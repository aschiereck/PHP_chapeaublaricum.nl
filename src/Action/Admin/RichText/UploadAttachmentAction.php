<?php

namespace App\Action\Admin\RichText;

use App\Action\Admin\AbstractAdminAction;
use App\Repository\IAttachmentRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Stratigility\MiddlewareInterface;

class UploadAttachmentAction extends AbstractAdminAction implements MiddlewareInterface
{
    const ROUTE = '/richtext/uploadAttachment';
    /**
     * @var IAttachmentRepository
     */
    private $attachmentRepository;

    /**
     * UploadAttachmentAction constructor.
     * @param IAttachmentRepository $attachmentRepository
     */
    public function __construct(IAttachmentRepository $attachmentRepository)
    {
        $this->attachmentRepository = $attachmentRepository;
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

        $fileData = isset($_FILES[0]) ? $_FILES[0] : [];
        $attachment = $this->attachmentRepository->fromUpload($fileData);

        if($attachment)
        {
            $response['success'] = true;
            $response['url'] = $attachment->getUrl();
            $response['name'] = $attachment->getName();
        }
        else
        {
            $response['errorMessage'] .= implode('<br/>', $this->attachmentRepository->getUploadValidationMessages($fileData));
            $response['success'] = false;

        }

        return new JsonResponse($response);
    }
}