<?php
include '../conexao/conexao_sqlsrv.php';

//DADOS ENVIADOS PELO FORMULÁRIO
$id            = $_POST['id'];
$id_user       = $_POST['id_user'];
$usuario       = $_POST['nome_completo'];
$cliente       = $_POST['cliente'];
$cadastro_data = $_POST['cadastro_data'];
$email         = $_POST['email'];

$id_cad = md5(uniqid(rand(), true));

if ($_POST['outros'] != '') {
  $outros = ' - ' . strtoupper($_POST['outros']);
}
if ($_POST['tipo_tampa'] != '') {
  $tipo_tampa = ' - ' . strtoupper($_POST['tipo_tampa']);
}
if ($_POST['tipo_balde'] != '') {
  $tipo_balde = ' - ' . strtoupper($_POST['tipo_balde']);
}
if ($_POST['tipo_certidao'] != '') {
  $tipo_balde = ' - ' . strtoupper($_POST['tipo_certidao']);
}

$documento  = $_POST['documento'] . $outros . $tipo_tampa . $tipo_balde . $tipo_certidao;
$arquivo    = $_POST['arquivo'];



/**************************************************************************************************************************************
 *************************************************** SOLICITAR DOCUMENTOS *************************************************************
 **************************************************************************************************************************************/

if ($_GET['funcao'] == "cad_documento") {

  // CRIA UMA PASTA COM O NOME DO "ID_USER"
  $dirname = $id_cad;
  $dir = "../upload/documentos/$id_cad";
  mkdir($dir, 0777);

  if ($_FILES['arquivo']['tmp_name'] != "") {

    $extensao  = strtolower(substr($_FILES['arquivo']['name'], -4)); // pega a extensao do arquivo
    $novo_nome = date('Ymd_His') . $extensao; // define o nome do arquivo
    $diretorio = "../upload/documentos/$id_cad/"; // define o diretorio para onde enviaremos o arquivo

    move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio . $novo_nome); // EFETUA O UPLOAD

    $user_sql = "INSERT INTO sys_tb_documentos (
                                                doc_id,
                                                us_fk,
                                                doc_documento,
                                                doc_solicitado,
                                                doc_data_cadastro,
                                                doc_data_atualizado
                                                ) VALUES (
                                                '$id_cad',
                                                '$id_user',
                                                '$documento',
                                                '$novo_nome',
                                                GETDATE(),
                                                GETDATE()
                                                )";
  } else {

    $user_sql = "INSERT INTO sys_tb_documentos (
                                                doc_id,
                                                us_fk,
                                                doc_documento,
                                                doc_data_cadastro,
                                                doc_data_atualizado
                                                ) VALUES (
                                                '$id_cad',
                                                '$id_user',
                                                '$documento',
                                                GETDATE(),
                                                GETDATE()
                                                )";
  }

  $stmt = sqlsrv_query($conn, $user_sql);
  if ($stmt) {
    echo "Dados inseridos com sucesso!\n";
  } else {
    echo "Erro no cadastro!\n";
    die(print_r(sqlsrv_errors(), true));
  }

  sqlsrv_free_stmt($stmt);
  sqlsrv_close($conn);


  // ENVIA EMAIL PARA O USUÁRIO APÓS O CADASTRADO
  $email_remetente = "carlos.silveira.bmx@gmail.com"; // EMAIL CADASTRADO NO WEBMAIL DO XAMPP

  // CONFIGURAÇÕES
  $email_destinatario = "$email"; // EMAIL QUE RECEBERA A MENSAGEM
  $email_destino_comercial = "carlos.silveira.bmx@gmail.com"; // EMAIL QUE RECEBERA A MENSAGEM
  $email_reply = "carlos.silveira.bmx@gmail.com";
  $email_assunto = "BOMIX FORCE - Documento solicitado"; //ASSUNTO

  // CORPO DO EMAIL
  $email_conteudo = "<h3 style='font-family: Arial, Helvetica, sans-serif; font-weight: normal;line-height: 25px;'>Registramos a sua solicitação de disponibilização de documentação. \n Disponibilizaremos os documentos requeridos em breve. \n Acompanhe o status da sua solicitação ao lado do seu registro. </h3> \n";
  $email_conteudo .= "<h2 style='font-family: Arial, Helvetica, sans-serif;'>Bomix Force</h2>";

  $headers[] = 'MIME-Version: 1.0';
  $headers[] = 'Content-type: text/html; charset=UTF-8';

  // CABEÇALHO DO EMAIL
  $headers[] = 'To: ' . $email_destinatario;
  $headers[] = 'From: ' . $email_remetente;

  // ENVIA O EMAIL PARA O CLIENTE
  mail($email_destinatario, $email_assunto, nl2br($email_conteudo), implode("\r\n", $headers));

  // ====================================================

  // CORPO DO EMAIL
  $email_conteudo_comercial  = "<strong><h2>Documento solicitado:</h2></strong>";
  $email_conteudo_comercial .= "<strong>Documento:</strong>   $documento \n";
  $email_conteudo_comercial .= "<strong>Solicitante:</strong> $usuario \n";
  $email_conteudo_comercial .= "<strong>Cliente:</strong>     $cliente \n";

  $headers_comercial[] = 'MIME-Version: 1.0';
  $headers_comercial[] = 'Content-type: text/html; charset=UTF-8';

  // CABEÇALHO DO EMAIL
  $headers_comercial[] = 'To: ' . $email_destino_comercial;
  $headers_comercial[] = 'From: ' . $email_remetente;

  // ENVIA O EMAIL PARA O COMERCIAL
  mail($email_destino_comercial, $email_assunto, nl2br($email_conteudo_comercial), implode("\r\n", $headers_comercial));

  // ====================================================

  //ENVIA MENSAGEM
  session_start();
  $_SESSION["msg"] = "Documento solicitado com sucesso!";

  //VOLTA A PÁGINA ANTERIOR
  header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
}



/**************************************************************************************************************************************
 ************************************************** RESPONDER SOLICITAÇÃO *************************************************************
 **************************************************************************************************************************************/
if ($_GET['funcao'] == "envia_documento") {

  $extensao = strtolower(substr($_FILES['arquivo']['name'], -4)); //pega a extensao do arquivo
  $novo_nome = date('Ymd_His') . $extensao; //define o nome do arquivo
  $diretorio = "../upload/documentos/$id/"; //define o diretorio para onde enviaremos o arquivo

  move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio . $novo_nome); //EFETUA O UPLOAD    

  $sql = "UPDATE
            sys_tb_documentos
          SET
            doc_enviado         = '$novo_nome',
            doc_data_atualizado = GETDATE()
          WHERE
            doc_id = '$id'";

  $params = array($qnty);

  $stmt = sqlsrv_query($conn, $sql, $params);
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }


  // ENVIA EMAIL PARA O USUÁRIO APÓS O CADASTRADO
  $email_remetente = "carlos.silveira.bmx@gmail.com"; // EMAIL CADASTRADO NO WEBMAIL DO XAMPP

  // CONFIGURAÇÕES
  $email_destinatario = "$email"; // EMAIL QUE RECEBERA A MENSAGEM
  $email_reply = "carlos.silveira.bmx@gmail.com";
  $email_assunto = "BOMIX FORCE - Documento solicitado"; //ASSUNTO

  // CORPO DO EMAIL
  $email_conteudo = "<h3 style='font-family: Arial, Helvetica, sans-serif; font-weight: normal;line-height: 25px;'>A sua solicitação de disponibilização de documentação realizada em $cadastro_data foi atendida. \n Os documentos solicitados encontram-se disponíveis para download em nossa plataforma.</h3> \n";
  $email_conteudo .= "<h2 style='font-family: Arial, Helvetica, sans-serif;'>Bomix Force</h2>";

  $headers[] = 'MIME-Version: 1.0';
  $headers[] = 'Content-type: text/html; charset=UTF-8';

  // CABEÇALHO DO EMAIL
  $headers[] = 'To: ' . $email_destinatario;
  $headers[] = 'From: ' . $email_remetente;

  // ENVIA O EMAIL PARA O CLIENTE
  mail($email_destinatario, $email_assunto, nl2br($email_conteudo), implode("\r\n", $headers));

  // ====================================================


  //ENVIA MENSAGEM
  session_start();
  $_SESSION["msg"] = "Documento enviado com sucesso!";

  //VOLTA A PÁGINA ANTERIOR
  header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
}





/**************************************************************************************************************************************
 ********************************************************* EXCLUSÃO *******************************************************************
 **************************************************************************************************************************************/

if (isset($_GET['doc_id'])) {

  $sql = "DELETE FROM sys_tb_documentos	WHERE doc_id = ? ";
  $params = array($_GET["doc_id"]);
  $stmt = sqlsrv_query($conn, $sql, $params);
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  } else {
    echo "Record delete successfully";
  }
  sqlsrv_close($conn);


  //APAGA OS IMAGENS DA TABELA "GALERIA_PATRIMONIOS" E DA PASTA
  array_map('unlink', glob("../upload/documentos/" . $_GET["doc_id"] . "/*.*"));
  rmdir("../upload/documentos/" . $_GET["doc_id"]);

  //VOLTA A PÁGINA ANTERIOR
  header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
}
