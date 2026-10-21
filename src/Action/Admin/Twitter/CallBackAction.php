<?php

namespace App\Action\Admin\Twitter;

use App\Action\Admin\AbstractAdminAction;
use App\Service\Twitter\ITwitterService;

class CallBackAction extends AbstractAdminAction
{
    const ROUTE = '/twitter/callback';
    /**
     * @var ITwitterService
     */
    private $twitterService;

    /**
     * CallBackAction constructor.
     * @param ITwitterService $twitterService
     */
    public function __construct(ITwitterService $twitterService)
    {
        $this->twitterService = $twitterService;
    }

    /**
     * @return \Zend\Diactoros\Response\RedirectResponse
     */
    public function execute()
    {
        $this->twitterService->getAccessToken($this->getSite());
        return $this->redirectTo(IndexAction::ROUTE);
    }
}