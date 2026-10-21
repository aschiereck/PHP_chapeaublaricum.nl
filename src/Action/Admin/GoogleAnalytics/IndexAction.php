<?php

namespace App\Action\Admin\GoogleAnalytics;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Repository\ISiteSettingRepository;
use App\Service\GoogleAnalytics\GoogleAnalyticsService;
use App\Service\GoogleAnalytics\IGoogleAnalyticsService;
use Psr\Log\LoggerInterface;

class IndexAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/googleAnalytics';

    /**
     * @var IGoogleAnalyticsService
     */
    private $analyticsService;
    /**
     * @var ISiteSettingRepository
     */
    private $settingRepository;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * IndexAction constructor.
     * @param IGoogleAnalyticsService $analyticsService
     * @param ISiteSettingRepository $settingRepository
     * @param LoggerInterface $logger
     */
    public function __construct(IGoogleAnalyticsService $analyticsService, ISiteSettingRepository $settingRepository, LoggerInterface $logger)
    {
        $this->analyticsService = $analyticsService;
        $this->settingRepository = $settingRepository;
        $this->logger = $logger;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function execute()
    {
        try
        {
            $profiles = $this->analyticsService->getProfiles($this->getSite());
        }
        catch(\Exception $e)
        {
            $this->logger->error($e->getMessage());
            $this->getFlash()->error('Het is niet gelukt om de gegevens van Google Analytics in te lezen. Stel het account opnieuw in.');
            $profiles = [];
        }

        $profileOptions = [
            '' => 'Selecteer een profiel'
        ];

        foreach($profiles as $profile)
        {
            $profileOptions[$profile->id] = $profile->websiteUrl . ' - ' . $profile->name;
        }

        if($this->isPostRequest())
        {
            $selectedProfileId = $this->getPostParameter('profileId');
            foreach($profiles as $profile)
            {
                if($profile->id == $selectedProfileId)
                {
                    $this->settingRepository->setValue(
                        $this->getSite(),
                        GoogleAnalyticsService::PROFILE_ID_SETTINGS_KEY,
                        $profile->id
                    );
                    $this->settingRepository->setValue(
                        $this->getSite(),
                        GoogleAnalyticsService::TRACKING_CODE_SETTINGS_KEY,
                        $profile->trackingCode
                    );

                    $this->getFlash()->success('Profiel opgeslagen');
                }
            }
        }

        try
        {
            $user = $this->analyticsService->getUserProfile($this->getSite());
        }
        catch(\Exception $e)
        {
            $user = null;
        }

        return [
            'user' => $user,
            'profileOptions' => $profileOptions,
            'activeProfileId' => $this->settingRepository->getValue($this->getSite(), GoogleAnalyticsService::PROFILE_ID_SETTINGS_KEY)
        ];
    }
}