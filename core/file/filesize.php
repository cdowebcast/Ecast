<?php

namespace core\file;

class filesize{

    public function format_filesize($size) {
        $arr_units = array(
            'KB',
            'Kibi',
            'MB',
            'GB',
            'TB',
            'TB'
        );
        for ($i = 0; $size > 1024; $i++) {
            $size /= 1024;
        }
        return number_format($size, 2, ',', '').' '.$arr_units[$i];
    }





}