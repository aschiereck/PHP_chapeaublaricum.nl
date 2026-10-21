<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 20-2-2017
 * Time: 16:58
 */
namespace App\Repository;

use App\Model\CarouselItem;

interface ICarouselItemRepository
{
    /**
     * @param CarouselItem $item
     */
    public function delete(CarouselItem $item);

    /**
     * @param CarouselItem $item
     */
    public function save(CarouselItem $item);

    /**
     * @return int
     */
    public function getMaxPosition();

    /**
     * @param $id
     * @return CarouselItem
     */
    public function findById($id);
}