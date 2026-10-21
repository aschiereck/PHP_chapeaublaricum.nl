<?php

namespace App\Repository\Image;

use App\Model\Image\AbstractImage;
use App\Util\Sanitize;
use Doctrine\ORM\EntityManagerInterface;
use Zend\Validator\File\IsImage;
use Zend\Validator\File\Size;
use Zend\Validator\ValidatorChain;

abstract class AbstractImageRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $_em;

    /**
     * UserRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->_em = $entityManager;
    }

    /**
     * @param AbstractImage $imageObject
     * @param $filesArray
     * @return UploadResult
     */
    protected function __fromUpload(AbstractImage $imageObject, $filesArray)
    {
        $result = new UploadResult();

        $isFileUploaded = isset($filesArray['name']) && $filesArray['name'];
        $result->setIsFileUploaded($isFileUploaded);

        $validation = new ValidatorChain();
        $validation->attach(new IsImage());
        $validation->attach(new Size(['max' => '5000000']));

        if($isFileUploaded && $validation->isValid($filesArray))
        {
            $imageObject->setName($filesArray['name']);
            $imageObject->setFileName(Sanitize::filename($filesArray['name']));
            $this->_em->persist($imageObject);
            $this->_em->flush();

            $extension = pathinfo($filesArray['name'], PATHINFO_EXTENSION);
            $fileName = $imageObject->getId() . '.' . $extension;
            $destinationFolder = MEDIA_FOLDER_PATH . $imageObject->getModelName() . DIRECTORY_SEPARATOR;

            if (!is_dir($destinationFolder))
            {
                mkdir($destinationFolder, 0777, true);
            }

            if (move_uploaded_file($filesArray['tmp_name'], $destinationFolder . $fileName))
            {
                $imageObject->setFileName($fileName);
                $this->_em->persist($imageObject);
                $this->_em->flush();

                $result->setIsValidImage(true);
                $result->setImage($imageObject);
            }
            else
            {
                $this->_em->remove($imageObject);
                $this->_em->flush();
            }
        }
        else
        {
            $result->setIsValidImage(false);
            $result->setValidationErrors($validation->getMessages());
        }

        return $result;
    }
}