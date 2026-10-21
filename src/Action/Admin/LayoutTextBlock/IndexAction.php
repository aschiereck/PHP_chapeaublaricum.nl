<?php

namespace App\Action\Admin\LayoutTextBlock;

use App\Action\Admin\AbstractAdminTemplateAction;

class IndexAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/layoutTextBlock';

    /**
     * @return array
     */
    public function execute()
    {
        return [
            'blocks' => $this->getSite()->getLayoutTextBlocks()
        ];
    }
}