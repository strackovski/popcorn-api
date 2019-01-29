<?php

namespace App\Service\Manager;

use App\Entity\EntityInterface;

/**
 * Class UserManager
 *
 * @package App\Service\Manager
 */
class UserManager extends AbstractManager
{
    /**
     * @param EntityInterface ...$entities
     *
     * @return EntityInterface
     */
    public function create(EntityInterface ...$entities): EntityInterface
    {
        // TODO: Implement create() method.

        return null;
    }
}
