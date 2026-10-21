<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 22-2-2017
 * Time: 23:37
 */
namespace App\Repository;

use App\Model\Link\AbstractLink;

interface ILinkRepository
{
    /**
     * @param $id
     * @return AbstractLink
     */
    public function findById($id);
}