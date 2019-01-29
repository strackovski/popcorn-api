<?php

namespace App\Repository;

use App\Entity\NotificationSettings;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class NotificationSettingsRepository
 *
 * @package      App\Repository
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class NotificationSettingsRepository extends AbstractRepository
{
    /**
     * NotificationSettingsRepository constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->repository = $entityManager->getRepository(NotificationSettings::class);
    }
}
