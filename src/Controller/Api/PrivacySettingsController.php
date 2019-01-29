<?php

namespace App\Controller\Api;

use App\Controller\AbstractRestController;
use App\Entity\PrivacySettings;
use App\Entity\User;
use App\Repository\PrivacySettingsRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PrivacySettingsController
 *
 * @package      App\Controller\Api
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class PrivacySettingsController extends AbstractRestController
{
    /**
     * @var PrivacySettingsRepository
     */
    protected $repository;

    /**
     * Get privacy settings for current user.
     *
     * @Rest\Get("/me/privacy")
     */
    public function getAction(): \Symfony\Component\HttpFoundation\Response
    {
        if (!($user = $this->fetchUser()) instanceof User) {
            throw $this->createNotFoundException();
        }

        return $this->response($user->getPrivacy(), 200, ['settings_privacy']);
    }

    /**
     * Update privacy settings for current user.
     *
     * @Rest\Patch("/me/privacy")
     *
     * @param User    $user
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function patchAction(User $user, Request $request): \Symfony\Component\HttpFoundation\Response
    {
        try {
            $status = $this->formProcessor->process($user->getPrivacy(), $request);
        } catch (\Exception $e) {
            return $this->response($e->getMessage(), 500);
        }

        if ($status instanceof PrivacySettings) {
            $this->repository->save($status);

            return $this->response($status, 200, ['settings_privacy']);
        }

        return $this->response($this->getFormErrors($status), 400);
    }
}
