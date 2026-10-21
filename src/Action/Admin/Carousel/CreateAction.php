<?php

namespace App\Action\Admin\Carousel;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Model\Carousel;
use App\Repository\ICarouselRepository;
use Psr\Http\Message\ResponseInterface as Response;

class CreateAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/carousel/create';

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
     * @return Response|array
     */
    protected function execute()
    {
        $invalidFields = [];
        $carousel = new Carousel();
        $carousel->setSite($this->getSite());

        if($this->isPostRequest())
        {
            $carousel->setName($this->getPostParameter('name'));

            if ($carousel->isValid())
            {
                $this->carouselRepository->save($carousel);
                $this->getFlash()->success('Gegevens opgeslagen');
                return $this->redirectTo(ViewAction::ROUTE, ['id'=>$carousel->getId()]);
            }
            else
            {
                $invalidFields = $carousel->getValidationErrors();
            }
        }

        return [
            'carousel' => $carousel,
            'invalidFields' => $invalidFields
        ];
    }
}