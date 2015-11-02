<?php
 


/*
 *      Main Route
 */

$app->get('/', function () use ($app) {

    if($_SESSION['group'] == 'adm'){
        $SPMenu = new SP\Menu\MenuInclusion();
        $SPMenu->MenuInclude($app);



        /*
         *      Update - Benachrichtigungen
         */

        $getConfigFromDB = DB::queryFirstRow("SELECT * FROM config WHERE id=%s",'1');
       if($getConfigFromDB['upd_message'] == true){
        function get_http_response_code($url) {
            $headers = get_headers($url);
            return substr($headers[0], 9, 3);
        }

        $URL = "http://login.streamerspanel.de/newversioncheck.php?rechNr=@RECHNR@&kdnr=@KDNUM@";
        if(get_http_response_code($URL) != "404"){
            $json=json_decode(file_get_contents($URL));

            if($app->config('sp.version_clear') < $json->SP_Version){
?>
            <div class="container">
            <div class="row">
            <div class="span12">
            <div class="alert alert-info">
                <h4 class="alert-heading"><?= $json->Title; ?></h4>
                <?= $json->Message; ?>
            </div>
            </div>
                <!-- /span12 -->
            </div>
                <!-- /.row -->
            </div>
            <!-- /.container -->
<?php
            }

        }else{
        }
       }



        $app->render('spwelcome/adm.phtml', compact('Users'));



        $handle=@fopen("http://login.streamerspanel.de/request.php","r");

        (!isset($_SERVER['SERVER_ADDR'])) ? ($serverAddr = 'Nicht gesetzt') : ($serverAddr =$_SERVER['SERVER_ADDR']);
        (!isset($_SERVER['SCRIPT_FILENAME'])) ? ($serverScript = 'Nicht gesetzt') : ($serverScript =$_SERVER['SCRIPT_FILENAME']);
        (!isset($_SERVER['SERVER_SIGNATURE'])) ? ($serverSignature = 'Nicht gesetzt') : ($serverSignature =$_SERVER['SERVER_SIGNATURE']);
        (PHP_VERSION == false) ? ($serverPHP = 'Nicht gesetzt') : ($serverPHP = PHP_VERSION);



        if($handle == true){
        date_default_timezone_set('Europe/Berlin');
        if (!file_exists('.lastNewsForAdmin')) {
            file_put_contents('.lastNewsForAdmin', date('Y-m-d'));
            Requests::register_autoloader();
            $data = array(
                'SendUserStat' => '@KDNUM@@RECHNR@',
                'kdNumb' => '@KDNUM@',
                'rechnnumb' => '@RECHNR@',
                'server_ip' => $serverAddr,
                'php_v' => $serverPHP,
                'sp_version' => $app->config('sp.version'),
                'script_filename' => $serverScript,
                'server_signature' => $serverSignature
            );

            $response = Requests::post('http://login.streamerspanel.de/request.php', array(), $data);
        } else {
            $lastUsageSentAt = file_get_contents('.lastNewsForAdmin');
            if ($lastUsageSentAt !== date('Y-m-d')) {
                file_put_contents('.lastNewsForAdmin', date('Y-m-d'));
                Requests::register_autoloader();
                $data = array(
                    'SendUserStat' => '@KDNUM@@RECHNR@',
                    'kdNumb' => '@KDNUM@',
                    'rechnnumb' => '@RECHNR@',
                    'server_ip' => $serverAddr,
                    'php_v' => $serverPHP,
                    'sp_version' => $app->config('sp.version'),
                    'script_filename' => $serverScript,
                    'server_signature' => $serverSignature
                );
                $response = Requests::post('http://login.streamerspanel.de/request.php', array(), $data);
            }
           }
         }









    }elseif($_SESSION['group']== 'user'){
            $SPMenu = new SP\Menu\MenuInclusion();
            $SPMenu->MenuInclude($app);
            $app->render('spwelcome/user.phtml', compact('Users'));


    }elseif($_SESSION['group'] == 'dj'){
        $SPMenu = new SP\Menu\MenuInclusion();
        $SPMenu->MenuInclude($app);
        $app->render('spwelcome/dj.phtml', compact('Users'));    }
})->name('not-restricted');







