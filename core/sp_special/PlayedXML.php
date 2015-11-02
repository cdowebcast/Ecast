<?php
namespace core\sp_special;

class PlayedXML {

    public function playedXMLParms($port, $passw){

        $file = file('/var/www/html/userconf/'.$port.'/var/log/playlist.log');
        for ($i = count($file)-6; $i < count($file); $i++) {
            print $file[$i] . "\n";



        }
    }

} 