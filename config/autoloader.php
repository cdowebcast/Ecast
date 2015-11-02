<?php
/*** nullify any existing autoloads ***/
spl_autoload_register(null, false);

/*** specify extensions that may be loaded ***/
spl_autoload_extensions('scserv.php, .controller.php, .lib.php');

/*** controller Loader ***/
function classLoader($class)
{
$filename = strtolower($class) . '.class.php';
$file ='config/' . $filename;
if (!file_exists($file))
{
    return false;
}
include $file;
}

function ColreLib($class){

// Die Backslashes des Namespaces zu Slashes umwandeln
    $fileName = str_replace("\\", "/", $class).".lib.php";

    if (file_exists($fileName)) {
        require_once $fileName;
    }
}

function libClassLoader($class){

// Die Backslashes des Namespaces zu Slashes umwandeln
    $fileName = str_replace("\\", "/", $class).".php";

    if (file_exists($fileName)) {
        require_once $fileName;
    }
}


/*** register the loader functions ***/
spl_autoload_register('classLoader');
spl_autoload_register('libClassLoader');
spl_autoload_register('ColreLib');
// Späteres automatisiertes Laden der datein
include_once 'config.php';

/*
 *      Multiple CSS Import
 */
# DFW Logik
$DTF = new dfwconf();
date_default_timezone_set('America/Bogota');

// Alle modconf.php erkennen und laden


# GETTET
$locale = Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);

$lang = $locale;
setlocale( 'LC_ALL', 'C.UTF-8' );
setlocale(LC_ALL, $lang);
bindtextdomain( $lang, "./locale" );
bind_textdomain_codeset( $lang, "UTF-8" );
textdomain( $lang );





