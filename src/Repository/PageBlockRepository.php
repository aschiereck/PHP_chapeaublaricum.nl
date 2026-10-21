<?php

namespace App\Repository;

use App\Model\PageBlock\AbstractPageBlock;
use Doctrine\ORM\EntityManagerInterface;

class PageBlockRepository implements IPageBlockRepository
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
     * @param AbstractPageBlock $block
     */
    public function save(AbstractPageBlock $block)
    {
        $this->_em->persist($block);
        $this->_em->flush();
    }


    /**
     * @param AbstractPageBlock $block
     */
    public function delete(AbstractPageBlock $block)
    {
        $this->_em->remove($block);
        $this->_em->flush();
    }

    /**
     * @param AbstractPageBlock $block
     * @param $id
     * @return object
     */
    public function findById(AbstractPageBlock $block, $id)
    {
        return $this->_em->find(get_class($block), $id);
    }

    /**
     * @param $id
     * @return AbstractPageBlock|object
     */
    public function findAbstractPageBlockById($id)
    {
        return $this->_em->find(AbstractPageBlock::class, $id);
    }

    /**
     * @return int
     */
    public function getMaxPosition()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('max(p.position)')
            ->from(AbstractPageBlock::class, 'p');

        return (int)$queryBuilder->getQuery()->getSingleScalarResult();
    }
}