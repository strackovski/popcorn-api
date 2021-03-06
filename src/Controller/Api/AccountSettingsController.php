<?php

namespace App\Controller\Api;

use App\Controller\AbstractRestController;
use App\Entity\AccountSettings;
use App\Entity\User;
use App\Repository\AccountSettingsRepository;
use Faker\Factory;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AccountSettingsController
 *
 * @package      App\Controller\Api
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class AccountSettingsController extends AbstractRestController
{
    /**
     * @var AccountSettingsRepository
     */
    protected $repository;

    /**
     * Get account settings for current user.
     *
     * @Rest\Get("/cards")
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function getCardsAction(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $count = $request->query->get('count', 1);

        if ($count > 20) {
            $count = 20;
        }

        if (!($user = $this->fetchUser()) instanceof User) {
            throw $this->createNotFoundException();
        }

        $initial = ["customer1.jpg", "customer2.jpg", "customer3.jpg"];
        $final = [];

        $faker = Factory::create();
        for ($i = 1; $i <= $count; $i++) {
            foreach ($initial as $item) {
                $final[] = [
                    'title' => $faker->firstName.' '.$faker->lastName,
                    'text' => $faker->realText(),
                    'image' => $item,
                    'link' => '#',
                ];
            }
        }

        return $this->response(
            $final,
            200,
            ['settings_account', 'public']
        );
    }

    /**
     * Get account settings for current user.
     *
     * @Rest\Get("/me/account")
     */
    public function getAction(): \Symfony\Component\HttpFoundation\Response
    {
        if (!($user = $this->fetchUser()) instanceof User) {
            throw $this->createNotFoundException();
        }

        return $this->response($user->getAccount(), 200, ['settings_account']);
    }

    /**
     * Update account settings for current user.
     *
     * @Rest\Patch("/me/account")
     *
     * @param User    $user
     * @param Request $request
     *
     * @return mixed
     */
    public function patchAction(User $user, Request $request): \Symfony\Component\HttpFoundation\Response
    {
        try {
            $status = $this->formProcessor->process($user->getAccount(), $request);
        } catch (\Exception $e) {
            return $this->response($e->getMessage(), 500);
        }

        if ($status instanceof AccountSettings) {
            $this->repository->save($status);

            return $this->response($status, 200, ['settings_account']);
        }

        return $this->response($this->getFormErrors($status), 400);
    }
}
