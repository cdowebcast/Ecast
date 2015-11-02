<?php
 


/*
 *
 *      Admin Routes
 *
 */
$app->get('/user/list', function () use ($app) {

    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    $Users = DB::query("SELECT * FROM accounts");
    $app->render('benutzerverwaltung/listuser.phtml', compact('Users'));

})->name('nn');

$app->get('/user/register', function () use ($app) {

    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    $app->render('benutzerverwaltung/adduser.phtml');

})->name('restricted');


$app->post('/user/list', function () use ($app) {
# Benutzer Aktiv/inaktiv

    if(isset($_POST['useredit'])){
        $changer = explode(".", $_POST['useredit']);

        if($changer['1'] == 'deluser'){
            $addUser = new core\usercontrol\user();
            $addUser->delUserToDb($changer['0']);
            $SPMenu = new SP\Menu\MenuInclusion();
            $SPMenu->MenuInclude($app);
            $Users = DB::query("SELECT * FROM accounts");
            $app->render('benutzerverwaltung/listuser.phtml', compact('Users'));
            $sp_growl = new core\sp_special\growl();
            $sp_growl->writeGrowl('success', _('Benutzer gelöscht'), _('Der Benutzer wurde erfolgreich gelöscht!'));
        }

        if($changer['1'] == 'edituser'){

            $SPMenu = new SP\Menu\MenuInclusion();
            $SPMenu->MenuInclude($app);
            $Users = DB::queryFirstRow("SELECT * FROM accounts WHERE id=%s", $changer['0']);
            $_SESSION['merker'] = $changer[0];
            $app->render('benutzerverwaltung/edituser.phtml', compact('Users'));
        }

        if($changer['1'] == 'changePass'){
        $pass = new core\password\password();
        $password = $pass->generatePassword();
        $passwordcrypt = $pass->createPassword($password);
        DB::update('accounts', array(
            'password' => $passwordcrypt
        ), "id=%s", $changer['0']);

        $SPMenu = new SP\Menu\MenuInclusion();
        $SPMenu->MenuInclude($app);
            $Users = DB::query("SELECT * FROM accounts");
            $app->render('benutzerverwaltung/listuser.phtml', compact('Users'));
        $growl = new core\sp_special\growl();
        $growl->writeGrowl('success','DJ - Passwort geändert!','Neuer Passwort: '. $password);
        }


        if($changer['1'] == 'djuserlimit'){
            $_SESSION['user_dj_limit_id'] = $changer['0'];
            $SPMenu = new SP\Menu\MenuInclusion();
            $SPMenu->MenuInclude($app);
            $Users = DB::query("SELECT * FROM accounts");
            $app->render('benutzerverwaltung/djlimitsel.phtml');
            $app->render('benutzerverwaltung/listuser.phtml', compact('Users'));
        }


    }else{
        $changer = explode(".", $_POST['is_aktiv']);
        DB::update('accounts', array(
            'is_aktiv' => $changer['1']
        ), "id=%s", $changer['0']);

        $SPMenu = new SP\Menu\MenuInclusion();
        $SPMenu->MenuInclude($app);
        $Users = DB::query("SELECT * FROM accounts");
        $app->render('benutzerverwaltung/listuser.phtml', compact('Users'));
        $sp_growl = new core\sp_special\growl();
        $sp_growl->writeGrowl('success', _('Benutzer wurde aktiviert'), '');
    }

})->name('restricted');

$app->post('/user/list', function () use ($app) {

# Benutzer Aktiv/inaktiv
    if (isset($_POST['is_aktiv'])) {
        $changer = explode(".", $_POST['is_aktiv']);
        DB::update('accounts', array(
            'is_aktiv' => $changer['1']
        ), "id=%s", $changer['0']);
    }


})->name('restricted');




$app->post('/user/edituser', function () use ($app) {

    if (isset($_POST['update']) AND
        !empty($_POST['vorname'])
        AND
        !empty($_POST['nachname'])
        AND
        !empty($_POST['mail'])
    ) {

        DB::update('accounts', array(
            'kundennummer' => $_POST['kundennummer'],
            'vorname' => $_POST['vorname'],
            'nachname' => $_POST['nachname'],
            'street' => $_POST['street'],
            'hausnummer' => $_POST['hausnummer'],
            'ort' => $_POST['ort'],
            'plz' => $_POST['plz'],
            'telefon' => $_POST['telefon'],
            'handy' => $_POST['handy'],
            'mail' => $_POST['mail'],
            'usr_grp' => $_POST['usr_grp'],
            'is_aktiv' => $_POST['is_aktiv'],
            'local' => $_POST['local']
        ), "id=%s", $_SESSION['merker']);


        if(isset($_POST['password'])){
        DB::update('accounts', array(
            'password' => $_POST['password']
        ), "id=%s", $_SESSION['merker']);
        }

        unset($_SESSION['merker']);

        $SPMenu = new SP\Menu\MenuInclusion();
        $SPMenu->MenuInclude($app);
        $Users = DB::query("SELECT * FROM accounts");
        $app->render('benutzerverwaltung/listuser.phtml', compact('Users'));
        $sp_growl = new core\sp_special\growl();
        $sp_growl->writeGrowl('success', 'Änderungen wurden übernommen', '');
    } else {
        $SPMenu = new SP\Menu\MenuInclusion();
        $SPMenu->MenuInclude($app);
        $Users = DB::query("SELECT * FROM accounts");
        $app->render('benutzerverwaltung/listuser.phtml', compact('Users'));
        $sp_growl = new core\sp_special\growl();
        $sp_growl->writeGrowl('error', _('Angaben nicht vollständig') ,  _('Name, Nachname und Mail werden benötigt') );
    }


})->name('restricted');



$app->post('/benutzerverwaltung/setdjlimit', function () use ($app) {

        DB::update('accounts', array(
            'dj_limit_count' => $_POST['djlimituser']
        ), "id=%s", $_SESSION['user_dj_limit_id']);

    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    $Users = DB::query("SELECT * FROM accounts");
    $app->render('benutzerverwaltung/listuser.phtml', compact('Users'));

})->name('restricted');




/*
 *
 *      User Routes
 *
 */


/*
 *
 *      POST Verarbeitung
 *
 */


/*
 *
 *      Add User
 *
 */
$app->post('/benutzerverwaltung/adduser', function () use ($app) {

    $addUser = new core\usercontrol\user();
    $formwork = new core\postget\postgetcoll();


    if (isset($_POST['registeruser']) AND
        !empty($_POST['vorname'])
        AND
        !empty($_POST['nachname'])
        AND
        !empty($_POST['password'])
        AND
        !empty($_POST['mail'])
    ) {
        $mywork[] = $formwork->collvars('POST');
        $addUser->addUserToDb($mywork, 'registeruser');
        $SPMenu = new SP\Menu\MenuInclusion();
        $SPMenu->MenuInclude($app);
        $Users = DB::query("SELECT * FROM accounts");
        $app->render('benutzerverwaltung/listuser.phtml', compact('Users'));
        $sp_growl = new core\sp_special\growl();
        $sp_growl->writeGrowl('success', _('Benutzer wurde angelegt'), '');

    } else {

        if (isset($_POST['registeruser']) and
            empty($_POST['vorname'])
            or
            empty($_POST['nachname'])
            or
            empty($_POST['password'])
            or
            empty($_POST['mail'])
        ) {


            $SPMenu = new SP\Menu\MenuInclusion();
            $SPMenu->MenuInclude($app);
            $app->render('benutzerverwaltung/adduser.phtml');
        }

    }


})->name('doLogin');

