<?php

namespace App\Action\Admin\Twitter;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Service\Twitter\ITwitterService;

class IndexAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/twitter';
    /**
     * @var ITwitterService
     */
    private $twitterService;

    /**
     * IndexAction constructor.
     * @param ITwitterService $twitterService
     */
    public function __construct(ITwitterService $twitterService)
    {
        $this->twitterService = $twitterService;
    }

    /**
     * @return array
     */
    public function execute()
    {
        return [
            'profile' => $this->twitterService->getProfile($this->getSite())
        ];
    }
}