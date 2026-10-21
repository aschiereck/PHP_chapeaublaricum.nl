<?php

namespace App\Action\Admin\Form;

use App\Action\Admin\AbstractAdminTemplateAction;

class IndexAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/form/';

    /**
     * @return array
     */
    public function execute()
    {
         return [
            'forms' => $this->getSite()->getForms()
        ];
    }
}