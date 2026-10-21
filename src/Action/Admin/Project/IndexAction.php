<?php

namespace App\Action\Admin\Project;

use App\Action\Admin\AbstractAdminTemplateAction;

class IndexAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/project';

    /**
     * @return array
     */
    public function execute()
    {
        return [
            'projects' => $this->getSite()->getProjects()
        ];
    }
}