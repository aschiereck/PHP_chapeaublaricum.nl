<?php

namespace App\Action\Admin\PhotoAlbum;

use App\Action\Admin\AbstractAdminAction;
use App\Exception\NotFoundException;
use App\Repository\IPhotoAlbumRepository;

class DeleteAction extends AbstractAdminAction
{
    const ROUTE = '/photoAlbums/delete/{id:\d+}';

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
     * @return \Zend\Diactoros\Response\RedirectResponse
     * @throws NotFoundException
     */
    public function execute()
    {
        $id = $this->getRouteParameter('id');
        $photoAlbum = $this->photoAlbumRepository->findById($id);
        if(!$photoAlbum)
        {
            throw new NotFoundException();
        }

        $this->photoAlbumRepository->delete($photoAlbum);
        $this->getFlash()->success('Fotoalbum verwijderd');

        return $this->redirectTo(IndexAction::ROUTE);
    }
}