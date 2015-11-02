<?php
/**
 * Created by David Schomburg (DashTec - Services)
 *      www.dashtec.de
 *
 *  S:P (StreamersPanel)
 *  Support: http://board.streamerspanel.de
 *
 *  v 4.0.0
 *
 *  Kundennummer:   @KDNUM@
 *  Lizenznummer:   @RECHNR@
 *  Lizenz: http://login.streamerspanel.de/user/terms
 */


/*
 *      Select witch SC_edit to load
 */
$app->get('/stationaddeditcontrol/selectStream', function() use ($app){
    unset($_SESSION['stationAddmerker']);
    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    $app->render('station/adminshowlist.phtml', compact('license'));

    # Demoeinstellungen
    $demo = new \core\demo\demomod();
    $demo->CheckDemo($app->config('demo_mod'));
})->name('restricted');



# Passende Stationconf laden
$app->post('/stationaddeditcontrol/selectStream', function() use ($app){

    $selectTempName = DB::queryFirstRow("SELECT * FROM sc_rel WHERE id=%s", $_POST['streamtoEdit']);



    # Bestimmung der zuladenden View
    $select_SCServ_FileName = DB::queryFirstRow("SELECT * FROM sc_version WHERE id=%s",$selectTempName['sc_serv_version_id']);
    $select_SCTrans_FileName = DB::queryFirstRow("SELECT * FROM sc_version WHERE id=%s",$selectTempName['sc_trans_version_id']);


    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    # Auslesen der Konfiguration
    $sc_trans = DB::queryFirstRow("SELECT * FROM sc_trans_conf WHERE id=%s", $selectTempName['sc_trans_id']);
    $sc_server = DB::queryFirstRow("SELECT * FROM sc_serv_conf WHERE id=%s",$selectTempName['sc_serv_conf_id']);

    $_SESSION['trans_id'] = $selectTempName['sc_trans_id'];
    $_SESSION['serv_id'] = $selectTempName['sc_serv_conf_id'];
    $_SESSION['streamID'] = $selectTempName['id'];

    $app->render('sc_stationconf/'.$select_SCServ_FileName['editTempName'].$_SESSION['group'].'.phtml', compact('sc_server', 'sc_trans', 'selectTempName'));
    $app->render('sc_stationconf/'.$select_SCTrans_FileName['editTempName'].$_SESSION['group'].'.phtml', compact('sc_server' , 'sc_trans', 'selectTempName'));
    # Demoeinstellungen
    $demo = new \core\demo\demomod();
    $demo->CheckDemo($app->config('demo_mod'));

})->name('allit');


# Passende Stationconf laden
$app->post('/station/useredit', function() use ($app){

    DB::update('sc_rel', array(
        'stream_userName'      => $_POST['sc_rel']['stream_userName']
    ), "id=%s", $_SESSION['streamID']);

    DB::update('sc_serv_conf', array(
        'Password'      => $_POST['sc_serv']['Password'],
        'AdminPassword' => $_POST['sc_serv']['AdminPassword'],
        'ShowLastSongs' => $_POST['sc_serv']['ShowLastSongs'],
        'TitleFormat'   => $_POST['sc_serv']['TitleFormat'],
        'URLFormat'     => $_POST['sc_serv']['TitleFormat']
        ), "id=%s", $_SESSION['serv_id']);

    DB::update('sc_trans_conf', array(
        'encoder_1'       => $_POST['sc_trans']['encoder_1'],
        'encoder_2'       => $_POST['sc_trans']['encoder_2'],
        'unlockkeyname' => $_POST['sc_trans']['unlockkeyname'],
        'unlockkeycode' => $_POST['sc_trans']['unlockkeycode'],
        'public'        => $_POST['PublicServer'],
       # 'adminuser'     => $_POST['sc_trans']['adminuser'],
        'adminpassword' => $_POST['sc_serv']['AdminPassword'],
       # 'djpassword'    => $_POST['sc_trans']['djpassword'],
        'streamurl'     => $_POST['sc_trans']['streamurl'],
        'genre'         => $_POST['sc_trans']['genre'],
        'shuffle'       => $_POST['sc_trans']['shuffle'],
        'aim'           => $_POST['sc_trans']['aim'],
        'icq'           => $_POST['sc_trans']['icq'],
        'irc'           => $_POST['sc_trans']['irc']
    ), "id=%s",  $_SESSION['trans_id']);


    $growl = new \core\sp_special\growl();
    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    $app->render('station/usershowstreams.phtml', compact('license'));

    if($_POST['sc_trans']['encoder_1'] == 'mp3' OR $_POST['sc_trans']['encoder_2'] == 'mp3'){
        if( empty($_POST['sc_trans']['unlockkeyname']) OR empty($_POST['sc_trans']['unlockkeycode'])){
            $growl->writeGrowl('warning',_('Stream kann NICHT gestartet werden'),_('Bei der Auswahl MP3 muss ein Unlockkeycode und Unlockkeyname angegeben werden'));
        }
    }




})->name('user');


