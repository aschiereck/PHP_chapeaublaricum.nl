<?php

namespace App\Action\Admin\Facebook;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Service\Facebook\IFacebookService;

class IndexAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/facebook';
    /**
     * @var IFacebookService
     */
    private $facebookService;

    /**
     * IndexAction constructor.
     * @param IFacebookService $facebookService
     */
    public function __construct(IFacebookService $facebookService)
    {
        $this->facebookService = $facebookService;
    }

    /**
     * @return array
     */
    public function execute()
    {
		$pageList = [];
		$publishId = null;
        $profile = $this->facebookService->getUserProfile($this->getSite());
		
		if($profile)
		{
			$pages = $this->facebookService->getPages($this->getSite());

			$pageList = [
				$profile->id => $profile->name
			];
			foreach($pages as $page)
			{
				$pageList[$page->id] = $page->name;
			}

			if($this->isPostRequest())
			{
				$publishId = $this->getPostParameter('publishId');
				$this->facebookService->setPublishId($this->getSite(), $publishId);
				$this->getFlash()->success('Uw facebook instellingen zijn opgeslagen');
			}
			
			$publishId = $this->facebookService->getPublishId($this->getSite());
		}

        return [
            'profile' => $profile,
            'pages' => $pageList,
            'publishId' => $publishId
        ];
    }
}