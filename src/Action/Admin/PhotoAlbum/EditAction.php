<?php

namespace App\Action\Admin\PhotoAlbum;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Exception\NotFoundException;
use App\Repository\IPhotoAlbumRepository;
use App\Repository\IPhotoRepository;

class EditAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/photoAlbums/edit/{id:\d+}';

    /**
     * @var IPhotoAlbumRepository
     */
    private $photoAlbumRepository;
    /**
     * @var IPhotoRepository
     */
    private $photoRepository;

    /**
     * CreateAction constructor.
     * @param IPhotoAlbumRepository $photoAlbumRepository
     * @param IPhotoRepository $photoRepository
     */
    public function __construct(IPhotoAlbumRepository $photoAlbumRepository, IPhotoRepository $photoRepository)
    {
        $this->photoAlbumRepository = $photoAlbumRepository;
        $this->photoRepository = $photoRepository;
    }

    /**
     * @return array|\Zend\Diactoros\Response\RedirectResponse
     * @throws NotFoundException
     */
    public function execute()
    {
        $id = $this->getRouteParameter('id');
        $invalidFields = [];

        $photoAlbum = $this->photoAlbumRepository->findById($id);
        if(!$photoAlbum)
        {
            throw new NotFoundException();
        }

        if($this->isPostRequest())
        {
            $photoAlbum->setName($this->getPostParameter('name'));
            $photoData = $this->getPostParameter('photo');

            if ($photoAlbum->isValid())
            {
                foreach($photoAlbum->getPhotos() as $photo)
                {
                    if(!isset($photoData[$photo->getId()]))
                    {
                        $this->photoRepository->delete($photo);
                    }
                    else
                    {
                        $newPosition =  array_search($photo->getId(), array_keys($photoData));
                        $photo->setIsActive(true);
                        $photo->setName($photoData[$photo->getId()]);
                        $photo->setPosition($newPosition);
                        $this->photoRepository->save($photo);
                    }
                }

                $this->photoAlbumRepository->save($photoAlbum);
                $this->getFlash()->success('Gegevens opgeslagen');
                return $this->redirectTo(EditAction::ROUTE, ['id'=>$photoAlbum->getId()]);
            }
            else
            {
                $invalidFields = $photoAlbum->getValidationErrors();
            }
        }


        return [
            'photoAlbum' => $photoAlbum,
            'invalidFields' => $invalidFields
        ];
    }
}