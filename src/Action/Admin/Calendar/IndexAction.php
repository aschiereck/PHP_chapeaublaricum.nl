<?php

namespace App\Action\Admin\Calendar;

use App\Action\Admin\AbstractAdminTemplateAction;

class IndexAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/calendar';

    /**
     * @return array
     */
    public function execute()
    {
        return [
            'calendars' => $this->getSite()->getCalendars()
        ];
    }
}