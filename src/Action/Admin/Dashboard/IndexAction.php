<?php

namespace App\Action\Admin\Dashboard;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Model\Module;
use App\Repository\IModuleRepository;
use App\Repository\ISiteRepository;
use App\Repository\ISiteSettingRepository;
use App\Service\Evolved\IApi;
use App\Service\Seo\ISeoService;

class IndexAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/dashboard';
    /**
     * @var ISeoService
     */
    private $seoService;
    /**
     * @var IModuleRepository
     */
    private $moduleRepository;
    /**
     * @var IApi
     */
    private $api;
    /**
     * @var ISiteSettingRepository
     */
    private $settingRepository;
    /**
     * @var ISiteRepository
     */
    private $siteRepository;

    /**
     * IndexAction constructor.
     * @param ISeoService $seoService
     * @param IModuleRepository $moduleRepository
     * @param IApi $api
     * @param ISiteSettingRepository $settingRepository
     * @param ISiteRepository $siteRepository
     */
    public function __construct(ISeoService $seoService, IModuleRepository $moduleRepository, IApi $api, ISiteSettingRepository $settingRepository, ISiteRepository $siteRepository)
    {
        $this->seoService = $seoService;
        $this->moduleRepository = $moduleRepository;
        $this->api = $api;
        $this->settingRepository = $settingRepository;
        $this->siteRepository = $siteRepository;
    }

    /**
     * @return array
     */
    public function execute()
    {
        $this->getViewModel()->showTitle = false;

        if($this->moduleRepository->isEnabled(Module::ARTICLE))
        {
            $lastCron = $this->settingRepository->getValue($this->siteRepository->findFirstSite(), \App\Action\Admin\Article\IndexAction::LAST_CRON_JOB_RUN_SETTING);
            if (!$lastCron)
            {
                $this->getFlash()->error('De cronjob voor het publiceren van artikelen is niet actief.');
            }
        }

        return [
            'focusWords' => $this->seoService->getTopFocusWords($this->getSite()),
            'seoScore' => $this->seoService->calculateGlobalSeoScore($this->getSite()),
            'showGaGraph' => $this->moduleRepository->isEnabled(Module::GOOGLE_ANALYTICS),
            'gaGraphColor' => $this->api->getBranding()->getPrimaryColor()
        ];
    }
}