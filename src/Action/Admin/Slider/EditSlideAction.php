<?php

namespace App\Action\Admin\Slider;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Exception\NotFoundException;
use App\Model\Slide;
use App\Repository\Image\ISlideImageRepository;
use App\Repository\IPageRepository;
use App\Repository\ISlideRepository;
use App\Repository\ISliderRepository;
use Psr\Http\Message\ResponseInterface as Response;

class EditSlideAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/slider/editSlide/{parentId:\d+}[/{id:\d+}]';

    /**
     * @var ISliderRepository
     */
    private $_sliderRepository;
    /**
     * @var ISlideRepository
     */
    private $_slideRepository;
    /**
     * @var IPageRepository
     */
    private $_pageRepository;
    /**
     * @var ISlideImageRepository
     */
    private $_slideImageRepository;

    /**
     * @param ISliderRepository $sliderRepository
     * @param ISlideRepository $slideRepository
     * @param IPageRepository $pageRepository
     * @param ISlideImageRepository $slideImageRepository
     */
    public function __construct(ISliderRepository $sliderRepository, ISlideRepository $slideRepository, IPageRepository $pageRepository, ISlideImageRepository $slideImageRepository)
    {
        $this->_sliderRepository = $sliderRepository;
        $this->_slideRepository = $slideRepository;
        $this->_pageRepository = $pageRepository;
        $this->_slideImageRepository = $slideImageRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $sliderId = $this->getRouteParameter('parentId');
        $slider = $this->_sliderRepository->findById($sliderId);
        if(!$slider)
        {
            throw new NotFoundException();
        }
        $invalidFields = [];

        if($id)
        {
            $slide = $this->_slideRepository->findById($id);
            if(!$slide)
            {
                throw new NotFoundException();
            }
        }
        else
        {
            $slide = new Slide();
            $slide->setSlider($slider);
            $slide->setPosition($this->_slideRepository->getMaxPosition() + 1);
        }

        if($this->isPostRequest())
        {
            $slide->setName($this->getPostParameter('name'));
            $slide->setSummary($this->getPostParameter('summary'));
            $slide->setLinkText($this->getPostParameter('linkText'));

            if($this->getPostParameter('hasLink'))
            {
                $slide->setLink($this->createLink($this->_pageRepository));
            }
            else
            {
                $slide->setLink(null);
            }

            $imageData = $this->getFilesParameter('slideImage');
            $uploadResult = $this->_slideImageRepository->createFromUpload($imageData);
            if($uploadResult->isUploadedWithoutErrors())
            {
                $slide->setSlideImage($uploadResult->getImage());
            }
            else if($uploadResult->isFileUploaded())
            {
                $invalidFields['slideImage'] = $uploadResult->getValidationErrors();
            }

            if ($slide->isValid() && (!$uploadResult->isFileUploaded() || $uploadResult->isValidImage()))
            {
                $this->_slideRepository->save($slide);
                $this->getFlash()->success('Gegevens opgeslagen');
                return $this->redirectTo(ViewAction::ROUTE, ['id'=>$sliderId]);
            }
            else
            {
                $invalidFields = array_merge(
                    $invalidFields,
                    $slide->getValidationErrors()
                );
            }
        }

        return [
            'sliderId' => $sliderId,
            'slide' => $slide,
            'invalidFields' => $invalidFields,
            'pages' => $this->_pageRepository->findList($this->getSite())
        ];
    }
}