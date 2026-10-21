<?php

namespace App\Action\Admin\Slider;

use App\Action\Admin\AbstractAdminAction;
use App\Exception\NotFoundException;
use App\Repository\ISlideRepository;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteSlideAction extends AbstractAdminAction
{
    const ROUTE = '/deleteSlide/{id:\d+}';

    /**
     * @var ISlideRepository
     */
    private $_slideRepository;

    /**
     * @param ISlideRepository $slideRepository
     */
    public function __construct(ISlideRepository $slideRepository)
    {
        $this->_slideRepository = $slideRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $slide = $this->_slideRepository->findById($id);
        if(!$slide)
        {
            throw new NotFoundException();
        }

        $this->_slideRepository->delete($slide);

        $this->getFlash()->success('Slide verwijderd');

        return $this->redirectTo(ViewAction::ROUTE, ['id'=>$slide->getSlider()->getId()]);
    }
}