<?php
namespace App\Repository;

use App\Model\Carousel;
use App\Model\Site;

interface ICarouselRepository
{
    /**
     * @param Carousel $carousel
     */
    public function delete(Carousel $carousel);

    /**
     * @param Carousel $carousel
     */
    public function save(Carousel $carousel);

    /**
     * @param $id
     * @return Carousel
     */
    public function findById($id);

    /**
     * @param Site $site
     * @return array
     */
    public function findList(Site $site);
}