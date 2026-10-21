<?php
namespace App\Repository\Image;

use App\Model\Image\PageBlockImage;

interface IPageBlockImageRepository
{
    /**
     * @param $filesArray
     * @return UploadResult
     */
    public function createFromUpload($filesArray);

    /**
     * @param PageBlockImage $pageBlockImage
     */
    public function delete(PageBlockImage $pageBlockImage);

    /**
     * @param $id
     * @return PageBlockImage
     */
    public function findById($id);
}