<?php

namespace App\Repository;

use App\Model\CarouselItem;
use Doctrine\ORM\EntityManagerInterface;

class CarouselItemRepository implements ICarouselItemRepository
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
     * @param CarouselItem $item
     */
    public function delete(CarouselItem $item)
    {
        $this->_em->remove($item);
        $this->_em->flush();
    }


    /**
     * @param CarouselItem $item
     */
    public function save(CarouselItem $item)
    {
        $this->_em->persist($item);
        $this->_em->flush();
    }

    /**
     * @return int
     */
    public function getMaxPosition()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('max(c.position)')
            ->from(CarouselItem::class, 'c');

        return (int)$queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $id
     * @return CarouselItem|object
     */
    public function findById($id)
    {
        return $this->_em->find(CarouselItem::class, $id);
    }
}