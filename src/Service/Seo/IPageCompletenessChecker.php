<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 3-1-2017
 * Time: 18:03
 */
namespace App\Service\Seo;

use App\Model\Page;

interface IPageCompletenessChecker
{
    /**
     * @param Page $page
     * @return int
     */
    public function calculateCompletness(Page $page);
}