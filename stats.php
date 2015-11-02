<?php
$url = "http://admin:edinedin@45.58.36.48:8004/admin/listclients?mount=/autodj";
$xml = simplexml_load_file($url);
$oyentes = $xml->source->listener;

                foreach ($oyentes as $oyente) {
                ?>
                <ul>
                  <li>IP: <?php echo $oyente->IP; ?></li>
                  <li>Tiempo: <?php echo ($oyente->Connected / 3600) % 60; ?> Horas <?php echo ($oyente->Connected / 60) % 60; ?> Minutos <?php echo ($oyente->Connected) % 60; ?> Segundos</li>
                  <li>Navegador: <?php echo $oyente->UserAgent; ?></li>
                </ul>
                <?php
                } // end foreach
                ?>