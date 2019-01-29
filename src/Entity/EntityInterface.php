<?php

namespace App\Entity;

/**
 * Interface EntityInterface
 *
 * @package      App\Entity
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
interface EntityInterface
{
    /**
     * @return \Ramsey\Uuid\Uuid
     */
    public function getId();

    /**
     * @return mixed
     */
    public function getModifiedAt();

    /**
     * @return mixed
     */
    public function getCreatedAt();
}
