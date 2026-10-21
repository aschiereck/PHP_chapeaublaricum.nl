<?php

namespace App\Repository\Image;

use App\Model\Image\ArticleImage;

class ArticleImageRepository extends AbstractImageRepository implements IArticleImageRepository
{
    /**
     * @param $filesArray
     * @return UploadResult
     */
    public function createFromUpload($filesArray)
    {
        $slideImage = new ArticleImage();
        return $this->__fromUpload($slideImage, $filesArray);
    }

    /**
     * @param ArticleImage $articleImage
     */
    public function delete(ArticleImage $articleImage)
    {
        $this->_em->remove($articleImage);
        $this->_em->flush();
    }
}