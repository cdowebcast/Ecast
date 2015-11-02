<?php
$puerto = $_REQUEST['p'];
$ext = $_REQUEST['e'];
$host = $_SERVER['SERVER_ADDR'];
$contents = "http://$host:$puerto/live";

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false); // required for certain browsers
header("Content-Type: audio/x-mpegurl");
header("Content-Disposition: inline; filename=\"playlist$ext\";" );
header("Content-Transfer-Encoding: binary");
header("Content-Length: " . strlen($contents));
ob_clean();
flush();
echo $contents;
?>