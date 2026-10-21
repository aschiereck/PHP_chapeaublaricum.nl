<?php

namespace App\Action\Admin\Facebook;

use App\Action\Admin\AbstractAdminAction;
use App\Service\Facebook\IFacebookService;
use App\Service\License\LicenseValidator;

class FetchRequestTokenAction extends AbstractAdminAction
{
    const ROUTE = '/facebook/fetchRequestToken';
    /**
     * @var IFacebookService
     */
    private $facebookService;
    /**
     * @var LicenseValidator
     */
    private $licenseValidator;

    /**
     * RetrieveAccessTokenAction constructor.
     * @param IFacebookService $facebookService
     * @param LicenseValidator $licenseValidator
     */
    public function __construct(IFacebookService $facebookService, LicenseValidator $licenseValidator)
    {
        $this->facebookService = $facebookService;
        $this->licenseValidator = $licenseValidator;
    }

    /**
     * @return array
     */
    public function execute()
    {
        $license = $this->licenseValidator->getLicense();
        $this->facebookService->fetchRequestToken(
            $license['evolvedId'],
            $this->buildUrl(CallBackAction::ROUTE, ['siteId'=>$this->getSite()->getId()], true)
        );
        die;
    }
}