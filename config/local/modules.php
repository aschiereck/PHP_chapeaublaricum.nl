<?php
/**
 * List of enabled modules for this installation
 */
return [
    'active_modules' => [
        //Mandatory modules
        \App\Model\Module::DASHBOARD,
        \App\Model\Module::PAGE,
        \App\Model\Module::TEXT_BLOCK,
        \App\Model\Module::MENU,
        \App\Model\Module::USER,
        \App\Model\Module::SITE,

        //Optional modules
        \App\Model\Module::ARTICLE,
        \App\Model\Module::PHOTO_SLIDER,
        \App\Model\Module::PHOTO_ALBUM,
        \App\Model\Module::FORM,
        \App\Model\Module::GOOGLE_ANALYTICS,
        \App\Model\Module::FACEBOOK,
        // \App\Model\Module::TWITTER,
        // \App\Model\Module::TEAM,
        // \App\Model\Module::CAROUSEL,
        \App\Model\Module::PROJECT,
        // \App\Model\Module::CALENDAR,
    ]
];