<?php

namespace App\Action\Admin\PageBlock;

use App\Exception\NotFoundException;
use App\Model\PageBlock\PageBlockPhotoAlbum;
use App\Repository\IPhotoAlbumRepository;
use App\Service\Serialization\IPageBlockSerializerService;
use Psr\Http\Message\ResponseInterface as Response;

class PhotoAlbumAction extends AbstractPageBlockAction
{
    const ROUTE = '/page/block/photoalbum';

    /**
     * @var IPhotoAlbumRepository
     */
    private $photoAlbumRepository;

    /**
     * SliderAction constructor.
     * @param IPhotoAlbumRepository $photoAlbumRepository
     * @param IPageBlockSerializerService $pageBlockSerializerService
     */
    public function __construct(IPhotoAlbumRepository $photoAlbumRepository, IPageBlockSerializerService $pageBlockSerializerService)
    {
        parent::__construct($pageBlockSerializerService);
        $this->photoAlbumRepository = $photoAlbumRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $invalidFields = [];

        $pageBlock = new PageBlockPhotoAlbum();

        if($this->isPostRequest())
        {
            $id = $this->getPostParameter('albumId');
            $photoAlbum = $this->photoAlbumRepository->findById($id);
            if(!$photoAlbum)
            {
                throw new NotFoundException();
            }

            $pageBlock->setPhotoAlbum($photoAlbum);
        }

        if($this->isPostRequest() && !$this->isPageBuilderRequest())
        {
            if ($pageBlock->isValid())
            {
                return $this->createSuccessJson($pageBlock);
            }
            else
            {
                return $this->createFailedJson($pageBlock->getValidationErrors());
            }
        }

        return [
            'pageBlock' => $pageBlock,
            'invalidFields' => $invalidFields,
            'albums' => $this->photoAlbumRepository->findList($this->getSite())
        ];
    }
}