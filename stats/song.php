<?php
$port = $_REQUEST['p'];
$puerto = base64_decode($port);

$curl_handle=curl_init();
curl_setopt($curl_handle, CURLOPT_URL,$_SERVER['SERVER_ADDR'].':'.$puerto.'/panel.xsl');
curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_handle,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; es-CO; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
$query = curl_exec($curl_handle);
curl_close($curl_handle);

$xml = new SimpleXMLElement($query);
$song = $xml->cancion;
//$xml  = utf8_encode($query);

# Alle Informationen ausgeben
#print_r($xml);
echo $song;
?>