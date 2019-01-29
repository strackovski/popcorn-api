<?php

namespace App\Service\Manager;

use App\Entity\EntityInterface;

/**
 * Interface ManagerInterface
 *
 * @package      App\Service\Manager
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
interface ManagerInterface
{
    /**
     * @param EntityInterface ...$entities
     *
     * @return EntityInterface
     */
    public function create(EntityInterface ...$entities): EntityInterface;

    /**
     * @return mixed
     */
    public function getEntityClass();
}
