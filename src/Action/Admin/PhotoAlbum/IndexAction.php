<?php

namespace App\Action\Admin\PhotoAlbum;

use App\Action\Admin\AbstractAdminTemplateAction;

class IndexAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/photoAlbums/';

    /**
     * @return array
     */
    public function execute()
    {
        return [
            'albums' => $this->getSite()->getPhotoAlbums()
        ];
    }
}