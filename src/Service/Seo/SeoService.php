<?php

namespace App\Service\Seo;

use App\Model\Site;

class SeoService implements ISeoService
{
    /**
     * @param Site $site
     * @return float|int
     */
    public function calculateGlobalSeoScore(Site $site)
    {
        $seoScore = [];

        foreach($site->getPages() as $page)
        {
            $seoScore[] = $page->getSeoCompleteness();
        }
        foreach($site->getArticles() as $article)
        {
            $seoScore[] = $article->getSeoCompleteness();
        }
        foreach($site->getProjects() as $project)
        {
            $seoScore[] = $project->getSeoCompleteness();
        }

        $total = array_sum($seoScore);
        $numberOfElements = count($seoScore);
        $score = $numberOfElements ? (($total / $numberOfElements) / 3 * 5) : 0;

        return $score;
    }

    /**
     * @param Site $site
     * @param int $limit
     * @return \string[]
     */
    public function getTopFocusWords(Site $site, $limit = 6)
    {
        $focusWords = [];

        foreach($site->getPages() as $page)
        {
            $focusWords = $this->countFocusWords($focusWords, $page->getFocusWordsList());
        }
        foreach($site->getArticles() as $article)
        {
            $focusWords = $this->countFocusWords($focusWords, $article->getFocusWordsList());
        }
        foreach($site->getProjects() as $project)
        {
            $focusWords = $this->countFocusWords($focusWords, $project->getFocusWordsList());
        }

        arsort($focusWords);

        if(count($focusWords) > $limit )
        {
            $focusWords = array_slice($focusWords, 0, $limit);
        }

        return $focusWords;
    }

    /**
     * @param string[] $focusWords
     * @param string[] $wordList
     * @return string[]
     */
    private function countFocusWords($focusWords, $wordList)
    {
        foreach($wordList as $word)
        {
            if (!isset($focusWords[$word]))
            {
                $focusWords[$word] = 0;
            }

            $focusWords[$word] = $focusWords[$word] + 1;
        }
        return $focusWords;
    }

}