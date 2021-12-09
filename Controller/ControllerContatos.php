<?php
include '../conexao/conexao_sqlsrv.php';

// DADOS ENVIADOS PELOS FORMULÁRIOS
$id_cad = md5(uniqid(rand(), true)); // ID PRINCIPAL

$id       = $_POST['id'];
$id_user  = $_POST['id_user'];
$nome     = strtoupper(trim($_POST['nome']));
$endereco = nl2br($_POST['endereco']); //nl2br -> Reconhecer quebra de parágrafo
$email    = trim($_POST['email']);

//CADASTRAR ************************************************************************************

if ($_GET['funcao'] == "cad_contato") {

  // IMPEDE CADASTRO COM LOGIN JÁ CADASTRADO
  $sql = "SELECT co_nome FROM sys_tb_contatos WHERE co_nome = '$nome'";
  $params = array();
  $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
  $stmt = sqlsrv_query($conn, $sql, $params, $options);
  $row_count = sqlsrv_num_rows($stmt);

  if ($row_count >= 1) {
    session_start();
    $_SESSION["erro"] = "Já existe um contato cadastrado com este nome!";
    echo "<script> history.go(-1);</script>";
    return die;
  }

  // CADASTRA DADOS  

  $user_sql = "INSERT INTO sys_tb_contatos (
                                            co_id,
                                            us_fk,
                                            co_data_entrada,
                                            co_nome,
                                            co_endereco,
                                            co_email
                                            ) VALUES (
                                            '$id_cad',
                                            '$id_user',
                                            GETDATE(),
                                            '$nome',
                                            '$endereco',
                                            '$email'
                                            )";

  $stmt = sqlsrv_query($conn, $user_sql);
  if ($stmt) {
    echo "Dados inseridos com sucesso!\n";
  } else {
    echo "Erro no cadastro!\n";
    die(print_r(sqlsrv_errors(), true));
  }

  sqlsrv_free_stmt($stmt);
  sqlsrv_close($conn);

  //ENVIA MENSAGEM
  session_start();
  $_SESSION["msg"] = "Cadastro realizado com sucesso!";

  //VOLTA A PÁGINA ANTERIOR
  header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
}


//EDITAR ************************************************************************************

if ($_GET['funcao'] == "edit_contato") {

  // IMPEDE CADASTRO COM LOGIN JÁ CADASTRADO
  $sql = "SELECT co_nome FROM sys_tb_contatos WHERE co_nome = '$nome' AND co_id != '$id'";
  $params = array();
  $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
  $stmt = sqlsrv_query($conn, $sql, $params, $options);
  $row_count = sqlsrv_num_rows($stmt);

  if ($row_count >= 1) {
    session_start();
    $_SESSION["erro"] = "Já existe um contato cadastrado com este nome!";
    echo "<script> history.go(-1);</script>";
    return die;
  }

  // EDITA DADOS
  $sql = "UPDATE
          sys_tb_contatos
        SET
          co_id           = '$id_user',
          co_data_entrada = GETDATE(),
          co_nome         = '$nome',
          co_endereco     = '$endereco',
          co_email        = '$email'
        WHERE
          co_id = '$id'";
  $params = array($qnty);

  $stmt = sqlsrv_query($conn, $sql, $params);
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  //ENVIA MENSAGEM
  session_start();
  $_SESSION["msg"] = "Registro atualizado com sucesso!";

  //VOLTA A PÁGINA ANTERIOR
  header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
}


//EXCLUIR *****************************************************************************************************

if (isset($_GET['co_id'])) {

  $sql = "DELETE FROM sys_tb_contatos	WHERE co_id = ? ";
  $params = array($_GET["co_id"]);
  $stmt = sqlsrv_query($conn, $sql, $params);
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  } else {
    echo "Record delete successfully";
  }

  sqlsrv_close($conn);
  header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
}
