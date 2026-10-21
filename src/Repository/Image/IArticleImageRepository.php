<?php
namespace App\Repository\Image;

use App\Model\Image\ArticleImage;

interface IArticleImageRepository
{
    /**
     * @param $filesArray
     * @return UploadResult
     */
    public function createFromUpload($filesArray);

    /**
     * @param ArticleImage $articleImage
     */
    public function delete(ArticleImage $articleImage);
}