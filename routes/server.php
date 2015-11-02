<?php

$app->get('/server/conf', function () use ($app) {
    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    $server = DB::queryFirstRow("SELECT * FROM config");

    $app->render('server/serverconf.phtml', compact('server'));

    # Demoeinstellungen
    $demo = new \core\demo\demomod();
    $demo->CheckDemo($app->config('demo_mod'));
})->name('not-restricted');

$app->get('/server/license', function () use ($app) {
    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    $app->render('server/licenseshow.phtml');
})->name('not-restricted');



$app->post('/server/conf', function () use ($app) {
    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    if (isset($_POST['saveserverconf']) AND $app->config('demo_mod') == false) {

        $fromwork = new core\postget\postgetcoll();
        $mywork[] = $fromwork->collvars('POST');

        // TODO: [0] entfernen ;-)
        # Update ServerConf
        DB::update('config', array(
            'server_ip' => $mywork[0]['server_ip'],
            'root_user' => $mywork[0]['root_user'],
            # 'root_password' => $mywork[0]['root_password'],
            'ssh_port' => $mywork[0]['ssh_port'],
            'sp_titel' => $mywork[0]['sp_titel'],
            'doc_root' => $mywork[0]['doc_root'],
            'default_local' => $mywork[0]['local'],
            'wartungsmodus' => $mywork[0]['wartungsmodus'],
            'adminMail' => $mywork[0]['adminMail']
        ), "id=%s", '1');

        if (!empty($mywork[0]['root_password'])) {

            DB::update('config', array(
                'root_password' => $mywork[0]['root_password']
            ), "id=%s", '1');
        }
    }


    $server = DB::queryFirstRow("SELECT * FROM config");
    $app->render('server/serverconf.phtml', compact('server'));

})->name('doLogin');




