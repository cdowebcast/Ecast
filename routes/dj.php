<?php
 

$app->get('/dj/list', function () use ($app) {
    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    $app->render('dj/listdj.phtml');
})->name('usr');

$app->get('/dj/add', function () use ($app) {

    DB::query("SELECT * FROM dj_accounts WHERE dj_of_user_id=%s", $_SESSION['account_id']);
    $counter = DB::count();
    $max_dj = DB::queryFirstRow("SELECT * FROM accounts WHERE id=%s", $_SESSION['account_id']);

    if($max_dj['dj_limit_count'] > $counter OR $max_dj['dj_limit_count'] == NULL ){
        $SPMenu = new SP\Menu\MenuInclusion();
        $SPMenu->MenuInclude($app);
        $app->render('dj/adddj.phtml');
    }else{
        $SPMenu = new SP\Menu\MenuInclusion();
        $SPMenu->MenuInclude($app);
        $app->render('dj/listdj.phtml');
        $growl = new core\sp_special\growl();
        $growl->writeGrowl('warning','Aktion ist nicht möglich!','Sie haben die maximale Anzahl an DJ-Benutzer erreicht!');
    }
})->name('usr');

$app->post('/dj/list', function () use ($app) {

    if (isset($_POST['editDjList']) AND !isset($_POST['entryDjtoStream'])) {
        $changer = explode(".", $_POST['editDjList']);
        $_SESSION['DJID'] = $changer[1];


        if($changer[0] == 'delDj'){
            // TODO: Absichern!!
            DB::delete('dj_accounts', "dj_accounts_id=%s", $_SESSION['DJID']);
            DB::delete('accounts', "id=%s", $_SESSION['DJID']);
            $SPMenu = new SP\Menu\MenuInclusion();
            $SPMenu->MenuInclude($app);
            $growl = new core\sp_special\growl();
            $app->render('dj/listdj.phtml');
            $growl->writeGrowl('success','DJ - wurde zurgelöscht!','Zur Übernahme muss der Server neu gestartet werden');

        }

        # Lädt das Auswahlfenster um einen Benutzer einer Station hinzuzufügen
        if($changer[0] == 'djtoStation'){
            $SPMenu = new SP\Menu\MenuInclusion();
            $SPMenu->MenuInclude($app);
            $app->render('dj/listdj.phtml');
            $app->render('dj/stationselect.phtml');
        }

        # DJ bearbeiten
        if($changer[0] == 'editDj'){
            // TODO: Absichern!!!
            $SPMenu = new SP\Menu\MenuInclusion();
            $SPMenu->MenuInclude($app);
            $_SESSION['editDjId'] = $changer['1'];
            $DjData = DB::queryFirstRow("SELECt * FROM accounts WHERE id=%s", $changer['1']);
            $app->render('dj/editdj.phtml', compact('DjData'));
        }

        # DJ neues Passwort
        if($changer[0] == 'newPass'){
            $pass = new core\password\password();
            $password = $pass->generatePassword();
            $passwordcrypt = $pass->createPassword($password);
            DB::update('accounts', array(
                'password' => $passwordcrypt,
                'dj_clearpass' => $password
            ), "id=%s", $_SESSION['DJID']);
            $SPMenu = new SP\Menu\MenuInclusion();
            $SPMenu->MenuInclude($app);
            $growl = new core\sp_special\growl();
            $growl->writeGrowl('success','DJ - Passwort geändert!','Neuer Passwort: '. $password);
            $app->render('dj/listdj.phtml');
        }

        # DJ aktiv / inaktiv
        if($changer[0] == 'is_aktiv'){
            DB::update('accounts', array(
                'is_aktiv' => $changer['2']
            ), "id=%s", $changer['1']);

            $SPMenu = new SP\Menu\MenuInclusion();
            $SPMenu->MenuInclude($app);
            $growl = new core\sp_special\growl();
            $growl->writeGrowl('success','DJ - Änderung wurde übernommen!','');
            $app->render('dj/listdj.phtml');
        }

    }

# Auswahl Menü um DJ einen Stream zuzuordnen
    if(isset($_POST['entryDjtoStream'])){

        $isSetDjoToStream= DB::queryFirstRow("SELECT * FROM dj_accounts WHERE dj_of_sc_rel_id=%s AND dj_of_user_id=%s AND  dj_accounts_id=%s", $_POST['entryDjtoStream'], $_SESSION['account_id'], $_SESSION['DJID'] );

        if (isset($isSetDjoToStream['id'])){
            $SPMenu = new SP\Menu\MenuInclusion();
            $SPMenu->MenuInclude($app);
            $growl = new core\sp_special\growl();
            $growl->writeGrowl('warning',_('DJ nicht zum Stream hinzugefügt'),_('Stream bereits vorhanden'));
            $app->render('dj/listdj.phtml');
        }else{
            DB::insert('dj_accounts', array(
                'dj_of_sc_rel_id' => $_POST['entryDjtoStream'],
                'dj_of_user_id' => $_SESSION['account_id'],
                'dj_accounts_id' => $_SESSION['DJID'],
            ));


            $SPMenu = new SP\Menu\MenuInclusion();
            $SPMenu->MenuInclude($app);
            $growl = new core\sp_special\growl();
            $growl->writeGrowl('info',_('DJ zum Stream hinzugefügt'),_('Stream benötigt einen Neustart!'));
            $app->render('dj/listdj.phtml');
        }

    }


    if (isset($_POST['entryDjUser'])){
        DB::insert('accounts', array(
            'kundennummer' => $_SESSION['account_id'].'-'.$_POST['dj_name'],
            'vorname' => $_POST['vorname'],
            'nachname' => $_POST['nachname'],
            'street' => $_POST['street'],
            'hausnummer' => $_POST['hausnummer'],
            'ort' => $_POST['ort'],
            'plz' => $_POST['plz'],
            'telefon' => $_POST['telefon'],
            'handy' => $_POST['handy'],
            'mail' => $_POST['mail'],
            'usr_grp' => 'dj',
            'is_aktiv' => '1',
            'dj_name' => $_POST['dj_name'],
            'dj_of_user_id' => $_SESSION['account_id']
        ));


        $SPMenu = new SP\Menu\MenuInclusion();
        $SPMenu->MenuInclude($app);
        $growl = new core\sp_special\growl();
        $growl->writeGrowl('info',_('DJ zum Account hinzugefügt '), _('Der Streamserver benötigt einen Neustart!'));
        $app->render('dj/listdj.phtml');
    }
})->name('usr');

# DJ bearbeiten
$app->post('/dj/edit', function () use ($app) {
# DJ bearbeiten
if (isset($_POST['editDjUser'])){

    DB::update('accounts', array(
        'dj_name' => $_POST['dj_name'],
        'kundennummer' => $_SESSION['account_id'].'-'.$_POST['dj_name'],
        'vorname' => $_POST['vorname'],
        'nachname' => $_POST['nachname'],
        'street' => $_POST['street'],
        'hausnummer' => $_POST['hausnummer'],
        'ort' => $_POST['ort'],
        'plz' => $_POST['plz'],
        'telefon' => $_POST['telefon'],
        'handy' => $_POST['handy'],
        'mail' => $_POST['mail'],
        'usr_grp' => 'dj',
        'skype' => $_POST['skype'],
        'is_aktiv' => '1'
    ), "id=%s",  $_SESSION['DJID']);


    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    $growl = new core\sp_special\growl();
    $growl->writeGrowl('info',_('DJ bearbeitet'),'');
    $app->render('dj/listdj.phtml');
}
})->name('usr');



$app->post('/dj/delStationFormList', function () use ($app) {

    // TODO SIcherung ist user = Inhaber des DJ
    DB::delete('dj_accounts', "id=%s", $_POST['delStream']);

    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    $growl = new core\sp_special\growl();
    $growl->writeGrowl('info',_('DJ gelöscht'),'');
    $app->render('dj/listdj.phtml');
})->name('usr');


$app->post('/dj/selDjPrio', function () use ($app) {

    $_SESSION['djprioSet'] = $_POST['djprioSet'];

    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    $app->render('dj/djprioselct.phtml');
    $app->render('dj/listdj.phtml');
})->name('usr');


$app->post('/dj/prioset', function () use ($app) {

    DB::update('dj_accounts', array(
        'dj_prio' => $_POST['djprioset']
    ), "id=%s", $_SESSION['djprioSet']);

    unset($_SESSION['djprioSet']);

    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    $growl = new core\sp_special\growl();
    $app->render('dj/listdj.phtml');

})->name('usr');




