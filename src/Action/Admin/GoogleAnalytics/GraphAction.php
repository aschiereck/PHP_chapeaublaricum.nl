<?php

namespace App\Action\Admin\GoogleAnalytics;

use App\Action\Admin\AbstractAdminAction;
use App\Repository\ISiteSettingRepository;
use App\Service\GoogleAnalytics\GoogleAnalyticsService;
use App\Service\GoogleAnalytics\IGoogleAnalyticsService;
use Zend\Diactoros\Response\JsonResponse;

class GraphAction extends AbstractAdminAction
{
    const ROUTE = '/googleAnalytics/graph';

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
     * @return JsonResponse
     */
    public function execute()
    {
        $graphData = null;
        $selectedProfileId = $this->settingRepository->getValue($this->getSite(), GoogleAnalyticsService::PROFILE_ID_SETTINGS_KEY);
        if($selectedProfileId)
        {
            $graphData = $this->analyticsService->getGraphData($this->getSite(), $selectedProfileId);
        }

        return new JsonResponse($graphData);
    }
}