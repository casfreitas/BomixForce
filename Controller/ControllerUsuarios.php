<?php
include '../conexao/conexao_sqlsrv.php';


$id_cad = md5(uniqid(rand(), true)); // ID PRINCIPAL

//$senha = base64_encode('1234'); // SENHA PADRÃO

$bytes = openssl_random_pseudo_bytes(4);
$senha = bin2hex($bytes);
$senha = base64_encode($senha);

$nivel = '0';

if (isset($_POST["id"])) {
  $id = $_POST['id'];
}

if (isset($_POST["id_user"])) {
  $id_user = $_POST['id_user'];
}

if (isset($_POST["identificador"])) {
  $identificador = $_POST['identificador'];
}

if (isset($_POST["nome_completo"])) {
  $nome_completo = strtoupper(trim($_POST['nome_completo']));
}

if (isset($_POST["usuario"])) {
  $usuario = strtoupper(trim($_POST['usuario']));
}

if (isset($_POST["email"])) {
  $email = trim($_POST['email']);
}

if (isset($_POST["cnpj"])) {
  $cnpj = $_POST['cnpj'];
}

if (isset($_POST["id_cliente"])) {
  $id_cliente = $_POST['id_cliente'];
}

if (isset($_POST["cliente"])) {
  $cliente = $_POST['cliente'];
}

if (isset($_POST["grupo"])) {
  $grupo = $_POST['grupo'];
}

if (isset($_POST["setor"])) {
  $setor = strtoupper(trim($_POST['setor']));
}

if (isset($_POST["cargo"])) {
  $cargo = strtoupper(trim($_POST['cargo']));
}

if (isset($_POST["acesso"])) {
  foreach ($_POST['acesso'] as $acesso) {
    @$valor_acesso .= $acesso . ",";
  }
}

if (isset($_POST['status'])) {
  $status = '1';
} else {
  $status = '0';
}

// USUÁRIO (LOGIN) NÃO PODE SER VAZIO
if (@$usuario === '') {
  session_start();
  $_SESSION["erro"] = "Usuário não encontrado!";
  echo "<script> history.go(-1);</script>";
  return die;
}





/**************************************************************************************************************************************
 *********************************************** CADASTRAR ADMINISTRADOR / COMERCIAL **************************************************
 **************************************************************************************************************************************/

if ($_GET['funcao'] == "cad_admin" || $_GET['funcao'] == "cad_comercial") {

  // IMPEDE CADASTRO COM LOGIN JÁ CADASTRADO
  $sql = "SELECT us_usuario FROM sys_tb_usuarios WHERE us_usuario = '$usuario'";
  $params = array();
  $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
  $stmt = sqlsrv_query($conn, $sql, $params, $options);
  $row_count = sqlsrv_num_rows($stmt);

  if ($row_count >= 1) {
    session_start();
    $_SESSION["erro"] = "Já existe um usuário cadastrado com esta descrição!";
    echo "<script> history.go(-1);</script>";
    return die;
  }

  // CADASTRA DADOS
  $user_sql = "INSERT INTO sys_tb_usuarios (
                                            us_id,
                                            us_identificador,
                                            us_senha,
                                            us_nome_completo,
                                            us_usuario,
                                            us_email,
                                            us_nivel,
                                            us_status,
                                            us_grupo,
                                            us_data_entrada
                                            ) VALUES (
                                            '$id_cad', 
                                            '$identificador', 
                                            '$senha',
                                            '$nome_completo',
                                            '$usuario',
                                            '$email',
                                            '$nivel',
                                            '$status',
                                            '$grupo',
                                            GETDATE()
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

  // ===========================================================================================

  // ENVIA EMAIL PARA O USUÁRIO APÓS O CADASTRADO
  $email_remetente = "carlos.silveira.bmx@gmail.com"; // EMAIL CADASTRADO NO WEBMAIL DO XAMPP

  // CONFIGURAÇÕES
  $email_destinatario = "$email"; // EMAIL QUE RECEBERA A MENSAGEM
  $email_reply = "$email";
  $email_assunto = "BOMIX FORCE - Seus dados de acesso"; // ASSUNTO

  $papel = array('1' => 'ADMINISTRADOR', '2' => 'COMERCIAL', '3' => 'CLIENTE', '4' => 'USUÁRIO');

  // CORPO DO EMAIL
  $senha = base64_decode($senha);
  $email_conteudo  = "<strong><h2>Dados de acesso ao Bomix Force:</h2></strong>";
  $email_conteudo .= "<strong>Nome:</strong>      $nome_completo \n";
  $email_conteudo .= "<strong>Usuário:</strong>   $usuario \n";
  $email_conteudo .= "<strong>Senha:</strong>     $senha \n";
  $email_conteudo .= "<strong>Email:</strong>     $email \n";
  $email_conteudo .= "<strong>Grupo:</strong>     $papel[$grupo] \n";
  $email_conteudo .= "<strong>Endereço de acesso:</strong> https://192.168.254.74/bomixforce \n";

  $headers[] = 'MIME-Version: 1.0';
  $headers[] = 'Content-type: text/html; charset=UTF-8';

  // CABEÇALHO DO EMAIL
  $headers[] = 'To: ' . $email_destinatario;
  $headers[] = 'From: ' . $email_remetente;

  // ENVIA O EMAIL
  mail($email_destinatario, $email_assunto, nl2br($email_conteudo), implode("\r\n", $headers));

  // ===========================================================================================

  // ENVIA MENSAGEM
  session_start();
  $_SESSION["msg"] = "Cadastro realizado com sucesso!";

  // VOLTA A PÁGINA ANTERIOR
  header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
}






/**************************************************************************************************************************************
 ******************************************************* CADASTRAR CLIENTE ************************************************************
 **************************************************************************************************************************************/

if ($_GET['funcao'] == "cad_cliente") {

  // CAPTURA O 'RECNO' DO CLIENTE DE ACORDO COM SEU CNPJ(USUÁRIO)
  $sql = "SELECT Recno, NomeFantasia, CNPJ FROM BomixBi.dbo.Fat_TB_Cliente WHERE CNPJ = '$usuario'";
  $stmt_uc = sqlsrv_query($conn, $sql);
  while ($row = sqlsrv_fetch_array($stmt_uc, SQLSRV_FETCH_ASSOC)) {
    $recno   = str_pad($row['Recno'], 6, '0', STR_PAD_LEFT); //CADASTRA 6 CARACTERES COM '0' A ESQUERDA
    $cliente = $row['NomeFantasia'];
  }
  sqlsrv_free_stmt($stmt_uc);

  // IMPEDE CADASTRO COM LOGIN JÁ CADASTRADO
  $sql = "SELECT us_usuario FROM sys_tb_usuarios WHERE us_usuario = '$usuario'";
  $params = array();
  $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
  $stmt = sqlsrv_query($conn, $sql, $params, $options);
  $row_count = sqlsrv_num_rows($stmt);

  if ($row_count >= 1) {
    session_start();
    $_SESSION["erro"] = "Já existe um usuário cadastrado com esta descrição!";
    echo "<script> history.go(-1);</script>";
    return die;
  }

  // ↑_ CADASTRA DADOS  
  $user_sql = "INSERT INTO sys_tb_usuarios (
                                            us_id,
                                            us_recno,
                                            us_identificador,
                                            us_cliente,
                                            us_senha,
                                            us_nome_completo,
                                            us_usuario,
                                            us_email,
                                            us_nivel,
                                            us_status,
                                            us_grupo,
                                            us_data_entrada
                                            ) VALUES (
                                            '$id_cad',
                                            '$recno',
                                            '$usuario',
                                            '$cliente',
                                            '$senha',
                                            '$nome_completo',
                                            '$usuario',
                                            '$email',
                                            '$nivel',
                                            '$status',
                                            '$grupo',
                                            GETDATE()
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


  //ENVIA EMAIL PARA O USUÁRIO APÓS O CADASTRADO
  $email_remetente = "carlos.silveira.bmx@gmail.com"; //EMAIL CADASTRADO NO WEBMAIL DO XAMPP

  //CONFIGURAÇÕES
  $email_destinatario = "$email"; //EMAIL QUE RECEBERA A MENSAGEM
  $email_reply = "$email";
  $email_assunto = "BOMIX FORCE - Novo usuário cadastrado"; //ASSUNTO

  $papel = array('1' => 'ADMINISTRADOR', '2' => 'COMERCIAL', '3' => 'CLIENTE', '4' => 'USUÁRIO');

  //CORPO DO EMAIL
  $senha = base64_decode($senha);
  $email_conteudo  = "<strong><h2>Dados de acesso ao Bomix Force:</h2></strong>";
  $email_conteudo .= "<strong>Cliente:</strong>   $nome_completo \n";
  $email_conteudo .= "<strong>Usuário:</strong>   $usuario \n";
  $email_conteudo .= "<strong>Senha:</strong>     $senha \n";
  $email_conteudo .= "<strong>Email:</strong>     $email \n";
  $email_conteudo .= "<strong>Endereço de acesso:</strong> https://192.168.254.74/bomixforce \n";

  $headers[] = 'MIME-Version: 1.0';
  $headers[] = 'Content-type: text/html; charset=UTF-8';

  //CABEÇALHO DO EMAIL
  $headers[] = 'To: ' . $email_destinatario;
  $headers[] = 'From: ' . $email_remetente;

  //ENVIA O EMAIL
  mail($email_destinatario, $email_assunto, nl2br($email_conteudo), implode("\r\n", $headers));

  //====================================================

  //ENVIA MENSAGEM
  session_start();
  $_SESSION["msg"] = "Cadastro realizado com sucesso!";

  //VOLTA A PÁGINA ANTERIOR
  header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
}






/**************************************************************************************************************************************
 *************************************************** CADASTRAR USUARIO DO CLIENTE *****************************************************
 **************************************************************************************************************************************/

if ($_GET['funcao'] == "cadUserCliente") {

  // CAPTURA O 'RECNO' DO CLIENTE DE ACORDO COM SEU CNPJ(USUÁRIO)
  $sql = "SELECT Recno, NomeFantasia, CNPJ FROM BomixBi.dbo.Fat_TB_Cliente WHERE CNPJ = '$cnpj'";
  $stmt_uc = sqlsrv_query($conn, $sql);
  while ($row = sqlsrv_fetch_array($stmt_uc, SQLSRV_FETCH_ASSOC)) {
    $recno   = str_pad($row['Recno'], 6, '0', STR_PAD_LEFT); //CADASTRA 6 CARACTERES COM '0' A ESQUERDA
    $cliente = $row['NomeFantasia'];
  }
  sqlsrv_free_stmt($stmt_uc);

  // ↑_ IMPEDE QUE O CLIENTE CADASTRE MAIS DE 3 USUÁRIOS
  $sql_u3 = "SELECT ue_recno FROM sys_tb_usuario_empresa WHERE ue_recno = '$recno'";
  $params_u3 = array();
  $options_u3 =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
  $stmt_u3 = sqlsrv_query($conn, $sql_u3, $params_u3, $options_u3);
  $row_count = sqlsrv_num_rows($stmt_u3);

  if ($row_count >= 3) {
    session_start();
    $_SESSION["erro"] = "O limite de 3 usuários cadastrados, para este cliente, foi atingido!";
    echo "<script> history.go(-1);</script>";
    return die;
  }

  // IMPEDE CADASTRO COM LOGIN JÁ CADASTRADO
  $sql = "SELECT us_usuario FROM sys_tb_usuarios WHERE us_usuario = '$usuario' AND us_id != '$id'";
  $params = array();
  $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
  $stmt = sqlsrv_query($conn, $sql, $params, $options);
  $row_count = sqlsrv_num_rows($stmt);

  if ($row_count >= 1) {
    session_start();
    $_SESSION["erro"] = "Já existe um usuário cadastrado com esta descrição!";
    echo "<script> history.go(-1);</script>";
    return die;
  }

  // ↑_ CADASTRA DADOS  
  $user_sql = "INSERT INTO sys_tb_usuarios (
                                            us_id,
                                            us_recno,
                                            us_identificador,
                                            us_cliente,
                                            us_senha,
                                            us_nome_completo,
                                            us_usuario,
                                            us_email,
                                            us_nivel,
                                            us_status,
                                            us_grupo,
                                            us_data_entrada
                                            ) VALUES (
                                            '$id_cad',
                                            '$recno',
                                            '$cnpj',
                                            '$cliente',
                                            '$senha',
                                            '$nome_completo',
                                            '$usuario',
                                            '$email',
                                            '$nivel',
                                            '$status',
                                            '$grupo',
                                            GETDATE()
                                            )";

  $stmt = sqlsrv_query($conn, $user_sql);
  if ($stmt) {
    echo "Dados inseridos com sucesso!\n";
  } else {
    echo "Erro no cadastro!\n";
    die(print_r(sqlsrv_errors(), true));
  }

  // ↑_ CADASTRA DADOS 
  $emp_sql = "INSERT INTO sys_tb_usuario_empresa (
                                                    us_fk,
                                                    ue_setor,
                                                    ue_cargo,
                                                    ue_recno,
                                                    ue_cliente,
                                                    ue_cnpj,
                                                    ue_permissao
                                                    ) VALUES (
                                                    '$id_cad',
                                                    '$setor',
                                                    '$cargo',
                                                    '$recno',
                                                    '$cliente',
                                                    '$cnpj',
                                                    '$valor_acesso'
                                                    )";

  $stmt = sqlsrv_query($conn, $emp_sql);
  if ($stmt) {
    echo "Dados inseridos com sucesso!\n";
  } else {
    echo "Erro no cadastro!\n";
    die(print_r(sqlsrv_errors(), true));
  }


  // CHECKBOX
  // $emp_sql = "INSERT INTO sys_tb_usuario_permissao ( up_user_emp_fk, up_permissao ) VALUES ( '$id_cad', '$valor_acesso' )";
  // $stmt = sqlsrv_query($conn, $emp_sql);
  // if ($stmt) {
  //   echo "Dados inseridos com sucesso!\n";
  // } else {
  //   echo "Erro no cadastro!\n";
  //   die(print_r(sqlsrv_errors(), true));
  // }


  // foreach ($_POST['acesso'] as $value) {
  //   $emp_sql = "INSERT INTO sys_tb_usuario_permissao ( up_user_emp_fk, up_permissao ) VALUES ( '$id_cad', '$value' )";
  //   $stmt = sqlsrv_query($conn, $emp_sql);
  //   if ($stmt) {
  //     echo "Dados inseridos com sucesso!\n";
  //   } else {
  //     echo "Erro no cadastro!\n";
  //     die(print_r(sqlsrv_errors(), true));
  //   }
  // }

  sqlsrv_free_stmt($stmt);
  sqlsrv_close($conn);

  //ENVIA EMAIL PARA O USUÁRIO CADASTRADO
  $email_remetente = "carlos.silveira.bmx@gmail.com"; //EMAIL CADASTRADO NO WEBMAIL DO XAMPP
  $email_comercial = "casfreitas@yahoo.com.br"; //EMAIL QUE RECEBERAR ALERTA PARA ATIVAÇÃO DO USUÁRIO

  $email_destinatario = "$email"; //EMAIL QUE RECEBERA A MENSAGEM
  $email_reply = "$email";
  $email_assunto = "BOMIX FORCE - Novo usuário cadastrado"; //ASSUNTO

  //CORPO DO EMAIL
  $senha = base64_decode($senha);
  $email_conteudo  = "<strong><h2>Dados de acesso ao Bomix Force:</h2></strong>";
  $email_conteudo .= "<strong>Nome:</strong>      $nome_completo \n";
  $email_conteudo .= "<strong>Usuário:</strong>   $usuario \n";
  $email_conteudo .= "<strong>Senha:</strong>     $senha \n";
  $email_conteudo .= "<strong>Email:</strong>     $email \n";
  $email_conteudo .= "<strong>Setor:</strong>     $setor \n";
  $email_conteudo .= "<strong>Cargo:</strong>     $cargo \n";
  $email_conteudo .= "<strong>Cliente:</strong>   $cliente \n";
  $email_conteudo .= "<strong>Endereço de acesso:</strong> https://192.168.254.74/bomixforce \n";

  $headers[] = 'MIME-Version: 1.0';
  $headers[] = 'Content-type: text/html; charset=UTF-8';

  //CABEÇALHO DO EMAIL
  $headers[] = 'To: ' . $email_destinatario;
  $headers[] = 'From: ' . $email_remetente;

  //ENVIA O EMAIL
  mail($email_destinatario, $email_assunto, nl2br($email_conteudo), implode("\r\n", $headers));

  //===============================================================================================

  $email_destinatario_comercial = "$email_comercial"; //EMAIL QUE RECEBERA O ALERTA DE ATIVAÇÃO DO USUARIO
  $email_reply = "$email_comercial";
  $email_assunto_comercial = "BOMIX FORCE - Ativação de usuário"; //ASSUNTO

  //CORPO DO EMAIL
  $email_conteudo_comercial  = "<strong><h2>Ativação de usuário:</h2></strong>";
  $email_conteudo_comercial .= "<strong>Nome:</strong>      $nome_completo \n";
  $email_conteudo_comercial .= "<strong>Usuário:</strong>   $usuario \n";
  $email_conteudo_comercial .= "<strong>Email:</strong>     $email \n";
  $email_conteudo_comercial .= "<strong>Cliente:</strong>   $cliente \n";
  $email_conteudo .= "<strong>Endereço de acesso:</strong> https://192.168.254.74/bomixforce \n";

  $headers_comercial[] = 'MIME-Version: 1.0';
  $headers_comercial[] = 'Content-type: text/html; charset=UTF-8';

  //CABEÇALHO DO EMAIL
  $headers_comercial[] = 'To: ' . $email_destinatario_comercial;
  $headers_comercial[] = 'From: ' . $email_remetente;

  //ENVIA O EMAIL
  mail($email_destinatario_comercial, $email_assunto_comercial, nl2br($email_conteudo_comercial), implode("\r\n", $headers_comercial));

  //ENVIA MENSAGEM
  session_start();
  $_SESSION["msg"] = "Cadastro realizado com sucesso!";

  //VOLTA A PÁGINA ANTERIOR
  header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
}








/**************************************************************************************************************************************
 ***************************************************** EDITAR ADMINISTRADOR ***********************************************************
 **************************************************************************************************************************************/

if ($_GET['funcao'] == "edit_admin") {

  // IMPEDE CADASTRO COM LOGIN JÁ CADASTRADO
  $sql = "SELECT us_usuario FROM sys_tb_usuarios WHERE us_usuario = '$usuario' AND us_id != '$id'";
  $params = array();
  $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
  $stmt = sqlsrv_query($conn, $sql, $params, $options);
  $row_count = sqlsrv_num_rows($stmt);

  if ($row_count >= 1) {
    session_start();
    $_SESSION["erro"] = "Já existe um usuário cadastrado com esta descrição!";
    echo "<script> history.go(-1);</script>";
    return die;
  }

  // EDITA DADOS
  $sql = "UPDATE
          sys_tb_usuarios
        SET
          --us_nome_completo = '$nome_completo',
          --us_usuario       = '$usuario',
          us_email         = '$email',
          us_nivel         = '$nivel',
          us_status        = '$status',
          us_grupo         = '$grupo',
          us_data_entrada  = GETDATE()
        WHERE
          us_id = '$id'";
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









/**************************************************************************************************************************************
 ******************************************************* EDITAR COMERCIAL *************************************************************
 **************************************************************************************************************************************/

if ($_GET['funcao'] == "edit_comer") {

  // IMPEDE CADASTRO COM LOGIN JÁ CADASTRADO
  $sql = "SELECT us_usuario FROM sys_tb_usuarios WHERE us_usuario = '$usuario' AND us_id != '$id'";
  $params = array();
  $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
  $stmt = sqlsrv_query($conn, $sql, $params, $options);
  $row_count = sqlsrv_num_rows($stmt);

  if ($row_count >= 1) {
    session_start();
    $_SESSION["erro"] = "Já existe um usuário cadastrado com esta descrição!";
    echo "<script> history.go(-1);</script>";
    return die;
  }

  // EDITA DADOS
  $sql = "UPDATE
            sys_tb_usuarios
          SET
            --us_nome_completo = '$nome_completo',
            --us_usuario       = '$usuario',
            us_email         = '$email',
            us_nivel         = '$nivel',
            us_status        = '$status',
            us_grupo         = '$grupo',
            us_data_entrada  = GETDATE()
          WHERE
            us_id = '$id'";
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








/**************************************************************************************************************************************
 ********************************************************* EDITAR CLIENTE *************************************************************
 **************************************************************************************************************************************/

if ($_GET['funcao'] == "edit_cliente") {

  // IMPEDE CADASTRO COM LOGIN JÁ CADASTRADO
  $sql = "SELECT us_usuario FROM sys_tb_usuarios WHERE us_usuario = '$usuario' AND us_id != '$id'";
  $params = array();
  $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
  $stmt = sqlsrv_query($conn, $sql, $params, $options);
  $row_count = sqlsrv_num_rows($stmt);

  if ($row_count >= 1) {
    session_start();
    $_SESSION["erro"] = "Já existe um usuário cadastrado com esta descrição!";
    echo "<script> history.go(-1);</script>";
    return die;
  }

  // O CLIENTE NÃO PODE SER ALTERADO ENQUANTO HOUVER USUÁRIOS ATRELADOS A ELE
  $sql = "SELECT ue_cnpj FROM sys_tb_usuario_empresa WHERE ue_cnpj = '$usuario'";
  $params = array();
  $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
  $stmt = sqlsrv_query($conn, $sql, $params, $options);
  $row_count = sqlsrv_num_rows($stmt);

  if ($row_count > 0) {
    session_start();
    $_SESSION["erro"] = "O cliente não pode ser editado enquanto houver usuários atrelados a ele!";
    echo "<script> history.go(-1);</script>";
    return die;
  }

  // EDITA DADOS
  $sql = "UPDATE
          sys_tb_usuarios
        SET
          --us_last_login    = GETDATE(),
          --us_nome_completo = '$nome_completo',
          --us_usuario       = '$usuario',
          us_email         = '$email',
          us_nivel         = '$nivel',
          us_status        = '$status',
          us_data_entrada  = GETDATE()
        WHERE
          us_id = '$id'";
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








/**************************************************************************************************************************************
 **************************************************** EDITAR USUÁRIO DO CLIENTE *******************************************************
 **************************************************************************************************************************************/

if ($_GET['funcao'] == "editUserCliente") {

  // IMPEDE CADASTRO COM LOGIN JÁ CADASTRADO
  @$sql = "SELECT us_usuario FROM sys_tb_usuarios WHERE us_usuario = '$usuario' AND us_id != '$id'";
  $params = array();
  $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
  $stmt = sqlsrv_query($conn, $sql, $params, $options);
  $row_count = sqlsrv_num_rows($stmt);

  if ($row_count >= 1) {
    session_start();
    $_SESSION["erro"] = "Já existe um usuário cadastrado com esta descrição!";
    echo "<script> history.go(-1);</script>";
    return die;
  }

  // CAPTURA O 'RECNO' DO CLIENTE DE ACORDO COM SEU CNPJ(USUÁRIO)
  $sql = "SELECT Recno, NomeFantasia, CNPJ FROM BomixBi.dbo.Fat_TB_Cliente WHERE CNPJ = '$cnpj'";
  $stmt_uc = sqlsrv_query($conn, $sql);
  while ($row = sqlsrv_fetch_array($stmt_uc, SQLSRV_FETCH_ASSOC)) {
    $recno   = str_pad($row['Recno'], 6, '0', STR_PAD_LEFT); //CADASTRA 6 CARACTERES COM '0' A ESQUERDA
    $cliente = $row['NomeFantasia'];
  }
  sqlsrv_free_stmt($stmt_uc);

  // IMPEDE QUE O CLIENTE CADASTRE MAIS DE 3 USUÁRIOS
  $sql_u3 = "SELECT ue_recno FROM sys_tb_usuario_empresa WHERE ue_recno = '$recno' AND us_fk != '$id'";
  $params_u3 = array();
  $options_u3 =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
  $stmt_u3 = sqlsrv_query($conn, $sql_u3, $params_u3, $options_u3);
  $row_count = sqlsrv_num_rows($stmt_u3);

  if ($row_count >= 3) {
    session_start();
    $_SESSION["erro"] = "O limite de 3 usuários cadastrados, para este cliente, foi atingido!";
    echo "<script> history.go(-1);</script>";
    return die;
  }

  // EDITA DADOS
  $sql = "UPDATE
            sys_tb_usuarios
          SET
            us_recno         = '$recno',
            us_identificador  = '$cnpj',
            us_cliente       = '$cliente',
            us_email         = '$email',
            us_nivel         = '$nivel',
            us_data_entrada  = GETDATE()
          WHERE
            us_id = '$id'";
  $params = array($qnty);

  $stmt = sqlsrv_query($conn, $sql, $params);
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  // EDITA DADOS
  $sql = "UPDATE
            sys_tb_usuario_empresa
          SET
            ue_setor         = '$setor',
            ue_cargo         = '$cargo',
            ue_recno         = '$recno',
            ue_cliente       = '$cliente',
            ue_cnpj          = '$cnpj'
          WHERE
            us_fk = '$id'";
  $params = array($qnty);

  $stmt = sqlsrv_query($conn, $sql, $params);
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  // CHECKBOX
  $sql = "UPDATE
            sys_tb_usuario_empresa
          SET
            ue_permissao = '$valor_acesso'
          WHERE
            us_fk = '$id'";
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







/**************************************************************************************************************************************
 *********************************** EDITAR USUÁRIO DO CLIENTE PELO ADMINISTRADOR MASTER **********************************************
 **************************************************************************************************************************************/

if ($_GET['funcao'] == "editUserClienteAdmin") {

  // IMPEDE CADASTRO COM LOGIN JÁ CADASTRADO
  @$sql = "SELECT us_usuario FROM sys_tb_usuarios WHERE us_usuario = '$usuario' AND us_id != '$id'";
  $params = array();
  $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
  $stmt = sqlsrv_query($conn, $sql, $params, $options);
  $row_count = sqlsrv_num_rows($stmt);

  if ($row_count >= 1) {
    session_start();
    $_SESSION["erro"] = "Já existe um usuário cadastrado com esta descrição!";
    echo "<script> history.go(-1);</script>";
    return die;
  }

  // CAPTURA O 'RECNO' DO CLIENTE DE ACORDO COM SEU CNPJ(USUÁRIO)
  $sql = "SELECT Recno, NomeFantasia, CNPJ FROM BomixBi.dbo.Fat_TB_Cliente WHERE CNPJ = '$cnpj'";
  $stmt_uc = sqlsrv_query($conn, $sql);
  while ($row = sqlsrv_fetch_array($stmt_uc, SQLSRV_FETCH_ASSOC)) {
    $recno   = str_pad($row['Recno'], 6, '0', STR_PAD_LEFT); //CADASTRA 6 CARACTERES COM '0' A ESQUERDA
    $cliente = $row['NomeFantasia'];
  }
  sqlsrv_free_stmt($stmt_uc);

  // IMPEDE QUE O CLIENTE CADASTRE MAIS DE 3 USUÁRIOS
  $sql_u3 = "SELECT ue_recno FROM sys_tb_usuario_empresa WHERE ue_recno = '$recno' AND us_fk != '$id'";
  $params_u3 = array();
  $options_u3 =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
  $stmt_u3 = sqlsrv_query($conn, $sql_u3, $params_u3, $options_u3);
  $row_count = sqlsrv_num_rows($stmt_u3);

  if ($row_count >= 3) {
    session_start();
    $_SESSION["erro"] = "O limite de 3 usuários cadastrados, para este cliente, foi atingido!";
    echo "<script> history.go(-1);</script>";
    return die;
  }

  // EDITA DADOS
  $sql = "UPDATE
            sys_tb_usuarios
          SET
            us_recno         = '$recno',
            us_identificador  = '$cnpj',
            us_cliente       = '$cliente',
            us_email         = '$email',
            us_nivel         = '$nivel',
            us_status        = '$status',
            us_data_entrada  = GETDATE()
          WHERE
            us_id = '$id'";
  $params = array($qnty);

  $stmt = sqlsrv_query($conn, $sql, $params);
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  // EDITA DADOS
  $sql = "UPDATE
            sys_tb_usuario_empresa
          SET
            ue_setor         = '$setor',
            ue_cargo         = '$cargo',
            ue_recno         = '$recno',
            ue_cliente       = '$cliente',
            ue_cnpj          = '$cnpj'
          WHERE
            us_fk = '$id'";
  $params = array($qnty);

  $stmt = sqlsrv_query($conn, $sql, $params);
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  // CHECKBOX
  $sql = "UPDATE
            sys_tb_usuario_empresa
          SET
            ue_permissao = '$valor_acesso'
          WHERE
            us_fk = '$id'";
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





/**************************************************************************************************************************************
 ********************************************* EDITAR USUÁRIO DO CLIENTE - STATUS *******************************************************
 **************************************************************************************************************************************/

if ($_GET['funcao'] == "editUserClienteStatus") {

  // EDITA DADOS
  $sql = "UPDATE
            sys_tb_usuarios
          SET
            us_status = '$status'
          WHERE
            us_id = '$id'";
  $params = array($qnty);

  $stmt = sqlsrv_query($conn, $sql, $params);
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  //ENVIA MENSAGEM
  session_start();
  $_SESSION["msg"] = "Status atualizado com sucesso!";

  //VOLTA A PÁGINA ANTERIOR
  header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
}







/**************************************************************************************************************************************
 ********************************************************** EDITAR PERFIL *************************************************************
 **************************************************************************************************************************************/

if ($_GET['funcao'] == "edit_perfil") {

  // EDITA DADOS
  $sql = "UPDATE
          sys_tb_usuarios
        SET
          --us_last_login    = GETDATE(),
          us_nome_completo = '$nome_completo',
          us_email         = '$email',
          us_data_entrada  = GETDATE()
        WHERE
          us_id = '$id'";
  $params = array($qnty);

  $stmt = sqlsrv_query($conn, $sql, $params);
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  //ENVIA MENSAGEM
  session_start();
  $_SESSION["msg"] = "Perfil atualizado com sucesso!";

  //VOLTA A PÁGINA ANTERIOR
  header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
}









/**************************************************************************************************************************************
 ********************************************************** EDITAR SENHA **************************************************************
 **************************************************************************************************************************************/

if ($_GET['funcao'] == "edit_senha") {

  $senha = base64_encode(trim($_POST['senha']));

  // EDITA DADOS
  $sql = "UPDATE
          sys_tb_usuarios
        SET
          us_senha        = '$senha',
          us_data_entrada = GETDATE()
        WHERE
          us_id = '$id'";
  $params = array($qnty);

  $stmt = sqlsrv_query($conn, $sql, $params);
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  //ENVIA MENSAGEM
  session_start();
  $_SESSION["msg"] = "Senha atualizado com sucesso!";

  //VOLTA A PÁGINA ANTERIOR
  header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
}









/**************************************************************************************************************************************
 ******************************************************** PRIMEIRA SENHA **************************************************************
 **************************************************************************************************************************************/

if ($_GET['funcao'] == "primeira_senha") {

  session_start();
  $id = $_SESSION['us_id'];
  $senha = base64_encode(trim($_POST['senha']));

  // EDITA DADOS
  $sql = "UPDATE
          sys_tb_usuarios
        SET
          us_senha        = '$senha',
          us_data_entrada = GETDATE()
        WHERE
          us_id = '$id'";
  $params = array($qnty);

  $stmt = sqlsrv_query($conn, $sql, $params);
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  //ENVIA MENSAGEM
  session_start();
  $_SESSION['msg'] = "<div class=\"alert alert-success mt-3\" role=\"alert\"><p>Senha atualizado com sucesso!</p></div>";

  //VOLTA A PÁGINA ANTERIOR
  header("Location: ../sair.php");
  //header("Location: ../index.php");
}










/**************************************************************************************************************************************
 ********************************************************* EXCLUSÃO *******************************************************************
 **************************************************************************************************************************************/

if (isset($_GET['us_id'])) {

  //EXCLUI USUÁRIO
  $sql = "DELETE FROM sys_tb_usuarios	WHERE us_id = ? ";
  $params = array($_GET["us_id"]);
  $stmt = sqlsrv_query($conn, $sql, $params);
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  } else {
    echo "Record delete successfully";
  }

  //EXCLUI USUÁRIO DO CLIENTE COM O CNPJ ENVIADO
  $sql = "DELETE FROM sys_tb_usuarios	WHERE us_identificador = ? ";
  $params = array($_GET["cnpj"]);
  $stmt = sqlsrv_query($conn, $sql, $params);
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  } else {
    echo "Record delete successfully";
  }

  //EXCLUI USUÁRIO DO CLIENTE COM O CNPJ ENVIADO DA TABELA USUARIO EMPRESA
  $sql_ue = "DELETE FROM sys_tb_usuario_empresa WHERE ue_cnpj = ? ";
  $params_ue = array($_GET["cnpj"]);
  $stmt_ue = sqlsrv_query($conn, $sql_ue, $params_ue);
  if ($stmt_ue === false) {
    die(print_r(sqlsrv_errors(), true));
  } else {
    echo "Record delete successfully";
  }


  $sql_ue = "DELETE FROM sys_tb_usuario_empresa WHERE us_fk = ? ";
  $params_ue = array($_GET["us_id"]);
  $stmt_ue = sqlsrv_query($conn, $sql_ue, $params_ue);
  if ($stmt_ue === false) {
    die(print_r(sqlsrv_errors(), true));
  } else {
    echo "Record delete successfully";
  }

  sqlsrv_close($conn);
  header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
}
