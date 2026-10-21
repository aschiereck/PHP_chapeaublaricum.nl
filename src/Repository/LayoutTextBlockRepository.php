<?php

namespace App\Repository;

use App\Model\LayoutTextBlock;
use App\Model\Site;

class LayoutTextBlockRepository extends AbstractRepository implements ILayoutTextBlockRepository
{
    /**
     * @param LayoutTextBlock $textBlock
     */
    public function delete(LayoutTextBlock $textBlock)
    {
        $this->_em->remove($textBlock);
        $this->_em->flush();
    }


    /**
     * @param LayoutTextBlock $textBlock
     */
    public function save(LayoutTextBlock $textBlock)
    {
        $this->saveInternal($textBlock);
    }

    /**
     * @param $id
     * @return LayoutTextBlock|object
     */
    public function findById($id)
    {
        return $this->_em->find(LayoutTextBlock::class, $id);
    }

    /**
     * @return LayoutTextBlock[]
     */
    public function findAll()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('block')
            ->from(LayoutTextBlock::class, 'block');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param Site $site
     * @param string $tag
     * @return LayoutTextBlock
     */
    public function findByTag(Site $site, string $tag)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select('block')
            ->from(LayoutTextBlock::class, 'block')
            ->where($qb->expr()->eq('block.tag', ':tag'))
            ->andWhere($qb->expr()->eq('block.site', $site->getId()))
            ->setParameter('tag', $tag)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}