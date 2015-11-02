<?php
session_start();
if(!is_file("conf.php") ){
    // TODO Realease activate
    die(_('Bitte das Panel Installieren'));
}



require_once __DIR__ . '/core/SplClassLoader.php';
require_once __DIR__ . '/core/DB.php';
include_once __DIR__ . '/core/request/Requests.php';

require 'core/Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

(new SplClassLoader('Slim', __DIR__ . '/core'))->register();
(new SplClassLoader('SP', __DIR__ . '/core'))->register();
(new SplClassLoader('core', __DIR__ ))->register();



require_once __DIR__ . '/conf.php';


$app->add(new \SP\Middleware\AuthorizationMiddleware());
$app->add(new \SP\Middleware\AuthenticationMiddleware());





# Active Routes
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/routes/authentication.php';
require_once __DIR__ . '/routes/support.php';
require_once __DIR__ . '/routes/sp.php';
require_once __DIR__ . '/routes/news.php';
require_once __DIR__ . '/routes/station.php';
require_once __DIR__ . '/routes/filemanager.php';
require_once __DIR__ . '/routes/server.php';
require_once __DIR__ . '/routes/benutzerverwaltung.php';
require_once __DIR__ . '/routes/dj.php';
require_once __DIR__ . '/routes/streamAddSel.php';
require_once __DIR__ . '/routes/djuserfunction.php';
require_once __DIR__ . '/routes/stream_edit_control.php';



# GETTEXT Cofig
if(empty($_SESSION['local'])){
    $local = DB::queryFirstRow("SELECT default_local FROM config WHERE id=%s",'1');
    $_SESSION['local'] = $local['default_local'];
}
$local = $_SESSION['local'];  # de_DE , en_US, es_MX
putenv("LC_ALL=".$local.".utf8");
setlocale(LC_ALL, $local.".utf8");
bindtextdomain($local, __DIR__.'/locale');
textdomain($local);


if(!defined('SP_AREA')){
$app->run();
}
