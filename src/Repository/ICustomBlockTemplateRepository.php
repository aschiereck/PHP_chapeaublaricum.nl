<?php
namespace App\Repository;

use App\Model\CustomBlockTemplate;

interface ICustomBlockTemplateRepository
{
    /**
     * @return CustomBlockTemplate[]
     */
    public function getTemplates();

    /**
     * @param $templateName
     * @return CustomBlockTemplate
     */
    public function getByTemplateName($templateName);
}