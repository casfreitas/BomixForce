<?php
// try {
//   $conn = new PDO('sqlsrv:Server=192.168.254.53;Database=Cimatec', 'carlos.silveira', '#B0mix!');
// } catch (PDOException $e) {
//   echo $e->getMessage();
//   exit;
// }



try {
  $conn = new PDO('sqlsrv:Server=192.168.254.75;Database=BomixForce', 'BomixForce', 'zbo67OIzxk');
} catch (PDOException $e) {
  echo $e->getMessage();
  exit;
}
