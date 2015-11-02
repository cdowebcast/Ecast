<?php
date_default_timezone_set($app->config('php.timezone'));
error_reporting($app->config('php.error-reporting'));

#   Titel des Programms
$app->view()->set('site_title', 'Ecast');

