<?php

namespace App\Repository;

use App\Model\PhotoAlbum;
use App\Model\Site;
use Doctrine\ORM\EntityManagerInterface;

class PhotoAlbumRepository implements IPhotoAlbumRepository
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
     * @param PhotoAlbum $photoAlbum
     */
    public function delete(PhotoAlbum $photoAlbum)
    {
        $this->_em->remove($photoAlbum);
        $this->_em->flush();
    }


    /**
     * @param PhotoAlbum $photoAlbum
     */
    public function save(PhotoAlbum $photoAlbum)
    {
        $this->_em->persist($photoAlbum);
        $this->_em->flush();
    }

    /**
     * @param $id
     * @return PhotoAlbum|object
     */
    public function findById($id)
    {
        return $this->_em->find(PhotoAlbum::class, $id);
    }

    /**
     * @return PhotoAlbum[]
     */
    public function findAll()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('album')
            ->from(PhotoAlbum::class, 'album');

        return $queryBuilder->getQuery()->getResult();
    }


    /**
     * @param Site $site
     * @return array
     */
    public function findList(Site $site)
    {
        $albums = array();
        foreach($site->getPhotoAlbums() as $album)
        {
            $albums[$album->getId()] = $album->getName();
        }
        return $albums;
    }

    /**
     * @return int
     */
    public function countAll()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('count(album.id)')
            ->from(PhotoAlbum::class, 'album');

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
}