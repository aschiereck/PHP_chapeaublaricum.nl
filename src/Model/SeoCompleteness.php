<?php

namespace App\Model;

class SeoCompleteness
{
    const BAD = 1;  //No focuswords used
    const OK = 2; //Focuswords used in most fields
    const GOOD = 3; //Focuswords used in all fields
}