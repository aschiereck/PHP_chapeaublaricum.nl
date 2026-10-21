<?php

namespace App\Repository;

use App\Model\Attachment;
use App\Util\Sanitize;
use Doctrine\ORM\EntityManagerInterface;
use Zend\Validator\File\Size;
use Zend\Validator\File\UploadFile;
use Zend\Validator\ValidatorChain;

class AttachmentRepository implements IAttachmentRepository
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
     * @param $filesArray
     * @return Attachment
     */
    public function fromUpload($filesArray)
    {
        $attachment = new Attachment();

        if($this->getValidationChain()->isValid($filesArray))
        {
            $attachment->setName($filesArray['name']);
            $attachment->setFileName(Sanitize::filename($filesArray['name']));
            $this->save($attachment);

            $extension = pathinfo($filesArray['name'], PATHINFO_EXTENSION);
            $fileName = $attachment->getId() . '.' . $extension;
            $destinationFolder = MEDIA_FOLDER_PATH . Attachment::FOLDER_NAME . DIRECTORY_SEPARATOR;

            if(!is_dir($destinationFolder))
            {
                mkdir($destinationFolder, 0775, true);
            }

            if(move_uploaded_file($filesArray['tmp_name'], $destinationFolder . $fileName))
            {
                $attachment->setFileName($fileName);
                $this->save($attachment);
            }
            else
            {
                $this->delete($attachment);
            }
        }
        else
        {
            $attachment = null;
        }

        return $attachment;
    }

    /**
     * @param $filesArray
     * @return array
     */
    public function getUploadValidationMessages($filesArray)
    {
        $validation = $this->getValidationChain();
        $validation->isValid($filesArray);
        return $validation->getMessages();
    }

    /**
     * @return ValidatorChain
     */
    protected function getValidationChain()
    {
        $validation = new ValidatorChain();
        $validation->attach(new UploadFile());
        $validation->attach(new Size(['max'=>'10000000']));

        return $validation;
    }

    /**
     * @param Attachment $attachment
     */
    private function save(Attachment $attachment)
    {
        $this->_em->persist($attachment);
        $this->_em->flush();
    }

    /**
     * @param Attachment $attachment
     */
    private function delete(Attachment $attachment)
    {
        $this->_em->remove($attachment);
        $this->_em->flush();
    }
}