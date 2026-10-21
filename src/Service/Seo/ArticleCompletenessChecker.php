<?php

namespace App\Service\Seo;

use App\Model\Article;

class ArticleCompletenessChecker extends AbstractCompletenessChecker implements IArticleCompletenessChecker
{
    /**
     * @param Article $article
     * @return int
     */
    public function calculateCompletness(Article $article)
    {
        $searchStrings = [
            $article->getName(),
            $article->getHeadTitle(),
            $article->getMetaDescription(),
            $article->getContent()
        ];

        return $this->calculateSeoCompletness($article->getFocusWordsList(), $searchStrings);
    }
}