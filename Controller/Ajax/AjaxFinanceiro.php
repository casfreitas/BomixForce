<?php
require_once('../../conexao/conexao_sqlsrv.php');

@$notaProd = $_POST['nota'];

$types = array('pdf');
if ($handle = opendir('../../documentos/Boletos/Itau/')) {
  while ($entry = readdir($handle)) {
    $ext = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
    if (in_array($ext, $types))

      //$numero = substr($entry, 0, 11); //posição inicial = 10, comprimento = 1
      $numero = substr($entry, 0, 9); //posição inicial = 10, comprimento = 1
    $numero_number = substr($entry, 10, 1); //posição inicial = 10, comprimento = 1

    //echo $numero_number . '<br>';
    if (@$numero == $notaProd) {
      //$response = '<option value="' . $numero . '">' . $numero . '-' . $numero_number . '</option>';
      @$response = '<option value="' . $numero . '-' . $numero_number . '">' . $numero_number . 'ª Parcela</option>';
      //echo $response;
    }
    //echo $numero . '<br>';
  }
  closedir($handle);
}
