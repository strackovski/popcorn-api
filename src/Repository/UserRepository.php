<?php

namespace App\Repository;

use App\Entity\User;
use App\Exception\Request\NotFoundButRequiredException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class UserRepository
 *
 * @package      App\Repository
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class UserRepository extends AbstractRepository
{
    /**
     * SubscriptionRepository constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->repository = $entityManager->getRepository(User::class);
    }

    public function findSettings(): void
    {
    }

    /**
     * @param $email
     *
     * @return User
     * @throws NonUniqueResultException
     * @throws NotFoundButRequiredException
     */
    public function findByEmail($email): User
    {
        $qb = $this->createQueryBuilder('u')->select('u')->where('u.email = :email')->setParameter('email', $email);

        try {
            $r = $qb->getQuery()->getOneOrNullResult();
            if ($r === null) {
                throw new NotFoundButRequiredException(['User', 'email', $email]);
            }
        } catch (NonUniqueResultException $e) {
            throw $e;
        }

        return $r;
    }

    /**
     * @return array
     */
    public function getUserIds(): array
    {
        $users = $this->repository->findAll();
        $result = [];

        /** @var User $user */
        foreach ($users as $user) {
            $result[] = $user->getEmail();
        }

        return $result;
    }

    /**
     * @param      $id
     *
     * @return mixed
     */
    public function findUser($id)
    {
        return $this->findOneBy(['email' => $id]);
    }

    /**
     * @param $criteria
     *
     * @return mixed
     */
    public function search($criteria)
    {
        return $this->createQueryBuilder('u')->where('LOWER(u.username) LIKE :username')->orWhere(
            'LOWER(u.email) LIKE :email'
        )->setParameter('username', '%'.strtolower($criteria).'%')->setParameter(
            'email',
            '%'.strtolower($criteria).'%'
        )->getQuery()->getResult()
            ;
    }
}
