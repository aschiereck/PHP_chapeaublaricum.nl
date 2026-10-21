<?php

namespace App\Service\GoogleAnalytics;

class GraphData
{
    /**
     * @var int
     */
    public $totalPageViews;
    /**
     * @var int
     */
    public $totalVisits;

    /**
     * @var DayStatistics[]
     */
    public $visitsPerDay;
    /**
     * @var string
     */
    public $trackingCode;
    /**
     * @var string
     */
    public $profileName;
}
