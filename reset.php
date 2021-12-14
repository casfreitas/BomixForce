<?php include_once 'includes/login/header.php'; ?>

<?php
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (!empty($dados['SendReset'])) {
  $query_usuario = "SELECT * FROM sys_tb_usuarios WHERE us_usuario = :usuario"; //LIMIT 1
  $result_usuario = $conn->prepare($query_usuario);
  $result_usuario->bindParam(':usuario', $dados['usuario'], PDO::PARAM_STR); //, PDO::PARAM_STR -> FORMA PARA QUE SEJA UMA STRING, MAS NÃO É NECESSÁRIO POR
  $result_usuario->execute();

  if (($result_usuario) and ($result_usuario->rowCount() != 0)) {
    $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);

    $senha    = base64_decode($row_usuario['us_senha']);
    $usuario  = $row_usuario['us_usuario'];
    $email    = $row_usuario['us_email'];

    //ENVIA EMAIL PARA O USUÁRIO CADASTRADO
    $email_remetente = "carlos.silveira.bmx@gmail.com"; //EMAIL CADASTRADO NO WEBMAIL DO XAMPP

    //CONFIGURAÇÕES
    $email_destinatario = "$email"; //EMAIL QUE RECEBERA A MENSAGEM
    $email_reply = "$email";
    $email_assunto = "BOMIX FORCE - Dados de acesso recuperados"; //ASSUNTO

    //CORPO DO EMAIL
    @$email_conteudo .= "<strong><h2>Dados de acesso ao Bomix Force:</h2></strong>";
    @$email_conteudo .= "<strong>Usuário:</strong>   $usuario \n";
    @$email_conteudo .= "<strong>Senha:</strong>     $senha \n";
    @$email_conteudo .= "<strong>Email:</strong>     $email \n";


    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=UTF-8';

    //CABEÇALHO DO EMAIL
    $headers[] = 'To: ' . $email_destinatario;
    $headers[] = 'From: ' . $email_remetente;

    //ENVIA O EMAIL
    //mail($email_destinatario, $email_assunto, nl2br($email_conteudo), implode("\r\n", $headers));

    //====================================================

    //ENVIA MENSAGEM
    session_start();
    $_SESSION["confirm"] = "<div class=\"alert alert-success mt-3\" role=\"alert\"><p>Os dados de acesso foram enviados para seu email cadastrado!</p></div>";
    header("Location: index.php");
  } else {
    $_SESSION["msg"] = "<div class=\"alert alert-danger mt-3\" role=\"alert\"><p>O usuário informado não foi encontrado. Entre em contato com o administrador!</p></div>";
  }
}
?>

<section>
  <div class="row align-items-center m-0">
    <div class="col-xl-4 text-center bg_campo_login">

      <?php if (!isset($_GET['funcao'])) { ?>

        <div class="box_reset">
          <form class="form-signin campo_reset g-3 needs-validation" method="post" action="" onkeydown="return event.key != 'Enter';" novalidate>
            <h3>Recuperação de Senha</h3>
            <p class="info">Informe seu usuário no campo abaixo. Caso exista, enviaremos, para seu email, seus dados de acesso.</p>
            <div class="input-group mb-3">
              <span class="input-group-text border icone_imput" id="basic-addon1"><i class="bi bi-person-fill"></i></span>
              <input type="text" class="form-control text-uppercase inputColor" name="usuario" placeholder="Usuário" required>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>
            <div class="text-center">
              <div class="row">
                <div class="col-12"><button type="submit" name="SendReset" class="botao_vasado w-100" value="Acessar">Recuperar</button></div>
                <div class="col-12 mt-3"><a href="index.php" class="botao_cancelar_vasado w-100 d-block">Cancelar</a></div>
              </div>
            </div>
          </form>
        </div>

      <?php } else { ?>

        <?php
        include 'conexao/conexao_sqlsrv.php';
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (!empty($dados['SendResetPass'])) {

          $id = $_SESSION['us_id'];
          @$senha = base64_encode(trim($_POST['senha']));
          @$old_pass = base64_encode(trim($_POST['old_pass']));

          // NOVA SENHA PRECISAR SER DIFERENTE DA AUAL
          if ($old_pass === $senha) {
            $_SESSION['msg'] = "<div class=\"alert alert-danger mt-3\" role=\"alert\"><p>A nova senha precisa ser diferente da atual!</p></div>";
          } else {

            // VALIDA SENHA ATUAL
            $sql = "SELECT us_id, us_senha, us_usuario FROM sys_tb_usuarios WHERE us_id = '$id' AND us_senha <> '$old_pass'";
            $params = array();
            $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
            $stmt = sqlsrv_query($conn, $sql, $params, $options);
            $row_count = sqlsrv_num_rows($stmt);

            if ($row_count >= 1) {
              $_SESSION['msg'] = "<div class=\"alert alert-danger mt-3\" role=\"alert\"><p>Senha atual inválida!</p></div>";
            } else {

              // EDITA OS DADOS
              $sql = "UPDATE sys_tb_usuarios SET us_senha = '$senha', us_last_login = GETDATE(), us_data_entrada = GETDATE() WHERE us_id = '$id'";
              $params = array($qnty);
              $stmt = sqlsrv_query($conn, $sql, $params);
              if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
              }

              //ENVIA EMAIL PARA O USUÁRIO CADASTRADO
              $email_remetente = "carlos.silveira.bmx@gmail.com"; //EMAIL CADASTRADO NO WEBMAIL DO XAMPP

              //CONFIGURAÇÕES
              $email = $_SESSION['us_email'];
              $email_destinatario = "$email"; //EMAIL QUE RECEBERA A MENSAGEM
              $email_reply = "$email";
              $email_assunto = "BOMIX FORCE - Nova senha de acesso"; //ASSUNTO

              //CORPO DO EMAIL
              $usuario = $_SESSION['us_usuario'];
              $senha = base64_decode($senha);
              $email_conteudo .= "<strong><h2>Sua senha foi atualizada!</h2></strong>";
              $email_conteudo .= "<strong>Usuário:</strong>   $usuario \n";
              $email_conteudo .= "<strong>Senha:</strong>     $senha \n";
              $email_conteudo .= "<strong>Email:</strong>     $email \n";


              $headers[] = 'MIME-Version: 1.0';
              $headers[] = 'Content-type: text/html; charset=UTF-8';

              //CABEÇALHO DO EMAIL
              $headers[] = 'To: ' . $email_destinatario;
              $headers[] = 'From: ' . $email_remetente;

              //ENVIA O EMAIL
              mail($email_destinatario, $email_assunto, nl2br($email_conteudo), implode("\r\n", $headers));

              //ENVIA MENSAGEM
              session_start();
              $_SESSION['erro'] = "<div class=\"alert alert-success mt-3\" role=\"alert\"><p>Senha atualizado com sucesso!</p></div>";

              //DIRECIONA PARA A PÁGINA 'SAIR' PARA FECHAR AS SESSIONS.
              header("Location: sair.php");
            }
          }
        }
        ?>

        <div class="box_reset">
          <form class="form-signin campo_reset g-3 needs-validation" method="post" action="" onSubmit="return val_senha(this);" name="valida_senha" onkeydown="return event.key != 'Enter';" novalidate>
            <h3>Alterar Senha</h3>
            <p class="info">Para sua seguraça, solicitamos que redefina sua senha. É preciso que ela seja diferente da atual.</p>
            <div class="input-group mb-3">
              <span class="input-group-text border icone_imput"><i class="bi bi-lock-fill"></i></span>
              <input type="password" class="form-control inputColor" name="old_pass" placeholder="Digite sua senha atual" required>
              <div class="invalid-feedback text-start">Campo obrigatório</div>
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text border icone_imput" id=""><i class="bi bi-lock-fill"></i></span>
              <input type="password" class="form-control inputColor" name="senha" minlength="4" maxlength="8" size="8" placeholder="Nova senha" required>
              <div class="invalid-feedback  text-start">Digite min: 4 / max: 8 caracteres</div>
            </div>
            <div class="input-group mb-2">
              <span class="input-group-text border icone_imput" id=""><i class="bi bi-lock-fill"></i></span>
              <input type="password" class="form-control inputColor" name="rep_senha" minlength="4" maxlength="8" size="8" placeholder="Repita a nova senha" required>
              <div class="invalid-feedback  text-start">Digite min: 4 / max: 8 caracteres</div>
            </div>
            <small class="text-body fw-bolder">Mínimo 4 e máximo 8 caracteres </small>
            <div class="text-center mt-2">
              <div class="row">
                <div class="col-12"><button type="submit" name="SendResetPass" class="botao_vasado w-100" value="Acessar">Alterar Senha</button></div>
                <div class="col-12 mt-3"><a href="index.php" class="botao_cancelar_vasado w-100 d-block">Cancelar</a></div>
              </div>
            </div>
          </form>
        </div>

      <?php } ?>

      <div class="campo_alerta">
        <?php
        if (isset($_SESSION['msg'])) {
          echo $_SESSION['msg'];
          unset($_SESSION['msg']);
        }
        ?>
      </div>

    </div>
    <div class="col-xl-8"></div>
  </div>
</section>


<!-- VALIDA FORMULÁRIO -->
<script src="dist/js/valida_form.js"></script>

<!--VALIDAR SENHA-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
  function val_senha(valida_senha) {
    senha = document.valida_senha.senha.value
    rep_senha = document.valida_senha.rep_senha.value
    if (senha != rep_senha) {

      Swal.fire({
        icon: 'error',
        title: 'Erro encontrado!',
        text: 'As senhas digitadas estão diferentes.',
      })

      return false;
    }
    return true;
  }
</script>

<?php include_once 'includes/login/footer.php'; ?>