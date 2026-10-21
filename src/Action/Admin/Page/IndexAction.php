<?php

namespace App\Action\Admin\Page;

use App\Action\Admin\AbstractAdminTemplateAction;

class IndexAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/page/';

    /**
     * @return array
     */
    public function execute()
    {
         return [
            'pages' => $this->getSite()->getPages(),
        ];
    }
}