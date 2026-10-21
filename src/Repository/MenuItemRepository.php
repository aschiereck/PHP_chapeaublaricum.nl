<?php

namespace App\Repository;

use App\Model\MenuItem;
use App\Model\Site;
use Doctrine\ORM\EntityManagerInterface;

class MenuItemRepository implements IMenuItemRepository
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
     * @param MenuItem $menuItem
     */
    public function delete(MenuItem $menuItem)
    {
        $this->_em->remove($menuItem);
        $this->_em->flush();
    }


    /**
     * @param MenuItem $menuItem
     */
    public function save(MenuItem $menuItem)
    {
        $this->_em->persist($menuItem);
        $this->_em->flush();
    }

    /**
     * @return int
     */
    public function getMaxPosition()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('max(m.position)')
            ->from(MenuItem::class, 'm');

        return (int)$queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $id
     * @return MenuItem|object
     */
    public function findById($id)
    {
        return $this->_em->find(MenuItem::class, $id);
    }

    /**
     * @param Site $site
     * @return MenuItem[]
     */
    public function findRootNodes(Site $site)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('m')
            ->from(MenuItem::class, 'm')
            ->where($queryBuilder->expr()->isNull('m.parent'))
            ->andWhere($queryBuilder->expr()->eq('m.site', $site->getId()))
            ->orderBy('m.position');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param Site $site
     * @param MenuItem $excludeItem
     * @return MenuItem[]
     */
    public function findRootNodesList(Site $site, MenuItem $excludeItem = null)
    {
        $rootItems = [
            0 => 'Geen'
        ];

        foreach($this->findRootNodes($site) as $item)
        {
            if($excludeItem && $excludeItem->getId() != $item->getId())
            {
                $rootItems[$item->getId()] = $item->getName();
            }
        }

        return $rootItems;
    }
}