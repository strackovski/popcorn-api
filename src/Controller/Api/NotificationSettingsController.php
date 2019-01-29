<?php

namespace App\Controller\Api;

use App\Controller\AbstractRestController;
use App\Entity\NotificationSettings;
use App\Entity\User;
use App\Repository\NotificationSettingsRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NotificationSettingsController
 *
 * @package      App\Controller\Api
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class NotificationSettingsController extends AbstractRestController
{
    /**
     * @var NotificationSettingsRepository
     */
    protected $repository;

    /**
     * Get notification settings for current user.
     *
     * @Rest\Get("/me/notifications")
     */
    public function getAction(): \Symfony\Component\HttpFoundation\Response
    {
        if (!($user = $this->fetchUser()) instanceof User) {
            throw $this->createNotFoundException();
        }

        return $this->response($user->getNotificationSettings(), 200, ['settings_notifications']);
    }

    /**
     * Update notification settings for current user.
     *
     * @Rest\Patch("/me/notifications")
     *
     * @param User    $user
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function patchAction(User $user, Request $request): \Symfony\Component\HttpFoundation\Response
    {
        try {
            $status = $this->formProcessor->process($user->getNotificationSettings(), $request);
        } catch (\Exception $e) {
            return $this->response($e->getMessage(), 500);
        }

        if ($status instanceof NotificationSettings) {
            $this->repository->save($status);

            return $this->response($status, 200, ['settings_notifications']);
        }

        return $this->response($this->getFormErrors($status), 400);
    }
}
