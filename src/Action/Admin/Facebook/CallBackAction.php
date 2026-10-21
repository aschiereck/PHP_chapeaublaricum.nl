<?php

namespace App\Action\Admin\Facebook;

use App\Action\Admin\AbstractAdminAction;
use App\Service\Facebook\IFacebookService;

class CallBackAction extends AbstractAdminAction
{
    const ROUTE = '/facebook/callback';
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
        $this->facebookService->getAccessToken($this->getSite());
        return $this->redirectTo(IndexAction::ROUTE);
    }
}