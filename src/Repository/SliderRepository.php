<?php

namespace App\Repository;

use App\Model\Site;
use App\Model\Slider;
use Doctrine\ORM\EntityManagerInterface;

class SliderRepository implements ISliderRepository
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
     * @param Slider $slider
     */
    public function delete(Slider $slider)
    {
        $this->_em->remove($slider);
        $this->_em->flush();
    }


    /**
     * @param Slider $slider
     */
    public function save(Slider $slider)
    {
        $this->_em->persist($slider);
        $this->_em->flush();
    }

    /**
     * @param $id
     * @return Slider|object
     */
    public function findById($id)
    {
        return $this->_em->find(Slider::class, $id);
    }

    /**
     * @param Site $site
     * @return array
     */
    public function findList(Site $site)
    {
        $sliders = array();
        foreach($site->getSliders() as $slider)
        {
            $sliders[$slider->getId()] = $slider->getName();
        }
        return $sliders;
    }
}