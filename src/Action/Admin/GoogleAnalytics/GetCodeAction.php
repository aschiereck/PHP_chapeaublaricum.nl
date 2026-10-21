<?php

namespace App\Action\Admin\GoogleAnalytics;

use App\Action\Admin\AbstractAdminAction;
use App\Service\GoogleAnalytics\IGoogleAnalyticsService;
use App\Service\License\LicenseValidator;

class GetCodeAction extends AbstractAdminAction
{
    const ROUTE = '/googleAnalytics/getCode';

    /**
     * @var IGoogleAnalyticsService
     */
    private $analyticsService;
    /**
     * @var LicenseValidator
     */
    private $licenseValidator;

    /**
     * IndexAction constructor.
     * @param IGoogleAnalyticsService $analyticsService
     * @param LicenseValidator $licenseValidator
     */
    public function __construct(IGoogleAnalyticsService $analyticsService, LicenseValidator $licenseValidator)
    {
        $this->analyticsService = $analyticsService;
        $this->licenseValidator = $licenseValidator;
    }

    /**
     * @return array
     */
    public function execute()
    {
        $license = $this->licenseValidator->getLicense();
        $this->analyticsService->makeAuthRequest(
            $license['evolvedId'],
            $this->buildUrl(CallBackAction::ROUTE, ['siteId', $this->getSite()->getId()], true)
        );
        die;
    }
}