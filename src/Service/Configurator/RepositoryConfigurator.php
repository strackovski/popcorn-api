<?php

namespace App\Service\Configurator;

use App\Repository\RepositoryInterface;
use App\Service\Primitive\StringTools;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class RepositoryConfigurator
 *
 * @package      App\Repository\Configurator
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class RepositoryConfigurator
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * RepositoryConfigurator constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param RepositoryInterface $repository
     */
    public function configure(RepositoryInterface $repository): void
    {
        $repositoryClass = ucfirst(StringTools::classNameToClassId(\get_class($repository), true));
        $repository->setRepository($this->entityManager->getRepository("App\\Entity\\$repositoryClass"));
    }
}
