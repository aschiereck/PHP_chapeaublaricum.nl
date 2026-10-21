<?php

namespace App\Action\Admin\Site;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Repository\ISiteRepository;

class IndexAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/site';

    /**
     * @var ISiteRepository
     */
    private $siteRepository;

    /**
     * @param ISiteRepository $siteRepository
     */
    public function __construct(ISiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }

    /**
     * @return array
     */
    public function execute()
    {
        return [
            'sites' => $this->siteRepository->findAll(),
        ];
    }
}