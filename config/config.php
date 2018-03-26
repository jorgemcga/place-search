<?php
//ini_set("error_reporting", "E_ALL & E_ERROR");
//ini_set("display_erros", "on");

ini_set("session.use_only_cookies", 'on');
date_default_timezone_set('America/Sao_Paulo');
define('GL_ROOT', getcwd() . '/..');

//define('BASE_URL', 'http://jorge.jeitodigital.com.br/bhmaps/');
define('BASE_URL', 'http://localhost:8080/maps/');
define('APP_NAME', 'Procurar Lugares');
define('PASSWORD_TOKEN', 'lk@129ng%)-çs4&hj1l&$');
define('APP_TOKEN', 'lk@129ng%)-çs4&hj1l&$');

/*
 * DATABASE CONFIG
 */
define('DB_DRIVER', 'mysqli');
define('DB_HOST', 'localhost');

/* Ambiente teste */
//define('DB_NAME', 'jorgejei_bhmaps');
//define('DB_USERNAME', 'jorgejei_admin');
//define('DB_PASSWORD', '0L06X$24pw7!');

/* Ambiente produção */
// define('DB_NAME', 'test');
// define('DB_USERNAME', 'sitermbh');
// define('DB_PASSWORD', '555CA578859729C9F1616DFD197379F445372D4E');

/* Ambiente dev */
define('DB_NAME', 'bhmaps');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');

define('DB_CHARSET', 'utf8');
define('DB_COLLATION', 'utf8_unicode_ci');
define('DB_PREFIX', '');

/*
 * E-MAIL CONFIG
 */
define('EMAIL_HOST', 'smtp.gmail.com');
define('EMAIL_PORT', '587');
define('EMAIL_USERNAME', 'bhpartmap@gmail.com');
define('EMAIL_PASSWORD', 'bhpartmap123');
define('EMAIL_SMTPSECURE', 'TLS');

/*
 * DANGER ZONE !!!
 */
require_once __DIR__ . '/../vendor/autoload.php';

$con = new Edily\Base\Config();
$con->setGlobalRoot(GL_ROOT);
$con->write();

require_once __DIR__ . '/../vendor/edily/base/config.php';
require_once FW_ROOT . '/boot.php';
