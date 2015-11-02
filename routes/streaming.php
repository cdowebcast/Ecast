<?php
$app->get('/sp/license', function() use ($app){
    $license = DB::query("SELECT * FROM config");
$app->render('streaming/licenseshow.phtml', compact('license'));
})->name('license');


$app->get('/sp/serverconf', function() use ($app){
    $app->render('streaming/serverconf.phtml', compact('supportTickets'));
})->name('serverconf');


