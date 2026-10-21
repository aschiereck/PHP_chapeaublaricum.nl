<?php

namespace App\Repository;

use App\Model\Slide;

interface ISlideRepository
{
    /**
     * @param Slide $slide
     */
    public function delete(Slide $slide);

    /**
     * @param Slide $slide
     */
    public function save(Slide $slide);

    /**
     * @return int
     */
    public function getMaxPosition();

    /**
     * @param $id
     * @return Slide
     */
    public function findById($id);
}