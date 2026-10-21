<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Attachment extends AbstractEntity
{
    const FOLDER_NAME = 'attachment';

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name = '';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $fileName = '';

    /***************************************************************************************
     * Validate
     ***************************************************************************************/

    /**
     * @return array
     */
    protected function __validate()
    {
        $invalidFields = array();

        $nameValidation = new ValidatorChain();
        $nameValidation
            ->attach(new NotEmpty());

        $fileNameValidation = new ValidatorChain();
        $fileNameValidation
            ->attach(new NotEmpty());

        if(!$nameValidation->isValid($this->name))
        {
            $invalidFields['name'] = $nameValidation->getMessages();
        }
        if(!$fileNameValidation->isValid($this->fileName))
        {
            $invalidFields['fileName'] = $fileNameValidation->getMessages();
        }

        return $invalidFields;
    }

    /***************************************************************************************
     * Callbacks
     ***************************************************************************************/

    /**
     * Remove attachment after removing the database enrtry
     * @ORM\postRemove
     */
    public function __afterRemove()
    {
        if(is_file($this->getPath()))
        {
            unlink($this->getPath());
        }
    }


    /***************************************************************************************
     * Custom
     ***************************************************************************************/

    /**
     * @return string
     */
    public function getUrl()
    {
        return MEDIA_FOLDER_URL . self::FOLDER_NAME . '/' . $this->getFileName();
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return MEDIA_FOLDER_PATH . self::FOLDER_NAME . DIRECTORY_SEPARATOR . $this->getFileName();
    }


    /***************************************************************************************
     * Getters / Setters
     ***************************************************************************************/

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Attachment
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     * @return Attachment
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }
}
