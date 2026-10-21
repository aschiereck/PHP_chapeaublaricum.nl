<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 17-12-2016
 * Time: 11:52
 */
namespace App\Repository;

use App\Model\Photo;

interface IPhotoRepository
{
    /**
     * @param Photo $photo
     */
    public function delete(Photo $photo);

    /**
     * @param Photo $photo
     */
    public function save(Photo $photo);

    /**
     * @param $id
     * @return Photo
     */
    public function findById($id);

    /**
     * @return int
     */
    public function getMaxPosition();
}