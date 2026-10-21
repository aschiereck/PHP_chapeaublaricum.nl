<?php
namespace App\Repository\Image;

use App\Model\Image\PhotoImage;

interface IPhotoImageRepository
{
    /**
     * @param $filesArray
     * @return UploadResult
     */
    public function createFromUpload($filesArray);

    /**
     * @param PhotoImage $photoImage
     */
    public function delete(PhotoImage $photoImage);
}