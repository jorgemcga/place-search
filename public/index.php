<?php

session_start();
require_once '../config/config.php';

$argv = isset($argv) ? $argv : array();

/*
 * Whoops
 */
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

/*
 * Edily Base
 */
$router = new Edily\Base\Router($argv);
$loader = new Edily\Base\Loader();
$ret = $loader->run();

$view = new Edily\Base\View();
$view->obj = $loader->obj; //Passa objeto controller instanciado;
$view->render($ret);
