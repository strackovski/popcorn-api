<?php

namespace App\Controller\Api;

use App\Controller\AbstractRestController;
use App\Entity\Article;
use App\Entity\User;
use App\Repository\ArticleRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ArticleController
 *
 * @package      App\Controller\Api
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class ArticleController extends AbstractRestController
{
    /**
     * @var ArticleRepository
     */
    protected $repository;

    /**
     * Get articles
     *
     * @Rest\Get("/articles")
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function getAction(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        if (!($user = $this->fetchUser()) instanceof User) {
            throw $this->createNotFoundException();
        }

        return $this->response(
            $this->repository->findAll(),
            200,
            ['public', 'user_profile_public']
        );
    }

    /**
     * Get article
     *
     * @Rest\Get("/articles/{id}")
     *
     * @param         $id
     * @param Request $request
     *
     * @return mixed
     */
    public function getDetailsAction($id, Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $res = null;
        if (!($user = $this->fetchUser()) instanceof User) {
            throw $this->createNotFoundException();
        }

        try {
            $res = $this->repository->findOneBy(['id' => $id]);
        } catch (Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e) {
            return $this->response(
                ['error' => 'Invalid request.', 'reason' => $e->getMessage()],
                $e->getCode()
            );
        } catch (\Exception $e) {
            throw $this->createNotFoundException();
        }

        return $this->response(
            [$res],
            $res === null ? 404 : 200,
            ['public', 'user_profile_public']
        );
    }

    /**
     * Create new Article
     *
     * @Rest\Post("/articles")
     *
     * @param User    $user
     * @param Request $request
     *
     * @return mixed
     */
    public function postAction(User $user, Request $request): \Symfony\Component\HttpFoundation\Response
    {
        try {
            $article = new Article($user);
            $status = $this->formProcessor->process($article, $request);
        } catch (Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e) {
            return $this->response(
                ['error' => 'Invalid request.', 'reason' => $e->getMessage()],
                $e->getCode()
            );
        } catch (\Exception $e) {
            return $this->response(
                ['error' => 'Invalid request or record not found.', 'reason' => $e->getMessage()],
                400
            );
        }

        if ($status instanceof Article) {
            $this->repository->save($status);

            return $this->response([$status], 200, ['public', 'user_profile_public']);
        }

        return $this->response($this->getFormErrors($status), 400);
    }

    /**
     * Update existing Article
     *
     * @Rest\Patch("/articles/{id}")
     *
     * @param         $id
     * @param User    $user
     * @param Request $request
     *
     * @return mixed
     */
    public function patchAction($id, User $user, Request $request): \Symfony\Component\HttpFoundation\Response
    {
        try {
            $status = $this->repository->findOneBy(['id' => $id]);
            if (null !== $status) {
                $status = $this->formProcessor->process($status, $request);
            }
        } catch (Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e) {
            return $this->response(
                ['error' => 'Invalid request.', 'reason' => $e->getMessage()],
                $e->getCode()
            );
        } catch (\Exception $e) {
            return $this->response(
                ['error' => 'Invalid request or record not found.', 'reason' => $e->getMessage()],
                400
            );
        }

        if ($status instanceof Article) {
            $this->repository->save($status);

            return $this->response([$status], 200, ['public', 'user_profile_public']);
        }

        return null === $status ? $this->response(['error' => 'Not found.'], 404) : $this->response(
            $this->getFormErrors($status),
            400
        );
    }
}
