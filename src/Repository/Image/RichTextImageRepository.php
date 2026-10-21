<?php

namespace App\Repository\Image;

use App\Model\Image\RichTextImage;

class RichTextImageRepository extends AbstractImageRepository implements IRichTextImageRepository
{
    /**
     * @param $filesArray
     * @return UploadResult
     */
    public function createFromUpload($filesArray)
    {
        $image = new RichTextImage();
        return $this->__fromUpload($image, $filesArray);
    }

    /**
     * @param RichTextImage $image
     */
    public function delete(RichTextImage $image)
    {
        $this->_em->remove($image);
        $this->_em->flush();
    }
}