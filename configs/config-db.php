<?php
/**
 * Created by PhpStorm.
 * User: ag
 * Date: 14.12.16
 * Time: 11:19
 */

// DB

define('DB_PORT', '3306');
define('DB_PREFIX', '');
define('DB_DRIVER', defined('LOAD_ADMIN_CONFIG') ? 'mysqli' : 'ExtMySQLi');

if(HOST == 'ledili.dev')
{
    define('DB_HOSTNAME', '10.0.0.7');
    define('DB_USERNAME', 'admin');
    define('DB_PASSWORD', 'alcora.eu');
    define('DB_DATABASE', 'godjatsk_ledili');
}
elseif(IS_DEV)
{
    define('DB_HOSTNAME', 'localhost');
    define('DB_USERNAME', 'admin2');
    define('DB_PASSWORD', 'alcora.eu');
    define('DB_DATABASE', 'godjatsk_ledili');
}
else
{
    define('DB_HOSTNAME', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', 'qazqaz');
    define('DB_DATABASE', 'ledilico_mua');
}

