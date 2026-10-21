<?php

namespace App\Action\Admin\Team;

use App\Action\Admin\AbstractAdminTemplateAction;

class IndexAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/team';

    /**
     * @return array
     */
    public function execute()
    {
        return [
            'members' => $this->getSite()->getTeamMembers()
        ];
    }
}