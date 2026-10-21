<?php

namespace App\Action\Admin\PhotoAlbum;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Model\PhotoAlbum;
use App\Repository\IPhotoAlbumRepository;

class CreateAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/photoAlbums/create';
    /**
     * @var IPhotoAlbumRepository
     */
    private $photoAlbumRepository;

    /**
     * CreateAction constructor.
     * @param IPhotoAlbumRepository $photoAlbumRepository
     */
    public function __construct(IPhotoAlbumRepository $photoAlbumRepository)
    {
        $this->photoAlbumRepository = $photoAlbumRepository;
    }

    /**
     * @return array|\Zend\Diactoros\Response\RedirectResponse
     */
    protected function execute()
    {
        $invalidFields = [];

        $album = new PhotoAlbum();
        $album->setSite($this->getSite());

        if($this->isPostRequest())
        {
            $album->setName($this->getPostParameter('name'));

            if ($album->isValid())
            {
                $this->photoAlbumRepository->save($album);
                $this->getFlash()->success('Gegevens opgeslagen');
                return $this->redirectTo(EditAction::ROUTE, ['id'=>$album->getId()]);
            }
            else
            {
                $invalidFields = $album->getValidationErrors();
            }
        }

        return [
            'album' => $album,
            'invalidFields' => $invalidFields,
        ];
    }
}