<?php

namespace App\Repository\Image;

use App\Model\Image\PhotoImage;

class PhotoImageRepository extends AbstractImageRepository implements IPhotoImageRepository
{
    /**
     * @param $filesArray
     * @return UploadResult
     */
    public function createFromUpload($filesArray)
    {
        $photoImage = new PhotoImage();
        return $this->__fromUpload($photoImage, $filesArray);
    }

    /**
     * @param PhotoImage $photoImage
     */
    public function delete(PhotoImage $photoImage)
    {
        $this->_em->remove($photoImage);
        $this->_em->flush();
    }
}