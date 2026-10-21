<?php

namespace App\Repository;

use App\Model\Carousel;
use App\Model\Site;

class CarouselRepository extends AbstractRepository implements ICarouselRepository
{
    /**
     * @param Carousel $carousel
     */
    public function delete(Carousel $carousel)
    {
        $this->_em->remove($carousel);
        $this->_em->flush();
    }


    /**
     * @param Carousel $carousel
     */
    public function save(Carousel $carousel)
    {
        $this->_em->persist($carousel);
        $this->_em->flush();
    }

    /**
     * @param $id
     * @return Carousel|object
     */
    public function findById($id)
    {
        return $this->_em->find(Carousel::class, $id);
    }


    /**
     * @param Site $site
     * @return array
     */
    public function findList(Site $site)
    {
        $Carousels = array();
        foreach($site->getCarousel() as $Carousel)
        {
            $Carousels[$Carousel->getId()] = $Carousel->getName();
        }
        return $Carousels;
    }

}