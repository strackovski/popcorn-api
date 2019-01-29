<?php

namespace App\Repository;

use App\Entity\AccountSettings;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AccountSettingsRepository
 *
 * @package      App\Repository
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class AccountSettingsRepository extends AbstractRepository
{
    /**
     * AccountSettingsRepository constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->repository = $entityManager->getRepository(AccountSettings::class);
    }
}
