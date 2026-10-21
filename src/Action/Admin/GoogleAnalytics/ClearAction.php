<?php

namespace App\Action\Admin\GoogleAnalytics;

use App\Action\Admin\AbstractAdminAction;
use App\Repository\ISiteSettingRepository;
use App\Service\GoogleAnalytics\IGoogleAnalyticsService;

class ClearAction extends AbstractAdminAction
{
    const ROUTE = '/googleAnalytics/clear';

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
        $this->analyticsService->clearData($this->getSite());
        $this->getFlash()->success('De Google Analytics gegevens zijn verwijderd');
        return $this->redirectTo(IndexAction::ROUTE);
    }
}