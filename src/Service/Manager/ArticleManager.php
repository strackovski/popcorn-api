<?php

namespace App\Service\Manager;

use App\Entity\EntityInterface;

/**
 * Class ArticleManager
 *
 * @package App\Service\Manager
 */
class ArticleManager extends AbstractManager
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
