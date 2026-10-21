<?php

namespace App\Repository;

use App\Model\Slide;
use Doctrine\ORM\EntityManagerInterface;

class SlideRepository implements ISlideRepository
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
     * @param Slide $slide
     */
    public function delete(Slide $slide)
    {
        $this->_em->remove($slide);
        $this->_em->flush();
    }


    /**
     * @param Slide $slide
     */
    public function save(Slide $slide)
    {
        $this->_em->persist($slide);
        $this->_em->flush();
    }

    /**
     * @return int
     */
    public function getMaxPosition()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('max(s.position)')
            ->from(Slide::class, 's');

        return (int)$queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $id
     * @return Slide|object
     */
    public function findById($id)
    {
        return $this->_em->find(Slide::class, $id);
    }
}