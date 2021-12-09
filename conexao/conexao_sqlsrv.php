<?php
// $serverName = "192.168.254.53";
// $connectionInfo = array("Database" => "Cimatec", "CharacterSet" => "UTF-8", "UID" => "carlos.silveira", "PWD" => "#B0mix!");
// $conn = sqlsrv_connect($serverName, $connectionInfo);

// if ($conn === false) {
//     die(print_r(sqlsrv_errors(), true));
// }


$serverName = "192.168.254.75";
$connectionInfo = array("Database" => "BomixForce", "CharacterSet" => "UTF-8", "UID" => "BomixForce", "PWD" => "zbo67OIzxk");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
