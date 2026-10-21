<?php

namespace App\Service\Serialization\PageBlock;

use App\Model\PageBlock\PageBlockPhotoAlbum;
use App\Repository\IPhotoAlbumRepository;

class PageBlockPhotoAlbumSerializer implements IPageBlockSerializer
{
    /**
     * @var IPhotoAlbumRepository
     */
    private $photoAlbumRepository;

    /**
     * PageBlockPhotoAlbumSerializer constructor.
     * @param IPhotoAlbumRepository $photoAlbumRepository
     */
    public function __construct(IPhotoAlbumRepository $photoAlbumRepository)
    {
        $this->photoAlbumRepository = $photoAlbumRepository;
    }

    /**
     * @param PageBlockPhotoAlbum $pageBlock
     * @return string
     */
    public function toArray($pageBlock)
    {
        return [
            'albumId' => $pageBlock->getPhotoAlbum()->getId()
        ];
    }

    /**
     * @param array $values
     * @param PageBlockPhotoAlbum $pageBlock
     * @return PageBlockPhotoAlbum $pageBlock
     */
    public function fromArray($values, $pageBlock)
    {
        $pageBlock = $pageBlock ? $pageBlock : new PageBlockPhotoAlbum();

        $albumId = (int)$values['albumId'];
        $album = $this->photoAlbumRepository->findById($albumId);

        $pageBlock->setPhotoAlbum($album);

        return $pageBlock;
    }
}
