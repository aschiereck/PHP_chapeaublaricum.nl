<?php

namespace App\Repository;

use App\Model\Photo;
use Doctrine\ORM\EntityManagerInterface;

class PhotoRepository implements IPhotoRepository
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
     * @param Photo $photo
     */
    public function delete(Photo $photo)
    {
        $this->_em->remove($photo);
        $this->_em->flush();
    }


    /**
     * @param Photo $photo
     */
    public function save(Photo $photo)
    {
        $this->_em->persist($photo);
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
            ->from(Photo::class, 's');

        return (int)$queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $id
     * @return Photo|object
     */
    public function findById($id)
    {
        return $this->_em->find(Photo::class, $id);
    }
}