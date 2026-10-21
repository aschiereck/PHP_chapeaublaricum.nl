<?php

use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Glob;

/**
 * Configuration files are loaded in a specific order. First ``global.php`` and afterwards ``local.php``. This way
 * local settings overwrite global settings.
 *
 * The configuration can be cached. This can be done by setting ``config_cache_enabled`` to ``true``.
 *
 * The configuration is stored in json so it is not depended on 3rd party libraries. Feel free to use something else
 * like Zend\Config\Writer to write PHP arrays.
 *
 * Obviously, if you use closures in your config you can't cache it.
 */

if(!defined('AREA'))
{
    throw new Exception('No Area defined');
}

//Set globals
define('ADMIN_SUFFIX', 'beheer');
define('MEDIA_FOLDER_PATH', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'httpdocs' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR);
define('MEDIA_FOLDER_URL',  '/media/');
define('MEDIA_CACHE_FOLDER_PATH', MEDIA_FOLDER_PATH .'cache'.DIRECTORY_SEPARATOR);
define('MEDIA_CACHE_FOLRDER_URL',  MEDIA_FOLDER_URL . 'cache/');
define("DATE_FORMAT", 'm-d-Y');
define("DATE_TIME_FORMAT", 'd-m-Y H:i:s');

$cachedConfigFile = 'data/cache/'.AREA.'.config.php';

$config = [];
if (is_file($cachedConfigFile))
{
    // Try to load the cached config
    $config = json_decode(file_get_contents($cachedConfigFile), true);
}
else
    {
    // Load configuration from autoload path
    foreach (Glob::glob('config/autoload/{{,*.}global,{,*.}'.AREA.',{,*.}local}.php', Glob::GLOB_BRACE) as $file)
    {
        $config = ArrayUtils::merge($config, include $file);
    }

    // Load configuration from local path
    foreach (Glob::glob('config/local/*.php', Glob::GLOB_BRACE) as $file)
    {
        $config = ArrayUtils::merge($config, include $file);
    }

    // Cache config if enabled
    if (isset($config['config_cache_enabled']) && $config['config_cache_enabled'] === true)
    {
        file_put_contents($cachedConfigFile, json_encode($config));
    }
}

// Return an ArrayObject so we can inject the config as a service in Aura.Di
// and still use array checks like ``is_array``.
return new \ArrayObject($config, \ArrayObject::ARRAY_AS_PROPS);
