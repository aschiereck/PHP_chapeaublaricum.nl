<?php

namespace App\Action\Admin\Twitter;

use App\Action\Admin\AbstractAdminAction;
use App\Service\License\LicenseValidator;
use App\Service\Twitter\ITwitterService;

class FetchRequestTokenAction extends AbstractAdminAction
{
    const ROUTE = '/twitter/fetchRequestToken';
    /**
     * @var ITwitterService
     */
    private $twitterService;
    /**
     * @var LicenseValidator
     */
    private $licenseValidator;

    /**
     * RetrieveAccessTokenAction constructor.
     * @param ITwitterService $twitterService
     * @param LicenseValidator $licenseValidator
     */
    public function __construct(ITwitterService $twitterService, LicenseValidator $licenseValidator)
    {
        $this->twitterService = $twitterService;
        $this->licenseValidator = $licenseValidator;
    }

    /**
     * @return array
     */
    public function execute()
    {
        $license = $this->licenseValidator->getLicense();
        $this->twitterService->getRequestToken(
            $license['evolvedId'],
            $this->buildUrl(CallBackAction::ROUTE, ['siteId'=>$this->getSite()->getId()], true)
        );
        die;
    }
}