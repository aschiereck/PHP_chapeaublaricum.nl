<?php

namespace App\Action\Admin\Article;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Repository\IArticleRepository;
use App\Repository\ISiteRepository;
use App\Repository\ISiteSettingRepository;

class IndexAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/article/';
    const LAST_CRON_JOB_RUN_SETTING = 'last-cron-job-run';

    /**
     * @var IArticleRepository
     */
    private $articleRepository;
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
     * @param IArticleRepository $articleRepository
     * @param ISiteSettingRepository $settingRepository
     * @param ISiteRepository $siteRepository
     */
    public function __construct(IArticleRepository $articleRepository, ISiteSettingRepository $settingRepository, ISiteRepository $siteRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->settingRepository = $settingRepository;
        $this->siteRepository = $siteRepository;
    }

    /**
     * @return array
     */
    public function execute()
    {
        $pageNr = $this->getQueryParameter('page', 1);
        $paginator = $this->articleRepository->getPaginator($this->getSite(), $pageNr, false);

        return [
            'lastCronJobRun' => $this->settingRepository->getValue($this->siteRepository->findFirstSite(), self::LAST_CRON_JOB_RUN_SETTING),
            'articles' => $paginator->getResults(),
            'paginator' => $paginator
        ];
    }
}