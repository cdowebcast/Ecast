<?php
namespace core\url;


class urledit {

    public function getUrlParm($URL){ // $_SERVER ['REQUEST_URI']

        $schluesselwoerter = preg_split("/\//", $URL);

        unset($schluesselwoerter[0]);
        return $schluesselwoerter;
    }
}