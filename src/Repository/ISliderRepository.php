<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 19-10-2016
 * Time: 22:37
 */
namespace App\Repository;

use App\Model\Site;
use App\Model\Slider;

interface ISliderRepository
{
    /**
     * @param Slider $slider
     */
    public function delete(Slider $slider);

    /**
     * @param Slider $slider
     */
    public function save(Slider $slider);

    /**
     * @param $id
     * @return Slider
     */
    public function findById($id);

    /**
     * @param Site $site
     * @return array
     */
    public function findList(Site $site);
}