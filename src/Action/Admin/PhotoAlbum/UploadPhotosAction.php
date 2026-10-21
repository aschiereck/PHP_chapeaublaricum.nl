<?php

namespace App\Action\Admin\PhotoAlbum;

use App\Action\Admin\AbstractAdminAction;
use App\Exception\NotFoundException;
use App\Model\Photo;
use App\Repository\Image\IPhotoImageRepository;
use App\Repository\IPhotoAlbumRepository;
use App\Repository\IPhotoRepository;
use App\View\ImageHelper;
use Zend\Diactoros\Response\JsonResponse;

class UploadPhotosAction extends AbstractAdminAction
{
    const ROUTE = '/photoAlbums/upload/{id:\d+}';

    /**
     * @var IPhotoAlbumRepository
     */
    private $photoAlbumRepository;
    /**
     * @var IPhotoImageRepository
     */
    private $photoImageRepository;
    /**
     * @var IPhotoRepository
     */
    private $photoRepository;
    /**
     * @var ImageHelper
     */
    private $imageHelper;

    /**
     * CreateAction constructor.
     * @param IPhotoAlbumRepository $photoAlbumRepository
     * @param IPhotoRepository $photoRepository
     * @param IPhotoImageRepository $photoImageRepository
     * @param ImageHelper $imageHelper
     */
    public function __construct(IPhotoAlbumRepository $photoAlbumRepository, IPhotoRepository $photoRepository, IPhotoImageRepository $photoImageRepository, ImageHelper $imageHelper)
    {
        $this->photoAlbumRepository = $photoAlbumRepository;
        $this->photoImageRepository = $photoImageRepository;
        $this->photoRepository = $photoRepository;
        $this->imageHelper = $imageHelper;
    }

    /**
     * @return JsonResponse
     * @throws NotFoundException
     */
    public function execute()
    {
        $id = $this->getRouteParameter('id');
        $album = $this->photoAlbumRepository->findById($id);
        if(!$album)
        {
            throw new NotFoundException();
        }

        $response = [];
        $files = $this->reArrayFiles($this->getFilesParameter('files'));
        foreach($files as $file)
        {
            $imageResponseData = [
                'name' => $file['name']
            ];

            $uploadResult = $this->photoImageRepository->createFromUpload($file);
            if($uploadResult->isUploadedWithoutErrors())
            {
                $photo = new Photo();
                $photo->setPhotoImage($uploadResult->getImage());
                $photo->setName($uploadResult->getImage()->getName());
                $photo->setPhotoAlbum($album);
                $photo->setPosition($this->photoRepository->getMaxPosition() + 1);
                $this->photoRepository->save($photo);

                $imageResponseData['id'] = $photo->getId();
                $imageResponseData['label'] = $photo->getName();
                $imageResponseData['url'] = $this->imageHelper->getExactResizeUrl($uploadResult->getImage(), 150, 150);
                $imageResponseData['position'] = $photo->getPosition();
            }
            else
            {
                $imageResponseData['error'] = 'Opslag van de foto is mislukt';
            }

            $response[] = $imageResponseData;
        }

        return new JsonResponse(['files'=>$response]);
    }

    /**
     * Reorder the file array for multiple uploads
     * @param $file_post
     * @return array
     */
    private function reArrayFiles($file_post) {

        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_ary;
    }
}