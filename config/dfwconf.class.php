<?php
class dfwconf {

   public static $DTFregName = '@KDNUM@';
   public static $DTFlickey = '@RECHNR@';


   var $URLcrypt = "SALT-KEY";          # Key zum Verschlüsseln
   var $dfw_Start_Module = 'login';      # Welches ist das Start-Modul


    public  function getModulFromUrl($URL, $dfw_module){

        # URL zersetzen und Modul Laden.
        $schluesselwoerter = preg_split("/\//", $URL);


        if(in_array($schluesselwoerter['1'], $dfw_module)){
            $dfw_LoadModul = $this->Modul_Exist($schluesselwoerter[1]);


            $mod = "./module/".$dfw_LoadModul."/index.php";
        }else{
            $mod = "./module/".$this->dfw_Start_Module."/index.php";
        }

        return $mod;
    }

    public function loadModule($dfw_LoadModul){
        $var = "./module/".$dfw_LoadModul."/index.php";
        return $var;
    }

    private function Modul_Exist($Modul){

        if (  is_dir("./module/".$Modul) == true  AND !empty($Modul)){
            return $Modul;
        }else{
            return $this->dfw_Start_Module;
        }
    }

    /*              Module-Funktionen
     *      1.      Laden der ModulCss - Datein     Load_Custem_CSS
     *      2.
     */
    public  function HTML_Header($Titel,$dfw_module){
        print '<!doctype html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>'.$Titel.'</title>
<meta charset="iso-8859-1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">

<!-- Styles -->
<link href="/css/bootstrap.css" rel="stylesheet">
<link href="/css/bootstrap-responsive.css" rel="stylesheet">
<link href="/css/bootstrap-overrides.css" rel="stylesheet">

<link href="/css/ui-lightness/jquery-ui-1.8.21.custom.css" rel="stylesheet">
<link href="/css/components/signin.css" rel="stylesheet" type="text/css">
<link href="/css/slate.css" rel="stylesheet">
<link href="/css/slate-responsive.css" rel="stylesheet">

<link href="/css/pages/invoice.css" rel="stylesheet">

<link href="/css/pages/reports.css" rel="stylesheet">
<link href="/css/msgGrowl.css" rel="stylesheet">
<link href="/js/plugins/msgAlert/css/msgAlert.css" rel="stylesheet">

<!-- Javascript -->
<script src="/js/jquery-1.7.2.min.js"></script>
<script src="/js/jquery-ui-1.8.21.custom.min.js"></script>
<script src="/js/jquery.ui.touch-punch.min.js"></script>
<script src="/js/bootstrap.js"></script>

<script src="/js/plugins/msgGrowl/js/msgGrowl.js"></script>
<script src="/js/plugins/msgAlert/js/msgAlert.js"></script>
<script src="/js/plugins/timepicker/jquery.ui.timepicker.min.js"></script>
<script src="/js/plugins/colorpicker/js/bootstrap-colorpicker.js"></script>

<script src="/js/Slate.js"></script>



<script src="/js/ui-elements.js"></script>
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
            ';
        $this->get_js_file($dfw_module);
        $this->get_css_file($dfw_module);

         print "\n".'</head><body>';
     }

    public function get_js_file($dfw_module){

        foreach ($dfw_module as $ModulReg){
            require './module/'.$ModulReg.'/modconf.php';
            if (isset($dfw_module_js)){
                $Module[$ModulReg] = $dfw_module_js;
                unset ($dfw_module_js);
            }
        }
        //TODO: KA WARUM ABER MACHT FEHLER VERBINDUNG MIT modulreg!!
        foreach ($Module as $type => $properties) {
            foreach ($properties as $property => $value) {
                echo '<script src="/module/'.$type.'/js/'.$value.'.js" type="text/javascript"></script>'."\n";
            }
        }


    }

    public function get_css_file($dfw_module){

        foreach ($dfw_module as $ModulReg){
            require './module/'.$ModulReg.'/modconf.php';
            if (isset($dfw_module_style)){
            $Module[$ModulReg] = $dfw_module_style;
                unset($dfw_module_style);
            }
        }

       // TODO: KA WARUM ABER MACHT FEHLER

        foreach ($Module as $type => $properties) {
            foreach ($properties as $property => $value) {
                echo '<link rel="stylesheet" type="text/css" href="/module/'.$type.'/css/'.$value.'.css">'."\n";
            }
        }

    }

    /*
     *      Einbenden der View
     */

    public function LoadView($ViewName, $Module_Ordner_Name){

        $Phtml_View= "./module/".$Module_Ordner_Name."/view/".$ViewName.'.phtml';

        if (  is_dir("./module/".$Module_Ordner_Name."/view/") == true  AND !empty($Phtml_View)){

            return $Phtml_View;

        }else{
            print 'View-Nicht-GEFUNDEN';
        }
    }

   /*
    *
    *       Security-Eigenschaften
    *
    */
    public function showUserData(){
            $name = self::$DTFregName;
            $softUser =  $name.'_'.self::$DTFlickey;
        return $softUser;
    }

    public function showUser(){
        DB::query("SELECT * FROM accounts");
        return DB::count();
    }

    public function showServerInfo(){
        $phpversion = PHP_VERSION;
        $server = $_SERVER['SERVER_SOFTWARE'];

        return $phpversion.'_'.$server;

    }






    /*              Übersicht
     *        1.    URL ver-/entschlüsseln          EnCryptURL / DeCryptURL
     *
     *
     *
     */

    // Verschlüsselung der URL bei Weitergabe von geschützten Daten in der URL! ->
    private function EnCryptURL(){

    }

    private function DeCryptURL(){


    }




}