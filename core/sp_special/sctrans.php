<?php
namespace core\sp_special;
class sctrans
{

    # Auslesen der SSH Config aus der DB
    public function getSSHConf()
    {
        $config = \DB::queryFirstRow("SELECT * FROM config WHERE id='1'");
        $SSHConf['ip'] = $config['server_ip'];
        $SSHConf['user'] = $config['root_user'];
        $SSHConf['pass'] = $config['root_password'];
        $SSHConf['port'] = $config['ssh_port'];
        return $SSHConf;
    }

    public function writeSc_TransConf($Sc_serv_conf_id)
    {

# Einlesen der Daten für SC_Serv
        $Sc_Serv_conf = \DB::queryFirstRow("SELECT * FROM sc_trans_conf WHERE id=%s", $Sc_serv_conf_id);

# Proof if Dir exist
        if (is_dir("shoutcastconf/" . $Sc_Serv_conf['serverport']) == false) {
            $this->creatDirHome($Sc_Serv_conf['serverport']);
        }

# ColumList aus der Datenbank
        $columns = \DB::columnList('sc_trans_conf');
        $Sc_Serv['trans'] = ''; # Array für die Spalte

# Datei öffnen
        $datei = fopen($_SERVER['DOCUMENT_ROOT'] . '/shoutcastconf/' . $Sc_Serv_conf['serverport'] . '/sc_trans.conf', 'w');
        foreach ($columns as $column) {
            $Sc_Serv['trans'][$column] = $Sc_Serv_conf[$column]; # Speichert alles in einem Array für Fehlerauswerung
        }
        foreach ($Sc_Serv['trans'] as $name => $wert) {
            if ($name == 'id' OR $wert == '') {
            } else {
                fwrite($datei, $name . '=' . $wert . "\r\n");
            }
        }
        // DJ Konfiguration in de SC_Trans schreiben
        $sc_rel_id = \DB::queryFirstRow("SELECT id FROM sc_rel WHERE sc_serv_conf_id=%s AND  sc_trans_id=%s",$Sc_serv_conf_id,$Sc_Serv_conf['id'] );

        $dj_prio_Data = \DB::query("SELECT * FROM dj_accounts WHERE dj_of_sc_rel_id=%s", $sc_rel_id['id']);
        $merker = '1';
        foreach ($dj_prio_Data as $row){
            $userConf = \DB::queryFirstRow("SELECT * FROM accounts WHERE id=%s", $row['dj_accounts_id']);
            fwrite($datei, 'djlogin_'.$merker.'=' . $userConf['kundennummer'] . "\r\n");
            fwrite($datei, 'djpassword_'.$merker.'=' . $userConf['dj_clearpass'] . "\r\n");
            fwrite($datei, 'djpriority_'.$merker.'=' . $row['dj_prio']. "\r\n");
            $merker++;
        }


        fclose($datei); # Datei schließen
    }

    public function startSc_Trans($sc_rel_id)
    {
        // Stream Conf
        $sc_serv_rel = \DB::queryFirstRow("SELECT * FROM sc_rel WHERE id=%s", $sc_rel_id);

        // StreamDaten
        $sc_serv_id = \DB::queryFirstRow("SELECT * FROM sc_serv_conf WHERE id=%s", $sc_serv_rel['sc_serv_conf_id']);

        // STREAMPORT
        $streamPort = $sc_serv_id['PortBase'];

        // SSH CONFIG ANLEGEN
        $this->writeSc_TransConf($sc_serv_rel['sc_serv_conf_id']);

        $this->writeNewPlaylist($sc_rel_id, $sc_serv_id['PortBase']);

        // SC_SERV Version
        $sc_trans = $this->getScTrans($sc_serv_rel['sc_trans_version_id']);

        // SSH AUSFÜHREN
        $SSHConf = $this->getSSHConf();
        $connection = ssh2_connect($SSHConf['ip'], $SSHConf['port']);
        ssh2_auth_password($connection, $SSHConf['user'], $SSHConf['pass']);
        $ssh2_exec_com = ssh2_exec($connection, '/var/www/html/shoutcast/sc_trans ' . $_SERVER['DOCUMENT_ROOT'] . '/shoutcastconf/' . $streamPort . '/source.conf </dev/null 2>/dev/null >/dev/null & echo $!');
        //$ssh2_exec_com = ssh2_exec($connection, $_SERVER['DOCUMENT_ROOT'] . '/shoutcast/' . $sc_trans . ' ' . $_SERVER['DOCUMENT_ROOT'] . '/shoutcastconf/' . $streamPort . '/sc_trans.conf </dev/null 2>/dev/null >/dev/null & echo $!');
        sleep(1);

        $pid = stream_get_contents($ssh2_exec_com);


        $this->setPID($sc_rel_id, $pid);

    }

    public function killSc_Trans($sc_rel_id)
    {
        $PID = \DB::queryFirstRow("SELECT sc_trans_pid FROM sc_rel WHERE id=%s", $sc_rel_id);
        $SSHConf = $this->getSSHConf();
        $connection = ssh2_connect($SSHConf['ip'], $SSHConf['port']);
        ssh2_auth_password($connection, $SSHConf['user'], $SSHConf['pass']);
        ssh2_exec($connection, 'kill ' . $PID['sc_trans_pid']);
        sleep(1);
        $this->setPID($sc_rel_id, '0');
    }

    public function setPID($sc_serId, $PID)
    {
        \DB::update('sc_rel', array(
            'sc_trans_pid' => $PID
        ), "id=%s", $sc_serId);
    }

    public function getScTrans($tabelId)
    {

        $scservname = \DB::queryFirstRow("SELECT file_name FROM sc_version WHERE id=%s", $tabelId);
        return $scservname['file_name'];
    }

    public function writeNewPlaylist($sc_rel_id, $Port){

        # ID der Playliste aus der sc_rel
        $playlistid = \DB::queryFirstRow("SELECT play_list_id FROM sc_rel WHERE id=%s", $sc_rel_id);

        # Name der Plaliyste
        $playlistname = \DB::queryFirstRow("SELECT playlist_name FROM playlist WHERE id=%s", $playlistid['play_list_id']);
        $datei = fopen( $_SERVER['DOCUMENT_ROOT'] . '/shoutcastconf/'.$Port.'/'.$playlistname['playlist_name'].'.lst', "w");

        # Abfrage der MP3 ID
        $results = \DB::query("SELECT mp3_id FROM playlist_mp3_rel WHERE playlist_id=%s", $playlistid['play_list_id'] );
        $MP3TitelSpeicher = '';
        foreach ($results as $row) {
            #MP3 Dateiname auslesen
            $mp3DirTitel = \DB::queryFirstRow("SELECT dir_titel FROM mp3 WHERE id=%s",$row['mp3_id']);
                $MP3TitelSpeicher[] = $_SERVER['DOCUMENT_ROOT'] . '/mp3collection/'.$mp3DirTitel['dir_titel'];
        }

        foreach($MP3TitelSpeicher as $key => $value){
            fwrite($datei,$value."\n");
        }
             fclose($datei);



    }

}