<?php

namespace App\Service\Manager;

use App\Entity\AccountSettings;
use App\Entity\EntityInterface;
use App\Entity\NotificationSettings;
use App\Entity\PrivacySettings;
use App\Entity\User;
use App\Repository\RepositoryInterface;
use App\Service\Mutator\MutatorInterface;
use App\Service\Primitive\StringTools;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AbstractManager
 *
 * @package      App\Service\Manager
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
abstract class AbstractManager implements RepositoryAwareManagerInterface
{
    /**
     * @var MutatorInterface
     */
    protected $mutator;

    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param EntityInterface ...$entities
     *
     * @return EntityInterface
     */
    abstract public function create(EntityInterface ...$entities): EntityInterface;

    /**
     * Returns the class name of the object managed by the manager.
     *
     * @return string
     */
    public function getEntityClass(): string
    {
        return "App\\Entity\\".StringTools::classNameToClassId($this, true);
    }

    /**
     * @param EntityInterface $entity
     * @param User|null       $user
     * @param string          $status
     *
     * @return void |null
     */
    public function updateStatus(EntityInterface $entity, User $user = null, $status = 'seen'): void
    {
    }

    /**
     * @param EntityInterface|null $entity
     */
    public function update(EntityInterface $entity = null): void
    {
        try {
            $this->mutator->update($entity);
        } catch (\Exception $e) {
            $this->mutator->getLogger()->error($e->getMessage());
        }
    }

    /**
     * @param EntityInterface $entity
     *
     * @return bool
     * @throws \Exception
     */
    public function delete(EntityInterface $entity): bool
    {
        try {
            $this->mutator->delete($entity);
        } catch (\Exception $e) {
            $this->mutator->getLogger()->error($e->getMessage());
        }

        return true;
    }

    /**
     * @param UserInterface|User $user
     *
     * @return User
     * @throws \Exception
     */
    public function provisionUserDefaults(User $user): User
    {
        if (!$user->getPrivacy() instanceof PrivacySettings) {
            $s = new PrivacySettings();
            $user = $user->setPrivacy($s);
            $this->save($s);
        }

        if (!$user->getAccount() instanceof AccountSettings) {
            $s = new AccountSettings();
            $user = $user->setAccount($s);
            $this->save($s);
        }

        if (!$user->getNotificationSettings() instanceof NotificationSettings) {
            $s = new NotificationSettings();
            $user = $user->setNotificationSettings($s);
            $this->save($s);
        }

        return $user;
    }

    /**
     * @param EntityInterface $entity
     *
     * @return EntityInterface
     * @throws \Exception
     */
    public function save(EntityInterface $entity): ?EntityInterface
    {
        try {
            return $this->mutator->save($entity);
        } catch (\Exception $e) {
            $this->mutator->getLogger()->error($e->getMessage());
        }

        return null;
    }

    /**
     * @return MutatorInterface
     */
    public function getMutator(): MutatorInterface
    {
        return $this->mutator;
    }

    /**
     * @param MutatorInterface $mutator
     */
    public function setMutator(MutatorInterface $mutator): void
    {
        $this->mutator = $mutator;
    }

    /**
     * @return RepositoryInterface
     */
    public function getRepository(): RepositoryInterface
    {
        return $this->repository;
    }

    /**
     * @param RepositoryInterface $repository
     */
    public function setRepository(RepositoryInterface $repository): void
    {
        $this->repository = $repository;
    }

    /**
     * @param RouterInterface $router
     */
    public function setRouter(RouterInterface $router): void
    {
        $this->router = $router;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     *
     *
     * @param array $dependencies Array of property => dependency class records.
     *
     * @return int Count of dependencies set
     */
    public function setDependencies(array $dependencies = []): int
    {
        $count = 0;
        foreach ($dependencies as $property => $dependency) {
            if (property_exists(\get_class($this), $property)) {
                $this->$property = $dependency;
                $count++;
            }
        }

        return $count;
    }
}
