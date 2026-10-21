<?php

namespace App\Action\Admin\RichText;

use App\Action\Admin\AbstractAdminAction;
use App\Repository\Image\IRichTextImageRepository;
use App\View\ImageHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Stratigility\MiddlewareInterface;

class UploadImageAction extends AbstractAdminAction implements MiddlewareInterface
{
    const ROUTE = '/richtext/uploadImage';
    /**
     * @var IRichTextImageRepository
     */
    private $richTextImageRepository;
    /**
     * @var ImageHelper
     */
    private $imageHelper;

    /**
     * UploadImageAction constructor.
     * @param IRichTextImageRepository $richTextImageRepository
     * @param ImageHelper $imageHelper
     */
    public function __construct(IRichTextImageRepository $richTextImageRepository, ImageHelper $imageHelper)
    {
        $this->richTextImageRepository = $richTextImageRepository;
        $this->imageHelper = $imageHelper;
    }

    /**
     * @return Response|array
     */
    protected function execute()
    {
        $response = [
            'success' => false,
            'errorMessage' => '',
            'url' => '',
            'name'=>''
        ];

        $imageData = isset($_FILES[0]) ? $_FILES[0] : [];
        $uploadResult = $this->richTextImageRepository->createFromUpload($imageData);

        if($uploadResult->isUploadedWithoutErrors())
        {
            $response['success'] = true;
            $response['url'] = $this->imageHelper->getResizeUrl($uploadResult->getImage(), 1600, 1600);
            $response['name'] = $uploadResult->getImage()->getName();
        }
        else
        {
            $response['errorMessage'] .= implode('<br/>', $uploadResult->getValidationErrors());
            $response['success'] = false;

        }

        return new JsonResponse($response);
    }
}