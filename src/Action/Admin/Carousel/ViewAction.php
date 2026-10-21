<?php

namespace App\Action\Admin\Carousel;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Exception\NotFoundException;
use App\Model\Carousel;
use App\Repository\ICarouselRepository;


class ViewAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/carousel/view/{id:\d+}';

    /**
     * @var Carousel
     */
    private $carousel;

    /**
     * @var ICarouselRepository
     */
    private $carouselRepository;

    /**
     * IndexAction constructor.
     * @param ICarouselRepository $carouselRepository
     */
    public function __construct(ICarouselRepository $carouselRepository)
    {
        $this->carouselRepository = $carouselRepository;
    }

    /**
     * @return array
     * @throws NotFoundException
     */
    public function execute()
    {
        $invalidFields = [];
        $id = $this->getRouteParameter('id');
        $this->carousel = $this->carouselRepository->findById($id);

        if(!$this->carousel)
        {
            throw new NotFoundException();
        }

        if($this->isPostRequest())
        {
            $this->carousel->setName($this->getPostParameter('name'));
            if($this->carousel->isValid())
            {
                $this->carouselRepository->save($this->carousel);
                $this->getFlash()->success('Gegevens opgeslagen');
            }
            else
            {
                $invalidFields = $this->carousel->getValidationErrors();
            }

        }

        return [
            'invalidFields' => $invalidFields,
            'carousel' => $this->carousel
        ];
    }
}