<?php
 

$app->get('/station/add', function() use ($app){

    $_SESSION['stationAddmerker'] = true;

    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    $app->render('streamAddSelct/streamswitch.phtml', compact('license'));

    # Demoeinstellungen
    $demo = new \core\demo\demomod();
    $demo->CheckDemo($app->config('demo_mod'));

})->name('restricted');

$app->post('/station/add', function() use ($app){

    /*
     *              SC_Serv
     *          id          sc_Version
     *          1           1.9.8
     *          2           2
     *          5           1.9.9
     */
if(isset($_POST['addstreamswitch'])){

    // Session ID der Stream-Version speichern
   $_SESSION['streamID'] = $_POST['addstreamswitch'];  #sc_version ID





    $select_SCServ_FileName = DB::queryFirstRow("SELECt * FROM sc_version WHERE id=%s",$_POST['addstreamswitch']);
    $select_SCTrans_FileName = DB::queryFirstRow("SELECt * FROM sc_version WHERE id=%s",'4');

    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    $app->render('sc_stationconf/'.$select_SCServ_FileName['editTempName'].$_SESSION['group'].'.phtml', compact('license'));
    $app->render('sc_stationconf/'.$select_SCTrans_FileName['editTempName'].$_SESSION['group'].'.phtml');
    # Demoeinstellungen
    $demo = new \core\demo\demomod();
    $demo->CheckDemo($app->config('demo_mod'));
}

/*
 *      Stream-Server   Hinzufügen
 */
if (isset($_POST['addsrv']) AND $app->config('demo_mod') == false) {

    # $_SESSION['streamID'] ID Der sc_verion version

    # Laden der Konfiguration
    $config = DB::queryFirstRow("SELECT doc_root FROM config WHERE id=%s", '1');
    $DocRoot = $config['doc_root'];     # Auslesen von doc_root

    $FolderDir = $DocRoot . "/userconf/" . $_POST['sc_serv']['PortBase'];    # Folder Pfad anlegen


    # Ordner mit Port als Bezeichnung erstellen
    if (is_dir($FolderDir)) {
        function deleteDirectory($dir) {
            if (!file_exists($dir)) return true;
            if (!is_dir($dir)) return unlink($dir);
            foreach (scandir($dir) as $item) {
                if ($item == '.' || $item == '..') continue;
                if (!deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) return false;
            }
            return rmdir($dir);
        }
        deleteDirectory($FolderDir);
        mkdir($FolderDir, 0700);
    } else {
        mkdir($FolderDir, 0700);
    }

    /*
     *      Eintragen der Daten in die DB
     */
    DB::insert('sc_serv_conf',  $_POST['sc_serv']  );
    $sc_serv_id = DB::insertId();

    $sc_Trans_value = $_POST['sc_trans'];

    $sc_Trans_value['serverport'] = $_POST['sc_serv']['PortBase'];
    $sc_Trans_value['password'] = $_POST['sc_serv']['Password'];


    DB::insert('sc_trans_conf',  $sc_Trans_value  );
    $sc_trans_id = DB::insertId();



    DB::insert('sc_rel', array(
        'accounts_id' => $_POST['usr_id'],
        'sc_serv_conf_id' => $sc_serv_id,
        'sc_serv_version_id' => $_SESSION['streamID'],
        'stream_userName' => 'Dein neuer Stream',
        'sc_trans_id' => $sc_trans_id,
        'sc_trans_version_id' => $_POST['sc_trans_version']
    ));


    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    $app->render('station/adminshowlist.phtml', compact('license'));
}

/*
 *      Stream-Server bearbeiten
 */
if(isset($_POST['admeditServ'])){

    #echo '<pre>';
    #echo print_r($_POST);

    DB::update('sc_serv_conf', array(
        'MaxUser' => $_POST['sc_serv']['MaxUser'],
        'Password' => $_POST['sc_serv']['Password'],
        'AdminPassword'   => $_POST['sc_serv']['AdminPassword'],
        'ShowLastSongs'     => $_POST['sc_serv']['ShowLastSongs']
    ), "id=%s", $_SESSION['serv_id']);

    DB::update('sc_trans_conf', array(
        'encoder_1'       => $_POST['sc_trans']['encoder_1'],
        'encoder_2'       => $_POST['sc_trans']['encoder_2'],
        'bitrate_1'       => $_POST['sc_trans']['bitrate_1'],
        'bitrate_2'       => $_POST['sc_trans']['bitrate_2'],
        #'adminport' => $_POST['sc_trans']['adminport'], # Port Änderung wenn Stream bearbeitet wird.
        'channels' => $_POST['sc_trans']['channels'],
        'adminpassword'        => $_POST['sc_serv']['AdminPassword'],
        'samplerate'     => $_POST['sc_trans']['samplerate'],
        'djpassword'         => $_POST['sc_trans']['djpassword']
    ), "id=%s",  $_SESSION['trans_id']);

    unset($_SESSION['serv_id']);
    unset($_SESSION['trans_id']);

    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    $app->render('station/adminshowlist.phtml', compact('license'));

    $sp_growl = new core\sp_special\growl();


    $sp_growl->writeGrowl('warning',_('Neustart ist erforderlich!'),'');
    # Demoeinstellungen
    $demo = new \core\demo\demomod();
    $demo->CheckDemo($app->config('demo_mod'));
}




})->name('restricted');