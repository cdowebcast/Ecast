<?php

namespace core\demo;


use core\sp_special\growl;

class demomod extends growl {

     function CheckDemo($Session_Demo_Mod){
        if($Session_Demo_Mod == true)
        {
          self::writeGrowl('warning',_('DEMO-MODUS'),_('Starten, Stoppen, hinzufügen, bearbeiten und löschen nicht möglich! '));
        }
    }

} 