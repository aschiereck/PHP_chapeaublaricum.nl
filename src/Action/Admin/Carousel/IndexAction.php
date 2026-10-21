<?php

namespace App\Action\Admin\Carousel;

use App\Action\Admin\AbstractAdminTemplateAction;

class IndexAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/carousel';

    /**
     * @return array
     */
    public function execute()
    {
         return [
            'carousels' => $this->getSite()->getCarousel()
        ];
    }
}