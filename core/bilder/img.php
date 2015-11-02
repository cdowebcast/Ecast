<?php
namespace core\bilder;

class img {

    public function ImgSrcInclude($ImgName, $dfw_module_Name){
        return '/module/'.$dfw_module_Name.'/img/'.$ImgName;
    }

}