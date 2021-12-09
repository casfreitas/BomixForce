<?php
/*
* Dica: Sempre mantenha os arquivos de download em uma mesma pasta, separada dos arquivos do site.
* Neste script usaremos a pasta download para esta furçãoo.
*/

//FINANCEIRO
//if (isset($_POST['pasta'])) {
if ($_POST['pasta'] == 'Danfe' || $_POST['pasta'] == 'XML') {

  if ($_POST['pasta'] == 'Danfe') {
    $ext = '.pdf';
  } else {
    $ext = '.xml';
  }

  $arquivo = $_POST['nota'] . $ext; // Nome do Arquivo
  $local = '//192.168.254.74/documentos/' . $_POST['pasta'] . '/'; // Pasta que contém os arquivos para download
  $local_arquivo = $local . $arquivo; // Concatena o diretório com o nome do arquivo
  /*
* Por segurança, o script verifica se o usuário esta tentato sair da pasta especificada para 
* os arquivos de download (stripos($arquivo, './') !== false || stripos($arquivo, '../') !== false),
* isso irá bloquear a tentativa de fazer download de arquivos não permitidos.
* Na mesma função verificamos se o arquivo existe (!file_exists($arquivo)).
*/
  if (stripos($arquivo, './') !== false || stripos($arquivo, '../') !== false || !file_exists($local_arquivo)) {
    //echo 'O arquivo não foi encontrado.';
    session_start();
    $_SESSION["erro"] = "O arquivo não foi encontrado!";
    header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
  } else {
    header('Cache-control: private');
    header('Content-Type: application/octet-stream');
    header('Content-Length: ' . filesize($local_arquivo));
    header('Content-Disposition: filename=' . $arquivo);
    header('Content-Disposition: attachment; filename=' . basename($local_arquivo));

    // Envia o arquivo Download
    readfile($local_arquivo);
    exit;
  }
}

//FINANCEIRO - BOLETOS
if (isset($_POST['parcela'])) {

  $arquivo = $_POST['parcela'] . '.pdf'; // Nome do Arquivo
  $local = 'documentos/Boletos/Itau/'; // Pasta que contém os arquivos para download
  $local_arquivo = $local . $arquivo; // Concatena o diretório com o nome do arquivo
  /*
* Por segurança, o script verifica se o usuário esta tentato sair da pasta especificada para 
* os arquivos de download (stripos($arquivo, './') !== false || stripos($arquivo, '../') !== false),
* isso irá bloquear a tentativa de fazer download de arquivos não permitidos.
* Na mesma função verificamos se o arquivo existe (!file_exists($arquivo)).
*/
  if (stripos($arquivo, './') !== false || stripos($arquivo, '../') !== false || !file_exists($local_arquivo)) {
    //echo 'O arquivo não foi encontrado.';
    session_start();
    $_SESSION["erro"] = "O arquivo não foi encontrado!";
    header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
  } else {
    header('Cache-control: private');
    header('Content-Type: application/octet-stream');
    header('Content-Length: ' . filesize($local_arquivo));
    header('Content-Disposition: filename=' . $arquivo);
    header('Content-Disposition: attachment; filename=' . basename($local_arquivo));

    // Envia o arquivo Download
    readfile($local_arquivo);
    exit;
  }
}

//DOCUMENTOS
if (isset($_GET['doc'])) {

  $arquivo = $_GET['doc']; // Nome do Arquivo
  $pasta   = $_GET['user']; // Nome do Pasta
  $local = 'upload/documentos/' . $pasta . '/'; // Pasta que contém os arquivos para download
  $local_arquivo = $local . $arquivo; // Concatena o diretório com o nome do arquivo

  if (stripos($arquivo, './') !== false || stripos($arquivo, '../') !== false || !file_exists($local_arquivo)) {
    //echo 'O arquivo não foi encontrado.';
    session_start();
    $_SESSION["erro"] = "O arquivo não foi encontrado!";
    header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
  } else {
    header('Cache-control: private');
    header('Content-Type: application/octet-stream');
    header('Content-Length: ' . filesize($local_arquivo));
    header('Content-Disposition: filename=' . $arquivo);
    header('Content-Disposition: attachment; filename=' . basename($local_arquivo));

    readfile($local_arquivo);
    exit;
  }
}

//DOCUMENTOS
if (isset($_GET['msg'])) {

  $arquivo = $_GET['msg']; // Nome do Arquivo
  $pasta   = $_GET['id']; // Nome do Pasta
  $local = 'upload/nc/' . $pasta . '/'; // Pasta que contém os arquivos para download
  $local_arquivo = $local . $arquivo; // Concatena o diretório com o nome do arquivo

  if (stripos($arquivo, './') !== false || stripos($arquivo, '../') !== false || !file_exists($local_arquivo)) {
    //echo 'O arquivo não foi encontrado.';
    session_start();
    $_SESSION["erro"] = "O arquivo não foi encontrado!";
    header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
  } else {
    header('Cache-control: private');
    header('Content-Type: application/octet-stream');
    header('Content-Length: ' . filesize($local_arquivo));
    header('Content-Disposition: filename=' . $arquivo);
    header('Content-Disposition: attachment; filename=' . basename($local_arquivo));

    readfile($local_arquivo);
    exit;
  }
}
