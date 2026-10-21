<?php

namespace App\Action\Admin\Facebook;

use App\Action\Admin\AbstractAdminAction;
use App\Service\Facebook\IFacebookService;

class ClearAction extends AbstractAdminAction
{
    const ROUTE = '/facebook/clear';

    /**
     * @var IFacebookService
     */
    private $facebookService;

    /**
     * CallBackAction constructor.
     * @param IFacebookService $facebookService
     */
    public function __construct(IFacebookService $facebookService)
    {
        $this->facebookService = $facebookService;
    }

    /**
     * @return \Zend\Diactoros\Response\RedirectResponse
     */
    public function execute()
    {
        $this->facebookService->clear($this->getSite());
        $this->getFlash()->success('De Facebook gegevens zijn verwijderd');
        return $this->redirectTo(IndexAction::ROUTE);
    }
}