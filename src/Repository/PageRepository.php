<?php

namespace App\Repository;

use App\Model\Page;
use App\Model\Site;
use Doctrine\ORM\EntityManagerInterface;

class PageRepository extends AbstractRepository implements IPageRepository
{
    /**
     * @param Page $page
     */
    public function delete(Page $page)
    {
        $this->_em->remove($page);
        $this->_em->flush();
    }

    /**
     * @param Page $page
     */
    public function save(Page $page)
    {
        $this->saveInternal($page);
    }

    /**
     * @param $id
     * @return Page|object
     */
    public function findById($id)
    {
        return $this->_em->find(Page::class, $id);
    }

    /**
     * @param Site $site
     * @return array
     */
    public function findList(Site $site)
    {
        $pages = array();
        foreach($site->getPages() as $page)
        {
            $pages[$page->getId()] = $page->getName();
        }
        return $pages;
    }

    /**
     * @param Site $site
     * @param string $url
     * @return Page
     */
    public function findPageByUrl(Site $site, $url)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('page')
            ->from(Page::class, 'page')
            ->andWhere($qb->expr()->eq('page.url', ':url'))
            ->andWhere($qb->expr()->eq('page.site', $site->getId()))
            ->setParameter('url', $url);

        return $qb->getQuery()->getOneOrNullResult();
    }
}