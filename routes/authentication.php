<?php
use core\password\password;


// zeigt die loginseite an
$app->get('/login', function () use ($app) {

    if(is_dir("./install") ){
        // TODO Realease activate
       # die(_('Bitte den "install" Ordner löschen'));
    }

    //$newsListing = DB::query("SELECT * FROM news WHERE is_aktiv=%s  AND login_news=%s", '1', '1');
    $app->render('authentication/login.phtml', compact('newsListing'));
})->name('login');

// fuehrt den login des benutzers durch
$app->post('/login', function () use ($app) {
    $username = $app->request()->post('username');
    $password = $app->request()->post('password');

    if (!$username || !$password) {
        $app->redirect('/login', 303);
    }

    $account = DB::queryFirstRow("SELECT * FROM accounts WHERE kundennummer=%s", $username);
    if (!$account) {
        $app->flash('error', _('Keinen Account gefunden'));
        $app->redirect('/', 303);
    }

    $passwordIsCorrect = password::verifyPassword($password, $account['password']);
    if (!$passwordIsCorrect || !$account['is_aktiv']) {
        $app->flash('error', _('Benutzer nicht gefunden!'));
        $app->redirect('/login', 303);
    }

    $config = DB::queryFirstRow("SELECT wartungsmodus FROM config WHERE id=%s", '1');
    if ($config['wartungsmodus'] == 1 AND  $account['usr_grp'] != 'adm'){
        $growl = new \core\sp_special\growl();
        $app->flash('error', _('Keinen Account gefunden'));
        $app->redirect('/', 303);
    }



    # Session setzen
    if(empty($account['local'])){
        $local = DB::queryFirstRow("SELECT default_local FROM config WHERE id=%s",'1');
        $_SESSION['local'] = $local['default_local'];
    }else{
        $_SESSION['local'] = $account['local'];
    }
    $_SESSION['account_id'] = (int)$account['id'];
    $_SESSION['group'] = $account['usr_grp'];
    $_SESSION['USERNAME'] = $account['vorname'].' '. $account['nachname'];







    $app->flash('success', _('Login erfolgreich'));
    $app->redirect('/');


})->name('doLogin');

// fuehrt den logout des benutzers durch
$app->get('/logout', function () use ($app) {
    $_SESSION = array();
    session_destroy();

    $app->flash('success', _('Abmelden erfolgreich'));
    $app->redirect('/login');
})->name('doLogout');

