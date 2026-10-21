<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 4-1-2017
 * Time: 12:28
 */
namespace App\Service\Seo;

use App\Model\Article;

interface IArticleCompletenessChecker
{
    /**
     * @param Article $article
     * @return int
     */
    public function calculateCompletness(Article $article);
}