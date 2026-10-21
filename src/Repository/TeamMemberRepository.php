<?php

namespace App\Repository;

use App\Model\TeamMember;
use Doctrine\ORM\EntityManagerInterface;

class TeamMemberRepository implements ITeamMemberRepository
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
     * @param TeamMember $teamMember
     */
    public function delete(TeamMember $teamMember)
    {
        $this->_em->remove($teamMember);
        $this->_em->flush();
    }


    /**
     * @param TeamMember $teamMember
     */
    public function save(TeamMember $teamMember)
    {
        $this->_em->persist($teamMember);
        $this->_em->flush();
    }

    /**
     * @param $id
     * @return TeamMember|object
     */
    public function findById($id)
    {
        return $this->_em->find(TeamMember::class, $id);
    }


    /**
     * @return TeamMember[]
     */
    public function findAll()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('t')
            ->from(TeamMember::class, 't')
            ->orderBy('t.position', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return int
     */
    public function countAll()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('count(t.id)')
            ->from(TeamMember::class, 't');

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @return int
     */
    public function getMaxPosition()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('max(t.position)')
            ->from(TeamMember::class, 't');

        return (int)$queryBuilder->getQuery()->getSingleScalarResult();
    }
}