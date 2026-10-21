<?php
namespace App\Repository;

use App\Model\PhotoAlbum;
use App\Model\Site;

interface IPhotoAlbumRepository
{
    /**
     * @param PhotoAlbum $photoAlbum
     */
    public function delete(PhotoAlbum $photoAlbum);

    /**
     * @param PhotoAlbum $photoAlbum
     */
    public function save(PhotoAlbum $photoAlbum);

    /**
     * @param $id
     * @return PhotoAlbum
     */
    public function findById($id);

    /**
     * @param Site $site
     * @return array
     */
    public function findList(Site $site);
}