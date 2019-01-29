<?php

namespace App\Service\Mutator;

use App\Entity\EntityInterface;
use App\Service\Primitive\StringTools;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Psr\Log\LoggerInterface;

/**
 * Class AbstractEntityMutator
 *
 *
 *
 * @package      App\Service\Mutator
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
abstract class AbstractEntityMutator implements MutatorInterface
{
    /**
     * The entity manager.
     *
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AbstractEntityMutator constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface        $logger
     */
    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * Persist and flush object changes to database
     *
     * @param EntityInterface $entity
     *
     * @return EntityInterface
     * @throws \Exception
     */
    public function save(EntityInterface $entity): EntityInterface
    {
        $this->persist($entity);
        $this->flush();

        return $entity;
    }

    /**
     * Tells the ObjectManager to make an instance managed and persistent.
     *
     * The object will be entered into the database as a result of the flush operation.
     *
     * @param EntityInterface $entity The instance to make managed and persistent.
     *
     * @return void
     */
    public function persist(EntityInterface $entity): void
    {
        $this->entityManager->persist($entity);
    }

    /**
     * Flushes all changes to objects that have been queued up to now to the database.
     *
     * @return void
     */
    public function flush(): void
    {
        $this->entityManager->flush();
    }

    /**
     * Flush and persist if possible (?)
     *
     * @param EntityInterface|null $entity
     *
     * @return bool
     * @throws \Exception
     */
    public function update(EntityInterface $entity = null): bool
    {
        try {
            if ($entity !== null) {
                $this->persist($entity);
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        $this->flush();

        return true;
    }

    /**
     * Delete entity and flush changes to database
     *
     * @param EntityInterface $entity
     *
     * @return bool
     * @throws \Exception
     */
    public function delete(EntityInterface $entity): bool
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        return true;
    }

    /**
     * Get managed entity class name
     *
     * @return string
     */
    public function getEntityClass(): string
    {
        return "App\\Entity\\".StringTools::classNameToClassId($this, true);
    }

    /**
     * Refreshes the persistent state of an object from the database,
     * overriding any local changes that have not yet been persisted.
     *
     * @param EntityInterface $entity The object to refresh.
     *
     * @return void
     */
    public function refresh(EntityInterface $entity): void
    {
        $this->entityManager->refresh($entity);
    }

    /**
     * Create a QueryBuilder instance
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder(): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder();
    }

    /**
     * Creates a new Query object.
     *
     * @param string $dql The DQL string.
     *
     * @return Query
     */
    public function createQuery($dql = ''): Query
    {
        return $this->entityManager->createQuery($dql);
    }
}
