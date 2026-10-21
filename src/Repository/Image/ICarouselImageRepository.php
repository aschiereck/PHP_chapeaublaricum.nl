<?php
namespace App\Repository\Image;

use App\Model\Image\CarouselImage;

interface ICarouselImageRepository
{
    /**
     * @param $filesArray
     * @return UploadResult
     */
    public function createFromUpload($filesArray);

    /**
     * @param CarouselImage $image
     */
    public function delete(CarouselImage $image);
}