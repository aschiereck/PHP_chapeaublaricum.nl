<?php

namespace App\Repository;

use App\Model\User;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository implements IUserRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $_em;

    /**
     * UserRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->_em = $entityManager;
    }

    /**
     * @param User $user
     */
    public function delete(User $user)
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }


    /**
     * @param User $user
     */
    public function save(User $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @param $id
     * @return User|object
     */
    public function findById($id)
    {
        return $this->_em->find(User::class, $id);
    }

    /**
     * @param string $email
     * @return User|null
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByEmail($email)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('user')
            ->from(User::class, 'user')
            ->where($queryBuilder->expr()->eq('user.email', ':email'))
            ->setParameter('email', $email)
            ->setMaxResults(1);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * @return User[]
     */
    public function findAll()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('user')
            ->from(User::class, 'user');

        return $queryBuilder->getQuery()->getResult();
    }
}