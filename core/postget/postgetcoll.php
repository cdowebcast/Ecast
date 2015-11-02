<?php
namespace core\postget;


class postgetcoll {

    public function  collvars($type = 'REQUEST')
{
    if($type == 'REQUEST')
        $ay = $_REQUEST;
    elseif($type == 'POST')
        $ay = $_POST;
    elseif($type == 'GET')
        $ay = $_GET;
    $rtn = array();

    foreach($ay as $a1 => $a2)
        $rtn[$this->sicher($a1)] = $this->sicher($a2);
    return $rtn;
}

    private function sicher($string) {
    return trim(strip_tags($string));
}
}