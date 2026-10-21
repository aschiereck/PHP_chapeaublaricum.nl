<?php

namespace App\Service\Seo;

use App\Model\SeoCompleteness;

class AbstractCompletenessChecker
{
    /**
     * @param $focusWords
     * @param $stringsToSearch
     * @return int
     */
    protected function calculateSeoCompletness($focusWords, $stringsToSearch)
    {
        $scores = [];

        foreach($stringsToSearch as $string)
        {
            $hits = $this->countFocusWordsInString($focusWords, $string);
            if(trim($string) == '')
            {
                $scores[] = SeoCompleteness::BAD;
            }
            else if($hits == 0)
            {
                $scores[] = SeoCompleteness::OK;
            }
            else
            {
                $scores[] = SeoCompleteness::GOOD;
            }
        }

        return min($scores);
    }


    /**
     * @param $focusWords
     * @param $stringToSearch
     * @return $this
     */
    protected function countFocusWordsInString($focusWords, $stringToSearch)
    {
        $hits = 0;
        foreach($focusWords as $word)
        {
            if(trim($word) != '')
            {
                $hits += substr_count(strtolower(strip_tags($stringToSearch)), strtolower(trim($word)));
            }
        }
        return $hits;
    }
}