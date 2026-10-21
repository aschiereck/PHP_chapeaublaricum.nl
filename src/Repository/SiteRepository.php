<?php

namespace App\Repository;

use App\Model\Site;

class SiteRepository extends AbstractRepository implements ISiteRepository
{
    /**
     * @param Site $site
     */
    public function delete(Site $site)
    {
        $this->_em->remove($site);
        $this->_em->flush();
    }

    /**
     * @param Site $site
     */
    public function save(Site $site)
    {
        $this->saveInternal($site);
    }

    /**
     * @param $id
     * @return Site|object
     */
    public function findById($id)
    {
        return $this->_em->find(Site::class, $id);
    }

    /**
     * @return Site[]
     */
    public function findAll()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('s')
            ->from(Site::class, 's');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return Site
     */
    public function findFirstSite()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('s')
            ->from(Site::class, 's')
            ->setMaxResults(1);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string $host
     * @return Site
     */
    public function finByUrl($host)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('s')
            ->from(Site::class, 's')
            ->where($qb->expr()->eq('s.url', ':url'))
            ->setParameter('url', $host)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}