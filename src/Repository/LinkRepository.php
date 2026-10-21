<?php

namespace App\Repository;

use App\Model\Link\AbstractLink;
use Doctrine\ORM\EntityManagerInterface;

class LinkRepository implements ILinkRepository
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
     * @param $id
     * @return AbstractLink|object
     */
    public function findById($id)
    {
        return $this->_em->find(AbstractLink::class, $id);
    }
}