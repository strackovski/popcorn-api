<?php

namespace App\Controller\Api;

use App\Controller\AbstractRestController;
use App\Entity\User;
use App\Form\ProfileType;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseNullableUserEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Mailer\Mailer;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 *
 * @package      App\Controller\Api
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class UserController extends AbstractRestController
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var UserManagerInterface
     */
    protected $userManager;
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;
    /**
     * @var FormFactory
     */
    private $formFactory;
    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @param ContainerInterface|null $container
     *
     * @throws \Exception
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->formFactory = $this->get('form.factory');
        $this->userManager = $this->get('fos_user.user_manager');
        $this->dispatcher = $this->get('event_dispatcher');
        $this->tokenGenerator = $this->get('fos_user.util.token_generator.default');
        $this->mailer = $this->get('app.custom_fos_user_mailer');
    }

    /**
     * Register a new user account
     *
     * @param UserPasswordEncoderInterface $encoder
     * @param Request                      $request
     *
     * @return mixed
     *
     * @Rest\View()
     * @Rest\Post("/register")
     * @throws \Exception
     */
    public function registerAction(UserPasswordEncoderInterface $encoder, Request $request)
    {
        $user = $this->userManager->createUser();
        $user->setEnabled(false);

        $event = new GetResponseUserEvent($user, $request);
        $this->dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->formFactory->createNamed(
            'user',
            RegistrationType::class,
            $user,
            ['validation_groups' => ['Registration', 'Default'], 'csrf_protection' => false]
        );

        $data = json_decode($request->getContent(), true);
        $form->submit($data, false);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $this->dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
                $user->addRole('ROLE_USER');
                $plainPassword = $data['plainPassword'];
                $user->setPassword($encoder->encodePassword($user, $plainPassword));
                $user->setEnabled(true);
                $this->userManager->updateUser($user);

                $this->dispatcher->dispatch(
                    FOSUserEvents::REGISTRATION_COMPLETED,
                    new FilterUserResponseEvent($user, $request, $event->getResponse())
                );

                $this->manager->provisionUserDefaults($user);

                return $this->response($user, 200, ['settings', 'user_basic']);
            }

            $event = new FormEvent($form, $request);
            $this->dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $event->getResponse()) {
                return $this->response($this->getFormErrors($form), 400);
            }
        }

        return $this->response($form, 400);
    }

    /**
     *
     * @param Request $request
     *
     * @return mixed
     *
     * @Rest\Post("/password/request")
     * @throws \Exception
     */
    public function requestResetPasswordAction(Request $request)
    {
        $username = $request->request->get('username');

        $user = $this->userManager->findUserByUsernameOrEmail($username);

        $event = new GetResponseNullableUserEvent($user, $request);
        $this->dispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $this->response($event->getResponse()->getContent(), 400);
        }

        if (null !== $user && !$user->isPasswordRequestNonExpired(60)) {
            $event = new GetResponseUserEvent($user, $request);
            $this->dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_REQUEST, $event);

            if (null !== $event->getResponse()) {
                return $this->response($event->getResponse()->getContent(), 401);
            }

            if (null === $user->getConfirmationToken()) {
                $user->setConfirmationToken($this->tokenGenerator->generateToken());
            }

            $event = new GetResponseUserEvent($user, $request);
            $this->dispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_CONFIRM, $event);

            if (null !== $event->getResponse()) {
                return $this->response($event->getResponse()->getContent(), 402);
            }

            $this->mailer->sendResettingEmailMessage($user);
            $user->setPasswordRequestedAt(new \DateTime());
            $this->userManager->updateUser($user);

            $event = new GetResponseUserEvent($user, $request);
            $this->dispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_COMPLETED, $event);

            if (null !== $event->getResponse()) {
                return $this->response($event->getResponse()->getContent(), 403);
            }
        }

        return $this->response([], 200);
    }

    /**
     *
     * @param Request $request
     *
     * @param         $token
     *
     * @return mixed
     *
     * @Rest\Post("/password/reset")
     */
    public function resetPasswordAction(Request $request, $token)
    {
        $user = $this->userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw $this->createNotFoundException();
        }

        $event = new GetResponseUserEvent($user, $request);
        $this->dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new FormEvent($form, $request);
            $this->dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_SUCCESS, $event);

            $this->userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                return $this->response([], 200);
            }

            $this->dispatcher->dispatch(
                FOSUserEvents::RESETTING_RESET_COMPLETED,
                new FilterUserResponseEvent($user, $request, $response)
            );

            return $this->response([], $response->getStatusCode());
        }

        return $this->response($this->getFormErrors($form), 200);
    }

    /**
     * @param User $user
     *
     * @return null|\Symfony\Component\Form\FormInterface|\Symfony\Component\HttpFoundation\Response
     *
     * @Rest\Get("/me/settings")
     */
    public function getProfileAction(User $user)
    {
        return $this->response($user, 200, ['settings', 'user_profile_public']);
    }

    /**
     * @param User    $user
     * @param Request $request
     *
     * @return null|\Symfony\Component\Form\FormInterface|\Symfony\Component\HttpFoundation\Response
     *
     * @Rest\Patch("/me/settings")
     */
    public function editProfileAction(User $user, Request $request)
    {
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var FormFactory $formFactory */
        $formFactory = $this->get('form.factory');

        $form = $formFactory->createNamed(
            'app_profile_edit',
            ProfileType::class,
            $user,
            ['validation_groups' => ['Default']]
        );

        $data = json_decode($request->getContent(), true);
        $form->submit($data, false);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UserManagerInterface $userManager */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);
            $userManager->updateUser($user);

            return $this->response($user, 200, ['settings', 'personal']);
        }

        return $this->response($form, 400);
    }

    /**
     * @param User    $user
     * @param Request $request
     *
     * @return null|\Symfony\Component\Form\FormInterface|\Symfony\Component\HttpFoundation\Response
     *
     * @Rest\Get("/users/search")
     */
    public function searchAction(User $user, Request $request)
    {
        if (!$request->query->get('criteria', false)) {
            return $this->response([], 200, ['user_profile_public']);
        }

        $users = $this->repository->search($request->query->get('criteria', null));

        return $this->response($users, 200, ['user_profile_public', 'public']);
    }

    /**
     * @return null|\Symfony\Component\Form\FormInterface|\Symfony\Component\HttpFoundation\Response
     *
     * @Rest\Get("/register/confirm")
     */
    public function confirmAction()
    {
        return $this->response([], 200);
    }

    /**
     * @return null|\Symfony\Component\Form\FormInterface|\Symfony\Component\HttpFoundation\Response
     *
     * @Rest\Get("/register/check")
     */
    public function checkEmailAction()
    {
        return $this->response([], 200);
    }

    /**
     * @param Request $request
     *
     * @return null|\Symfony\Component\Form\FormInterface|\Symfony\Component\HttpFoundation\Response
     *
     * @Rest\Post("/accounts")
     */
    public function checkUserExists(Request $request)
    {
        if (!$request->headers->has('app-token')) {
            throw $this->createAccessDeniedException();
        }

        if ($request->headers->get('app-token') !== $this->getParameter('app_token')) {
            throw $this->createAccessDeniedException('Invalid credentials.');
        }

        $username = $request->request->get('username', false);
        $email = $request->request->get('email', false);

        if ($username && $user = $this->repository->findOneBy(['username' => $username])) {
            return $this->response([], 200);
        }

        if ($email && $user = $this->repository->findOneBy(['email' => $email])) {
            return $this->response([], 200);
        }

        return $this->response([], 404);
    }
}
