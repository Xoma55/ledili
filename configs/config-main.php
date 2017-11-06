<?php
/**
 * Created by PhpStorm.
 * User: ag
 * Date: 14.12.16
 * Time: 11:21
 */
//error_reporting(E_ALL & ~E_DEPRECATED);
error_reporting(0);

define('VERSION_CSS_JS', '1.2');

define('HOST', $_SERVER['HTTP_HOST']);
define('ROOT', $_SERVER['DOCUMENT_ROOT']);

// production version
define('PROD', 'ledili.ua');

// dev3 server
define('DEV3', 'ledili.dev3.alcora.eu');

// for local and dev repositories
define('IS_DEV', in_array(HOST, array(
    DEV3,
    'ledili.dev',
)));

define('PROTOCOL', IS_DEV ? 'http' : 'http');

define('FULL_HOST', PROTOCOL . '://' . HOST);

define('LOG_NAME_1C', 'exchange.log');


if(stripos($_SERVER["REQUEST_URI"], '/admin') === 0 || defined('LOAD_ADMIN_CONFIG'))
{
    defined('LOAD_ADMIN_CONFIG') or define('LOAD_ADMIN_CONFIG', true);

    require_once __DIR__.'/config-admin.php';
}
else
    require_once __DIR__.'/config.php';

require_once __DIR__.'/config-db.php';