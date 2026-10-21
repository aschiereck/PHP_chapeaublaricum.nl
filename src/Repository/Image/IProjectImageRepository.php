<?php
namespace App\Repository\Image;

use App\Model\Image\ProjectImage;

interface IProjectImageRepository
{
    /**
     * @param $filesArray
     * @return UploadResult
     */
    public function createFromUpload($filesArray);

    /**
     * @param ProjectImage $image
     */
    public function delete(ProjectImage $image);
}