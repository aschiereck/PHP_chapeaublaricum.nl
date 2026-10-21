<?php

namespace App\Action\Admin\GoogleAnalytics;

use App\Action\Admin\AbstractAdminAction;
use App\Repository\ISiteSettingRepository;
use App\Service\GoogleAnalytics\GoogleAnalyticsService;
use App\Service\GoogleAnalytics\IGoogleAnalyticsService;

class CallBackAction extends AbstractAdminAction
{
    const ROUTE = '/googleAnalytics/callback';

    /**
     * @var IGoogleAnalyticsService
     */
    private $analyticsService;
    /**
     * @var ISiteSettingRepository
     */
    private $settingRepository;

    /**
     * IndexAction constructor.
     * @param IGoogleAnalyticsService $analyticsService
     * @param ISiteSettingRepository $settingRepository
     */
    public function __construct(IGoogleAnalyticsService $analyticsService, ISiteSettingRepository $settingRepository)
    {
        $this->analyticsService = $analyticsService;
        $this->settingRepository = $settingRepository;
    }

    /**
     * @return \Zend\Diactoros\Response\RedirectResponse
     */
    public function execute()
    {
        $code = $this->getQueryParameter('code');
        $refreshToken = $this->analyticsService->exchangeCodeForRefreshToken($code);
        $this->settingRepository->setValue($this->getSite(), GoogleAnalyticsService::REFRESH_TOKEN_SETTINGS_KEY, $refreshToken);
        return $this->redirectTo(IndexAction::ROUTE);
    }
}