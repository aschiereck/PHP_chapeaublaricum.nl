<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 23-10-2016
 * Time: 21:19
 */
namespace App\Repository;

use App\Model\Attachment;

interface IAttachmentRepository
{
    /**
     * @param $filesArray
     * @return Attachment
     */
    public function fromUpload($filesArray);

    /**
     * @param $filesArray
     * @return array
     */
    public function getUploadValidationMessages($filesArray);
}