<?php

namespace core\phparray;


class simplifyArray {


    /*      Multidimensionale Arrays, variabler Tiefe, in eindimensionale Array umwandeln
     *
     *              $array = array(1, 2, array('bli', 'bla', 'blub'));
     *              $output = array();
     *              simplifyArray($array, $output);
     */

   public function simplifyArray(array $array, array &$output) {
        foreach ($array as $v) {
            if (is_array($v)) {
                simplifyArray($v, $output);
            } else {
                $output[] = $v;
            }
        }
    }
}