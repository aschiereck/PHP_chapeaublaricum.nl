<?php

namespace App\Action\Admin\PageBlock;

use App\Model\PageBlock\PageBlockCustomTemplate;
use App\Repository\ICustomBlockTemplateRepository;
use App\Repository\ILinkRepository;
use App\Repository\Image\IPageBlockImageRepository;
use App\Repository\IPageRepository;
use App\Service\Serialization\IPageBlockSerializerService;
use Psr\Http\Message\ResponseInterface as Response;

class CustomTemplateAction extends AbstractPageBlockAction
{
    const ROUTE = '/page/block/customTemplate';

    /**
     * @var IPageBlockSerializerService
     */
    private $pageBlockSerializerService;
    /**
     * @var ICustomBlockTemplateRepository
     */
    private $customBlockTemplateRepository;
    /**
     * @var IPageRepository
     */
    private $pageRepository;
    /**
     * @var IPageBlockImageRepository
     */
    private $pageBlockImageRepository;
    /**
     * @var ILinkRepository
     */
    private $linkRepository;

    /**
     * SliderAction constructor.
     * @param IPageBlockSerializerService $pageBlockSerializerService
     * @param ICustomBlockTemplateRepository $customBlockTemplateRepository
     * @param IPageBlockImageRepository $pageBlockImageRepository
     * @param IPageRepository $pageRepository
     * @param ILinkRepository $linkRepository
     */
    public function __construct(
        IPageBlockSerializerService $pageBlockSerializerService,
        ICustomBlockTemplateRepository $customBlockTemplateRepository,
        IPageBlockImageRepository $pageBlockImageRepository,
        IPageRepository $pageRepository,
        ILinkRepository $linkRepository
    )
    {
        parent::__construct($pageBlockSerializerService);
        $this->pageBlockSerializerService = $pageBlockSerializerService;
        $this->customBlockTemplateRepository = $customBlockTemplateRepository;
        $this->pageRepository = $pageRepository;
        $this->pageBlockImageRepository = $pageBlockImageRepository;
        $this->linkRepository = $linkRepository;
    }

    /**
     * @return array|Response
     * @throws \Exception
     */
    protected function execute()
    {
        $invalidFields = [];
        $pageBlock = new PageBlockCustomTemplate();
        $pageBlock->setTemplate($this->getQueryParameter('template'));
        $imageErrors = [];

        if($this->isPostRequest())
        {
            //Overrule query parameter if template is in POST data
            if($this->getPostParameter('template'))
            {
                $pageBlock->setTemplate($this->getPostParameter('template'));
            }

            /**
             * Upload a new image
             */
            $imageData = isset($_FILES['image']) ? $_FILES['image'] : [];
            $uploadResult = $this->pageBlockImageRepository->createFromUpload($imageData);
            if($uploadResult->isUploadedWithoutErrors())
            {
                $pageBlock->setImage($uploadResult->getImage());
            }
            else if($uploadResult->isFileUploaded())
            {
                $imageErrors = $uploadResult->getValidationErrors();
            }

            /**
             * Load image by posted id
             */
            if(!$pageBlock->getImage() && $this->getPostParameter('imageId'))
            {
                $image = $this->pageBlockImageRepository->findById($this->getPostParameter('imageId'));
                $pageBlock->setImage($image);
            }

            /**
             * Set link from id
             */
            if($this->getPostParameter('linkId'))
            {
                $link = $this->linkRepository->findById($this->getPostParameter('linkId'));
                $pageBlock->setLink($link);
            }
            /**
             * Overwrite with data
             */
            $newLink = $this->createLink($this->pageRepository);
            if($newLink)
            {
                $pageBlock->setLink($newLink);
            }

            $pageBlock->setText($this->getPostParameter('text'));
            $pageBlock->setContent($this->getPostParameter('richtext'));
        }

        $template = $this->customBlockTemplateRepository->getByTemplateName($pageBlock->getTemplate());
        if(!$template)
        {
            throw new \Exception('Unknown template');
        }

        if(($this->isPostRequest() && !$this->isPageBuilderRequest()) || empty($template->getFields()))
        {
            if ($pageBlock->isValid() && !$imageErrors)
            {
                return $this->createSuccessJson($pageBlock);
            }
            else
            {
                $invalidFields = array_merge(
                    ['image' => $imageErrors],
                    $pageBlock->getValidationErrors()
                );
                return $this->createFailedJson($invalidFields);
            }
        }

        return [
            'pageBlock' => $pageBlock,
            'invalidFields' => $invalidFields,
            'template' => $template,
            'pages' => $this->pageRepository->findList($this->getSite())
        ];
    }
}