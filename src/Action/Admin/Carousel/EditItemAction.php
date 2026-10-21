<?php

namespace App\Action\Admin\Carousel;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Exception\NotFoundException;
use App\Model\CarouselItem;
use App\Repository\ICarouselItemRepository;
use App\Repository\ICarouselRepository;
use App\Repository\Image\ICarouselImageRepository;
use App\Repository\IPageRepository;
use Psr\Http\Message\ResponseInterface as Response;


class EditItemAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/carousel/editItem/{parentId:\d+}[/{id:\d+}]';

    /**
     * @var ICarouselRepository
     */
    private $carouselRepository;
    /**
     * @var ICarouselItemRepository
     */
    private $carouselItemRepository;
    /**
     * @var IPageRepository
     */
    private $pageRepository;
    /**
     * @var ICarouselImageRepository
     */
    private $carouselImageRepository;

    /**
     * EditItemAction constructor.
     * @param ICarouselRepository $carouselRepository
     * @param ICarouselItemRepository $carouselItemRepository
     * @param IPageRepository $pageRepository
     * @param ICarouselImageRepository $carouselImageRepository
     */
    public function __construct(ICarouselRepository $carouselRepository, ICarouselItemRepository $carouselItemRepository, IPageRepository $pageRepository, ICarouselImageRepository $carouselImageRepository)
    {
        $this->carouselRepository = $carouselRepository;
        $this->carouselItemRepository = $carouselItemRepository;
        $this->pageRepository = $pageRepository;
        $this->carouselImageRepository = $carouselImageRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $carouselId = $this->getRouteParameter('parentId');
        $carousel = $this->carouselRepository->findById($carouselId);
        if(!$carousel)
        {
            throw new NotFoundException();
        }
        $invalidFields = [];

        if($id)
        {
            $carouselItem = $this->carouselItemRepository->findById($id);
            if(!$carouselItem)
            {
                throw new NotFoundException();
            }
        }
        else
        {
            $carouselItem = new CarouselItem();
            $carouselItem->setCarousel($carousel);
            $carouselItem->setPosition($this->carouselItemRepository->getMaxPosition() + 1);
        }

        if($this->isPostRequest())
        {
            $carouselItem->setName($this->getPostParameter('name'));
            $carouselItem->setLink($this->createLink($this->pageRepository));

            $imageData = $this->getFilesParameter('image');
            $uploadResult = $this->carouselImageRepository->createFromUpload($imageData);
            if($uploadResult->isUploadedWithoutErrors())
            {
                $carouselItem->setCarouselImage($uploadResult->getImage());
            }
            else if($uploadResult->isFileUploaded())
            {
                $invalidFields['image'] = $uploadResult->getValidationErrors();
            }

            if ($carouselItem->isValid() && (!$uploadResult->isFileUploaded() || $uploadResult->isValidImage()))
            {
                $this->carouselItemRepository->save($carouselItem);
                $this->getFlash()->success('Gegevens opgeslagen');
                return $this->redirectTo(ViewAction::ROUTE, ['id'=>$carouselId]);
            }
            else
            {
                $invalidFields = array_merge(
                    $invalidFields,
                    $carouselItem->getValidationErrors()
                );
            }
        }

        return [
            'carousel' => $carousel,
            'carouselItem' => $carouselItem,
            'invalidFields' => $invalidFields,
            'pages' => $this->pageRepository->findList($this->getSite())
        ];
    }
}