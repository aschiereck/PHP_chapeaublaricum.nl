<?php

namespace App\Repository\Image;

use App\Model\Image\AbstractImage;
use App\Model\Image\ArticleImage;
use App\Model\Image\CarouselImage;
use App\Model\Image\PageBlockImage;
use App\Model\Image\PhotoImage;
use App\Model\Image\ProjectImage;
use App\Model\Image\RichTextImage;
use App\Model\Image\SlideImage;
use App\Model\Image\TeamMemberImage;

class UploadResult
{
    /**
     * Indicates if the user has tried to upload a file
     * @var bool
     */
    private $isFileUploaded = false;
    /**
     * Indicates if the user has uploaded a valid image fo;e
     * @var bool
     */
    private $isValidImage = false;
    /**
     * @var AbstractImage
     */
    private $image;
    /**
     * @var string[];
     */
    private $validationErrors = [];

    /**
     * @return bool
     */
    public function isValidImage()
    {
        return $this->isValidImage;
    }

    /**
     * @param bool $isValidImage
     */
    public function setIsValidImage($isValidImage)
    {
        $this->isValidImage = $isValidImage;
    }

    /**
     * @return AbstractImage|ProjectImage|ArticleImage|CarouselImage|PageBlockImage|PhotoImage|RichTextImage|SlideImage|TeamMemberImage
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param AbstractImage $image
     * @return UploadResult
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return \string[]
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    /**
     * @param \string[] $validationErrors
     * @return UploadResult
     */
    public function setValidationErrors($validationErrors)
    {
        $this->validationErrors = $validationErrors;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFileUploaded()
    {
        return $this->isFileUploaded;
    }

    /**
     * @return bool
     */
    public function isUploadedWithoutErrors()
    {
        return $this->isFileUploaded() && $this->isValidImage();
    }

    /**
     * @param bool $isFileUploaded
     * @return UploadResult
     */
    public function setIsFileUploaded($isFileUploaded)
    {
        $this->isFileUploaded = $isFileUploaded;
        return $this;
    }
}