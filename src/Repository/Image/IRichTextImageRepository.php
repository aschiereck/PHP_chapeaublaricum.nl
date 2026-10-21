<?php
namespace App\Repository\Image;

use App\Model\Image\RichTextImage;

interface IRichTextImageRepository
{
    /**
     * @param $filesArray
     * @return UploadResult
     */
    public function createFromUpload($filesArray);

    /**
     * @param RichTextImage $image
     */
    public function delete(RichTextImage $image);
}