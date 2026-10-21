<?php

namespace App\Repository\Image;

use App\Model\Image\ProjectImage;

class ProjectImageRepository extends AbstractImageRepository implements IProjectImageRepository
{
    /**
     * @param $filesArray
     * @return UploadResult
     */
    public function createFromUpload($filesArray)
    {
        $image = new ProjectImage();
        return $this->__fromUpload($image, $filesArray);
    }

    /**
     * @param ProjectImage $image
     */
    public function delete(ProjectImage $image)
    {
        $this->_em->remove($image);
        $this->_em->flush();
    }
}