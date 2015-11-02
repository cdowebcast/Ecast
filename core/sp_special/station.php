<?php
namespace core\sp_special;
class station
{

    public function createPlaylst($StreamId)
    {
        $Sc_Trans_conf = \DB::queryFirstRow("SELECT * FROM sc_rel WHERE id=%s", $StreamId);
        $Sc_Playlist = \DB::queryFirstRow("SELECT * FROM playlist WHERE id=%s", $Sc_Trans_conf['play_list_id']);
        $SC_Port_Base = \DB::queryFirstRow("SELECT PortBase FROM sc_serv_conf WHERE id=%s", $Sc_Trans_conf['sc_serv_conf_id']);
        $datei = fopen("userconf/" . $SC_Port_Base['PortBase'] . "/" . $Sc_Playlist['playlist_name'] . ".lst", "w+");
        $columns = \DB::query("SELECT mp3_id FROM playlist_mp3_rel WHERE playlist_id=%s", $Sc_Playlist['id']);
        foreach ($columns as $mp3) {
            $mp3_name = \DB::query("SELECT dir_titel FROM mp3 WHERE id=%s", $mp3['mp3_id']);
            foreach ($mp3_name as $name) {
                fwrite($datei, $_SERVER['DOCUMENT_ROOT'] . "/mp3collection/" . $name['dir_titel'] . "\r\n");
            }
        }
        fclose($datei);
    }
}