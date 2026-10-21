<?php

namespace App\Model\Image;

use App\Model\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;

/**
 * @ORM\Entity
 * @ORM\Table(name="image")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="model", type="string")
 * @ORM\DiscriminatorMap({
 *     "SlideImage" = "SlideImage",
 *     "RichTextImage" = "RichTextImage",
 *     "ArticleImage" = "ArticleImage",
 *     "PhotoImage" = "PhotoImage",
 *     "PageBlockImage" = "PageBlockImage",
 *     "TeamMemberImage" = "TeamMemberImage",
 *     "CarouselImage" = "CarouselImage",
 *     "ProjectImage" = "ProjectImage"
 * })
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractImage extends AbstractEntity
{
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
     * Remove image after removing the database enrtry
     * @ORM\postRemove
     */
    public function __afterRemove()
    {
        if(is_file($this->getPath()))
        {
            unlink($this->getPath());
        }

        if($this->getId() && is_dir($this->getCacheFolderPath()))
        {
            $it = new RecursiveDirectoryIterator($this->getCacheFolderPath(), RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
            foreach($files as $file)
            {
                if(is_file($file->getRealPath()))
                {
                    unlink($file->getRealPath());
                }
            }

            rmdir($this->getCacheFolderPath());
        }
    }


    /***************************************************************************************
     * Custom
     ***************************************************************************************/

    /**
     * @return string
     */
    abstract public function getModelName();
    
    /**
     * @return string
     */
    public function getPath()
    {
        return MEDIA_FOLDER_PATH . $this->getModelName() . DIRECTORY_SEPARATOR . $this->getFileName();
    }

    /**
     * @return string
     */
    public function getCacheFolderPath()
    {
        return MEDIA_CACHE_FOLDER_PATH . $this->getModelName() . DIRECTORY_SEPARATOR . $this->getId() . DIRECTORY_SEPARATOR;
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
     * @return AbstractImage
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
     * @return AbstractImage
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }


}
