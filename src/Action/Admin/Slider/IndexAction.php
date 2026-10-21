<?php

namespace App\Action\Admin\Slider;

use App\Action\Admin\AbstractAdminTemplateAction;

class IndexAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/slider';

    /**
     * @return array
     */
    public function execute()
    {
         return [
            'sliders' => $this->getSite()->getSliders()
        ];
    }
}