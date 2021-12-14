<?php
include '../conexao/conexao_sqlsrv.php';

$id = md5(uniqid(rand(), true)); // ID PRINCIPAL

if (isset($_POST["id_user"])) {
  $id_user = $_POST['id_user'];
}

if (isset($_POST["id_nc"])) {
  $id_nc = $_POST['id_nc'];
}

if (isset($_POST["id_us"])) {
  $id_us = $_POST['id_us'];
}

if (isset($_POST["grupo"])) {
  $grupo = $_POST['grupo'];
}

if (isset($_POST["lote"])) {
  $lote = trim($_POST['lote']);
}

if (isset($_POST["nota"])) {
  $nota = trim($_POST['nota']);
}

if (isset($_POST["quantidade"])) {
  $quantidade = $_POST['quantidade'];
}

if (isset($_POST["item"])) {
  $item = $_POST['item'];
}

if (isset($_POST["descricao"])) {
  $descricao = nl2br($_POST['descricao']); // nl2br -> Reconhecer quebra de parágrafo
  $descricao = str_replace("'", "", $descricao); // str_replace -> IMPEDE O CADASTRO DE ASPAS SIMPLES (')
}

if (isset($_POST["arquivo"])) {
  $arquivo = $_POST['arquivo'];
}

if (isset($_POST['status'])) {
  $status = $_POST['status'];
}

if (isset($_POST['email'])) { // Email para enviar aviso
  $email = $_POST['email'];
}

if (isset($_POST['data_entrada'])) { // Data que foi aberta a não conformidade
  $data_entrada = date_create($_POST['data_entrada']);
  $data_entrada = date_format($data_entrada, 'd/m/Y');
}

if (isset($_POST["msg"])) {
  $msg = trim(nl2br($_POST['msg'])); // nl2br -> Reconhecer quebra de parágrafo
  $msg = str_replace("'", "", $msg); // str_replace -> IMPEDE O CADASTRO DE ASPAS SIMPLES (')
}


/**************************************************************************************************************************************
 *************************************************** REGISTRO DE NÃO CONFORMIDADE *****************************************************
 **************************************************************************************************************************************/

if ($_GET['funcao'] == "cad_nao_conforme") {

  //CRIA UMA PASTA COM O NOME DO "ID"
  $dir = "../upload/nc/$id";
  mkdir($dir, 0777);

  if ($_FILES['arquivo']['tmp_name'] != "") {

    $extensao = strtolower(substr($_FILES['arquivo']['name'], -4)); //pega a extensao do arquivo
    $novo_nome = date('Ymd_His') . $extensao; //define o nome do arquivo
    $diretorio = "../upload/nc/$id/"; //define o diretorio para onde enviaremos o arquivo

    move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio . $novo_nome); //EFETUA O UPLOAD

    $user_sql = "INSERT INTO sys_tb_nao_conforme (
                                                  nc_id,
                                                  us_fk,
                                                  nc_data_entrada,
                                                  nc_lote,
                                                  nc_nota,
                                                  nc_quant,
                                                  nc_item,
                                                  nc_descricao,
                                                  nc_status,
                                                  nc_arquivo
                                                  ) VALUES (
                                                  '$id',
                                                  '$id_user',
                                                  GETDATE(),
                                                  '$lote',
                                                  '$nota',
                                                  '$quantidade',
                                                  '$item',
                                                  '$descricao',
                                                  '$status',
                                                  '$novo_nome'
                                                  )";
  } else {

    // CADASTRA DADOS  
    $user_sql = "INSERT INTO sys_tb_nao_conforme (
                                                nc_id,
                                                us_fk,
                                                nc_data_entrada,
                                                nc_lote,
                                                nc_nota,
                                                nc_quant,
                                                nc_item,
                                                nc_descricao,
                                                nc_status
                                                ) VALUES (
                                                '$id',
                                                '$id_user',
                                                GETDATE(),
                                                '$lote',
                                                '$nota',
                                                '$quantidade',
                                                '$item',
                                                '$descricao',
                                                '$status'
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
  $email_destinatario = $email; // EMAIL QUE RECEBERA A MENSAGEM
  $email_reply = "carlos.silveira.bmx@gmail.com";
  $email_assunto = "BOMIX FORCE - Registro de não conformidade"; //ASSUNTO

  // CORPO DO EMAIL
  $email_conteudo = "<h3 style='font-family: Arial, Helvetica, sans-serif; font-weight: normal;line-height: 25px;'>Registramos a sua solicitação de abertura de não conformidade. \n Iniciaremos as investigações internas e retornaremos em breve com o resultado. \n Acompanhe o status da sua reclamação ao lado do seu registro.</h3> \n";
  $email_conteudo .= "<h2 style='font-family: Arial, Helvetica, sans-serif;'>Bomix Force</h2>";
  // $email_conteudo .= "<strong>Lote:</strong>        $lote \n";
  // $email_conteudo .= "<strong>Nota Fiscal:</strong> $nota \n";
  // $email_conteudo .= "<strong>Quantidade:</strong>  $quantidade \n";
  // $email_conteudo .= "<strong>Item:</strong>        $item \n";
  // $email_conteudo .= "<strong>Descrição:</strong>   $descricao \n";

  $headers[] = 'MIME-Version: 1.0';
  $headers[] = 'Content-type: text/html; charset=UTF-8';

  // CABEÇALHO DO EMAIL
  $headers[] = 'To: ' . $email_destinatario;
  $headers[] = 'From: ' . $email_remetente;

  // ENVIA O EMAIL
  mail($email_destinatario, $email_assunto, nl2br($email_conteudo), implode("\r\n", $headers));

  // ====================================================


  //ENVIA MENSAGEM
  session_start();
  $_SESSION["msg"] = "Registro realizado com sucesso!";

  //VOLTA A PÁGINA ANTERIOR
  header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
}





/**************************************************************************************************************************************
 *************************************************** MENSAGEM DE NÃO CONFORMIDADE *****************************************************
 **************************************************************************************************************************************/

if ($_GET['funcao'] == "cad_nao_conforme_msg") {

  if ($_FILES['arquivo']['tmp_name'] != "") {

    $extensao = strtolower(substr($_FILES['arquivo']['name'], -4)); //pega a extensao do arquivo
    $novo_nome = date('Ymd_His') . $extensao; //define o nome do arquivo
    $diretorio = "../upload/nc/$id_nc/"; //define o diretorio para onde enviaremos o arquivo

    move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio . $novo_nome); //EFETUA O UPLOAD

    // CADASTRA DADOS  
    $user_sql = "INSERT INTO sys_tb_nao_conforme_msg (
                                                      nc_fk,
                                                      us_fk,
                                                      us_grupo,
                                                      ncm_msg,
                                                      ncm_arquivo,
                                                      ncm_data_envio
                                                      ) VALUES (
                                                      '$id_nc',
                                                      '$id_us',
                                                      '$grupo',
                                                      '$msg',
                                                      '$novo_nome',
                                                      GETDATE()
                                                      )";
  } else {

    // CADASTRA DADOS  
    $user_sql = "INSERT INTO sys_tb_nao_conforme_msg (
                                                      nc_fk,
                                                      us_fk,
                                                      us_grupo,
                                                      ncm_msg,
                                                      ncm_data_envio
                                                      ) VALUES (
                                                      '$id_nc',
                                                      '$id_us',
                                                      '$grupo',
                                                      '$msg',
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

  // EDITA DADOS
  $sql = "UPDATE sys_tb_nao_conforme SET nc_status = '$status' WHERE nc_id = '$id_nc'";
  $params = array($qnty);

  $stmt = sqlsrv_query($conn, $sql, $params);
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  sqlsrv_free_stmt($stmt);
  sqlsrv_close($conn);



  // QUANDO O COMERCIAL RESPONDER AO CLIENTE, UM EMAIL É DESPARADO INFORMADO AO CLIENTE QUE A SOLICITAÇÃO FOI RESPONDIDA
  if ($status == 'RESPONDIDO') {

    $email_remetente = "carlos.silveira.bmx@gmail.com"; // EMAIL CADASTRADO NO WEBMAIL DO XAMPP

    // CONFIGURAÇÕES
    $email_destinatario = $email; // EMAIL QUE RECEBERA A MENSAGEM
    $email_reply = "carlos.silveira.bmx@gmail.com";
    $email_assunto = "BOMIX FORCE - Retorno da não conformidade"; //ASSUNTO

    // CORPO DO EMAIL
    $email_conteudo = "<h3 style='font-family: Arial, Helvetica, sans-serif; font-weight: normal;line-height: 25px;'>O seu registro de não conformidade aberto em $data_entrada foi respondido. \n O relatório de Não Conformidade encontra-se disponível para download em nossa plataforma. </h3> \n";
    $email_conteudo .= "<h2 style='font-family: Arial, Helvetica, sans-serif;'>Bomix Force</h2>";

    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=UTF-8';

    // CABEÇALHO DO EMAIL
    $headers[] = 'To: ' . $email_destinatario;
    $headers[] = 'From: ' . $email_remetente;

    // ENVIA O EMAIL
    mail($email_destinatario, $email_assunto, nl2br($email_conteudo), implode("\r\n", $headers));

    // ====================================================
  }


  //VOLTA A PÁGINA ANTERIOR
  header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
}








/**************************************************************************************************************************************
 ****************************************************** CONCLUIR ATENDIMENTO **********************************************************
 **************************************************************************************************************************************/

if ($_GET['funcao'] == "concluir_atendimento") {

  $id = $_GET['id'];
  $sql = "UPDATE
          sys_tb_nao_conforme
        SET
          nc_status = 'CONCLUÍDO',
          nc_data_entrada  = GETDATE()
        WHERE
          nc_id = '$id'";
  $params = array($qnty);

  $stmt = sqlsrv_query($conn, $sql, $params);
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  sqlsrv_free_stmt($stmt);
  sqlsrv_close($conn);

  //ENVIA MENSAGEM
  session_start();
  $_SESSION["msg"] = "O atendimento foi concluido com sucesso!";

  //VOLTA A PÁGINA ANTERIOR
  header("Location: ../nao_conforme.php");
  //header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
}






/**************************************************************************************************************************************
 ********************************************************* EXCLUSÃO *******************************************************************
 **************************************************************************************************************************************/

if (isset($_GET['nc_id'])) {

  $sql = "DELETE FROM sys_tb_nao_conforme	WHERE nc_id = ? ";
  $params = array($_GET["nc_id"]);
  $stmt = sqlsrv_query($conn, $sql, $params);
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  } else {
    echo "Record delete successfully";
  }


  $sql = "DELETE FROM sys_tb_nao_conforme_msg	WHERE nc_fk = ? ";
  $params = array($_GET["nc_id"]);
  $stmt = sqlsrv_query($conn, $sql, $params);
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  } else {
    echo "Record delete successfully";
  }

  sqlsrv_close($conn);


  //APAGA OS IMAGENS DA TABELA "GALERIA_PATRIMONIOS" E DA PASTA
  array_map('unlink', glob("../upload/nc/" . $_GET["nc_id"] . "/*.*"));
  rmdir("../upload/nc/" . $_GET["nc_id"]);

  //VOLTA A PÁGINA ANTERIOR
  header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
}
