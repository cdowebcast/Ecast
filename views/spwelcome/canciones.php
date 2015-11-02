<?php

$puerto = $_REQUEST['port'];
echo '<meta charset="UTF-8">';

echo '<table class="table table-hover"><tbody><tr><th>Nombre</th><th>Fecha</th></tr>';
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
                      echo "<td><i class=\"fa fa-music\"></i> $cancionsola[3]</td>";
                      echo "<td><i class=\"fa fa-clock-o\"></i> $cancionsola[0]</td>";
					  echo utf8_encode("$cancionsola[3]");
                      echo "</tr>";
                    }
					}
					echo '</tbody></table>';
?>