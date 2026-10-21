<?php

namespace App\Action\Admin\Twitter;

use App\Action\Admin\AbstractAdminAction;
use App\Service\Twitter\ITwitterService;

class ClearAction extends AbstractAdminAction
{
    const ROUTE = '/twitter/clear';

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
        $this->twitterService->clear($this->getSite());
        $this->getFlash()->success('De Twitter gegevens zijn verwijderd');
        return $this->redirectTo(IndexAction::ROUTE);
    }
}