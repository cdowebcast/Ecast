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
 *
 *      Admin Routes
 *
 */

# Neue Nachrichten anlegen  1/2
$app->get('/news/addnews', function () use ($app) {

    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);

            $app->render('news/addnews.phtml', compact('Users'));

})->name('addnews');
# Neue Nachrichten anlegen  2/2
$app->post('/news/addnews', function () use ($app) {
    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    if (isset($_POST['eintragen'])) {
        $form = new \core\postget\postgetcoll();
        $formData[] = $form->collvars('POST');
        // TODO: Datum bis wann angezeigt wird.
        unset($formData['0']['eintragen']);
        \DB::insert('news', $formData);
        echo "
                 <script>
                 $.msgGrowl ({
                        type: 'info'
                        , title: '"._('Nachricht hinzugefügt')."'
                        , text: ''
                        , position: $(this).attr ('rel')
                    });

                </script> ";
    }
    $app->render('news/viewlist.phtml', compact('Users'));




})->name('doLogin');

$app->get('/news/list', function () use ($app) {
    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    $app->render('news/viewlist.phtml', compact('Users'));
})->name('list');



$app->post('/news/list', function () use ($app) {
    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);

    if (isset($_POST['delmes'])) {
        DB::delete('news', "id=%s", $_POST['delmes']);
        echo "
 <script>
 $.msgGrowl ({
        type: 'info'
        , title: '"._('Nachricht wurde gelöscht')."'
        , text: ''
        , position: $(this).attr ('rel')
    });
</script> ";

    }

    if (isset($_POST['have_to_read'])) {
        $changer = explode(".", $_POST['have_to_read']);
        DB::update('news', array(
            'have_to_read' => $changer['1']
        ), "id=%s", $changer['0']);
    }

    if (isset($_POST['is_aktiv'])) {
        $changer = explode(".", $_POST['is_aktiv']);
        DB::update('news', array(
            'is_aktiv' => $changer['1']
        ), "id=%s", $changer['0']);
    }

    if (isset($_POST['login_news'])) {
        $changer = explode(".", $_POST['login_news']);
        DB::update('news', array(
            'login_news' => $changer['1']
        ), "id=%s", $changer['0']);
    }
    $app->render('news/viewlist.phtml');
})->name('doLogin');


/*
 *
 *      User Routes
 *
 */

$app->get('/news/messages', function () use ($app) {

    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    $app->render('news/usernews.phtml', compact('Users'));

})->name('list');


$app->post('/news/messages', function () use ($app) {


    DB::insert('news_to_read', array(
        'user_id' => $_SESSION['account_id'],
        'news_id' => $_POST['close'],
        'user_read_it' => '1'
    ));

    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    $app->render('news/usernews.phtml', compact('Users'));


})->name('list');

$app->post('/news/spwelcome', function () use ($app) {


    DB::insert('news_to_read', array(
        'user_id' => $_SESSION['account_id'],
        'news_id' => $_POST['close'],
        'user_read_it' => '1'
    ));

    $SPMenu = new SP\Menu\MenuInclusion();
    $SPMenu->MenuInclude($app);
    $app->render('spwelcome/user.phtml', compact('Users'));

})->name('list');