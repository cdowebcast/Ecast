<?php
header('Content-type: text/html; charset=utf-8');
date_default_timezone_set('America/Bogota');
$port = $_REQUEST['p'];
$puerto = base64_decode($port);

echo '<table class="table table-hover"><tbody><tr><th>Nombre</th><th>Hora</th></tr>';
$file = file("/var/www/html/userconf/" . $puerto . "/var/log/playlist.log");
					$conte = count($file);
					
					if($conte < 6) {
						echo "<tr>";
						echo "<td><i class=\"fa fa-music\"></i> Vacio</td>";
						echo "<td><i class=\"fa fa-clock-o\"></i> Vacio</td>";
						echo "</tr>";
						}
					else {
                    for ($i = ($conte)-1; $i > ($conte)-6; $i--) {
                      $cancion = $file[$i];
                      $cancionsola = explode("|", $cancion);

                      echo "<tr>";
                      echo "<td><i class=\"fa fa-music\"></i> ". utf8_encode($cancionsola[3]) ."</td>";
                      echo "<td><i class=\"fa fa-clock-o\"></i> ". date('g:i a',strtotime($cancionsola[0])) ." </td>";
                      echo "</tr>";
                    }
					}
					echo '</tbody></table>';
?>