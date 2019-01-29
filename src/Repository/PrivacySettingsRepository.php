<?php

namespace App\Repository;

use App\Entity\PrivacySettings;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class PrivacySettingsRepository
 *
 * @package      App\Repository
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class PrivacySettingsRepository extends AbstractRepository
{
    /**
     * PrivacySettingsRepository constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->repository = $entityManager->getRepository(PrivacySettings::class);
    }
}
