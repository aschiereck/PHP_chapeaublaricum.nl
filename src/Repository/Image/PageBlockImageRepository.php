<?php

namespace App\Repository\Image;

use App\Model\Image\PageBlockImage;

class PageBlockImageRepository extends AbstractImageRepository implements IPageBlockImageRepository
{
    /**
     * @param $filesArray
     * @return UploadResult
     */
    public function createFromUpload($filesArray)
    {
        $slideImage = new PageBlockImage();
        return $this->__fromUpload($slideImage, $filesArray);
    }

    /**
     * @param PageBlockImage $pageBlockImage
     */
    public function delete(PageBlockImage $pageBlockImage)
    {
        $this->_em->remove($pageBlockImage);
        $this->_em->flush();
    }

    /**
     * @param $id
     * @return PageBlockImage|object
     */
    public function findById($id)
    {
        return $this->_em->find(PageBlockImage::class, $id);
    }
}