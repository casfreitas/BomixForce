<?php include_once 'includes/login/header.php'; ?>

<?php
// ACESSO APENAS DE SEGUNDA A SEXTA ENTRE 07:30 E 17:30
$diasemana = array('Domingo', 'Segunda', 'Terca', 'Quarta', 'Quinta', 'Sexta', 'Sabado'); // ARRAY COM OS DIAS DA SEMANA
$data = date('Y-m-d'); //AQUI PODEMOS USAR A DATA ATUAL OU QUALQUER OUTRA DATA DO FORMATO ANO-MÊS-DIA
$diasemana_numero = date('w', strtotime($data)); // VARIAVEL QUE RECEBE O DIA DA SEMANA (0 = Domingo, 1 = Segunda ...)
$dia = $diasemana[$diasemana_numero]; // EXIBE O DIA DA SEMANA COM O ARRAY

date_default_timezone_set('America/Sao_Paulo');

$hora = strtotime(date('H:i')); // HORA ATUAL
$abre = strtotime('07:30');
$fecha = strtotime('17:30');

/////////////////////////

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (!empty($dados['SendLogin'])) {
  $query_usuario = "SELECT * FROM sys_tb_usuarios WHERE us_usuario = :usuario"; //LIMIT 1
  $result_usuario = $conn->prepare($query_usuario);
  $result_usuario->bindParam(':usuario', $dados['usuario'], PDO::PARAM_STR); //, PDO::PARAM_STR -> FORMA PARA QUE SEJA UMA STRING, MAS NÃO É NECESSÁRIO POR
  $result_usuario->execute();

  if (($result_usuario) and ($result_usuario->rowCount() != 0)) {
    $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);

    if ($row_usuario['us_status'] != 0) { // SE O STATUS DO USUARIO FOR 'INATIVO', NÃO PODE LOGAR

      if ($dados['usuario'] == $row_usuario['us_usuario'] && $dados['senha'] == base64_decode($row_usuario['us_senha']) && (empty($row_usuario['us_last_login']))) { // SE O USUARIO NUNCA LOGOU, PEDE PARA CRIAR UMA NOVA SENHA
        $_SESSION['us_id']    = $row_usuario['us_id'];
        $_SESSION['us_senha'] = $row_usuario['us_senha'];
        $_SESSION['us_usuario'] = $row_usuario['us_usuario'];
        $_SESSION['us_email'] = $row_usuario['us_email'];
        header("Location: reset.php?funcao=reset");
      } else {

        if ($row_usuario['us_grupo'] != 1 && $row_usuario['us_grupo'] != 2) { // APENAS ADMIN E COMERCIAL TEM ACESSO A QUALQUER DIA E HORA

          // COMPARA AS DATAS DE HOJE E DATA DO ULTIMO LOGIN
          $hoje = date('Y-m-d');
          $data =  date('Y-m-d', strtotime($row_usuario['us_last_login']));
          $dif = strtotime($hoje) - strtotime($data);
          $time_login = ($dif / 86400); //OBS: 86400 = 60 * 60 * 24 (um dia)

          if ($row_usuario['us_last_login'] <> '' && $time_login > 90) { // APÓS 90 DIAS A CONTA SERÁ DESATIVADA

            $_SESSION['msg'] = "<div class=\"alert alert-warning mt-3\" role=\"alert\"><p>Por falta de acesso, sua conta foi desativada. Entre em contato com o administrador!</p></div>";

            // ATUALIZA A DATA DE LOGIN DO USUARIO QUE TENTOU LOGAR E DESATIVA SEU STATUS
            include 'conexao/conexao_sqlsrv.php';
            $id_user = $row_usuario['us_id'];
            $sql = "UPDATE sys_tb_usuarios SET us_status = 0, us_last_login = GETDATE() WHERE us_id = '$id_user'";
            @$params = array($qnty);
            $stmt = sqlsrv_query($conn, $sql, $params);
            if ($stmt === false) {
              die(print_r(sqlsrv_errors(), true));
            }
          } else {
            if ($hora < $abre || $hora > $fecha) {
              $_SESSION['msg'] = "<div class=\"alert alert-warning mt-3\" role=\"alert\"><p>Sistema disponível de segunda a sexta das 07h:30m às 17h:30m!</p></div>";
            } else {
              if ($dia == 'Sabado' || $dia == 'Domingo') {
                $_SESSION['msg'] = "<div class=\"alert alert-warning mt-3\" role=\"alert\"><p>Sistema disponível de segunda a sexta das 07h:30m às 17h:30m!</p></div>";
              } else {

                // SOLICITA QUE A SENHA SEJA RESETADA APÓS 90 DIAS
                $hoje = date('Y-m-d');
                $data_entrada = $row_usuario['us_data_entrada'];
                $sub = strtotime($hoje) - strtotime($data_entrada);
                $resetPass = ($sub / 86400);

                if ($dados['usuario'] == $row_usuario['us_usuario'] && $dados['senha'] == base64_decode($row_usuario['us_senha']) && $resetPass > 90) {
                  $_SESSION['us_id']      = $row_usuario['us_id'];
                  $_SESSION['us_senha']   = $row_usuario['us_senha'];
                  $_SESSION['us_usuario'] = $row_usuario['us_usuario'];
                  $_SESSION['us_email']   = $row_usuario['us_email'];
                  header("Location: reset.php?funcao=reset");
                } else {

                  if ($dados['senha'] == base64_decode($row_usuario['us_senha'])) {

                    $_SESSION['us_id']            = $row_usuario['us_id'];
                    $_SESSION['us_recno']         = $row_usuario['us_recno'];
                    $_SESSION['us_identificador']  = $row_usuario['us_identificador'];
                    $_SESSION['us_cliente']       = $row_usuario['us_cliente'];
                    $_SESSION['us_senha']         = $row_usuario['us_senha'];
                    $_SESSION['us_last_login']    = $row_usuario['us_last_login'];
                    $_SESSION['us_nome_completo'] = $row_usuario['us_nome_completo'];
                    $_SESSION['us_usuario']       = $row_usuario['us_usuario'];
                    $_SESSION['us_email']         = $row_usuario['us_email'];
                    $_SESSION['us_nivel']         = $row_usuario['us_nivel'];
                    $_SESSION['us_status']        = $row_usuario['us_status'];
                    $_SESSION['us_grupo']         = $row_usuario['us_grupo'];
                    $_SESSION['us_data_entrada']  = $row_usuario['us_data_entrada'];
                    header("Location: painel.php");

                    // CADASTRA DA DATA QUE O USUÁRIO LOGA NO SISTEMA
                    include 'conexao/conexao_sqlsrv.php';
                    $id = $_SESSION['us_id'];
                    $sql = "UPDATE sys_tb_usuarios SET us_last_login = GETDATE() WHERE us_id = '$id'";
                    $params = array($qnty);

                    $stmt = sqlsrv_query($conn, $sql, $params);
                    if ($stmt === false) {
                      die(print_r(sqlsrv_errors(), true));
                    }
                  } else {
                    $_SESSION['msg'] = "<div class=\"alert alert-danger mt-3\" role=\"alert\"><p>Senha inválida!</p></div>";
                  }
                }
              }
            }
          }
        } else {

          if ($dados['senha'] == base64_decode($row_usuario['us_senha'])) {

            $_SESSION['us_id']            = $row_usuario['us_id'];
            $_SESSION['us_recno']         = $row_usuario['us_recno'];
            $_SESSION['us_identificador']  = $row_usuario['us_identificador'];
            $_SESSION['us_cliente']       = $row_usuario['us_cliente'];
            $_SESSION['us_senha']         = $row_usuario['us_senha'];
            $_SESSION['us_last_login']    = $row_usuario['us_last_login'];
            $_SESSION['us_nome_completo'] = $row_usuario['us_nome_completo'];
            $_SESSION['us_usuario']       = $row_usuario['us_usuario'];
            $_SESSION['us_email']         = $row_usuario['us_email'];
            $_SESSION['us_nivel']         = $row_usuario['us_nivel'];
            $_SESSION['us_status']        = $row_usuario['us_status'];
            $_SESSION['us_grupo']         = $row_usuario['us_grupo'];
            header("Location: painel.php");

            // CADASTRA DA DATA QUE O USUÁRIO LOGA NO SISTEMA
            include 'conexao/conexao_sqlsrv.php';
            $id = $_SESSION['us_id'];
            $sql = "UPDATE sys_tb_usuarios SET us_last_login = GETDATE() WHERE us_id = '$id'";
            $params = array($qnty);

            $stmt = sqlsrv_query($conn, $sql, $params);
            if ($stmt === false) {
              die(print_r(sqlsrv_errors(), true));
            }
          } else {
            $_SESSION['msg'] = "<div class=\"alert alert-danger mt-3\" role=\"alert\"><p>Senha inválida!</p></div>";
          }
        }
      }
    } else {
      $_SESSION['msg'] = "<div class=\"alert alert-warning mt-3\" role=\"alert\"><p>Usuário inativo!</p></div>";
    }
  } else {
    $_SESSION['msg'] = "<div class=\"alert alert-danger mt-3\" role=\"alert\"><p>Usuário inválida!</p></div>";
  }
}

?>

<section>
  <div class="row align-items-center m-0">
    <div class="col-xl-4 text-center bg_campo_login">

      <div class="box_login">
        <div class="d-flex justify-content-center">
          <div class="icone_login"><img src="dist/images/icon_user_login.svg" alt=""></div>
        </div>
        <form class="form-signin form_link form-login" method="post" action="">
          <div class="input-group mb-3">
            <span class="input-group-text border icone_imput" id="basic-addon1"><i class="bi bi-person-fill"></i></span>
            <input type="text" class="form-control inputColor" name="usuario" placeholder="Nome de usuário" value="<?php if (isset($dados['usuario'])) {
                                                                                                                      echo $dados['usuario'];
                                                                                                                    } ?>" oninput="this.value = this.value.toUpperCase()" required>
          </div>
          <div class="input-group mb-3">
            <span class="input-group-text border icone_imput" id="basic-addon2"><i class="bi bi-lock-fill"></i></span>
            <input type="password" class="form-control inputColor" name="senha" id="senha" placeholder="Senha" value="<?php if (isset($dados['senha'])) {
                                                                                                                        echo $dados['senha'];
                                                                                                                      } ?>" required>
            <img src="dist/images/eye.svg" onclick="mostrarSenha()" alt="">
          </div>
          <p><a href="reset.php">Esqueci minha senha, e agora?</a></p>
          <div class="text-center">
            <button type="submit" name="SendLogin" class="botao_vasado mt-3" value="Acessar">Acessar minha conta</button>
          </div>
        </form>
      </div>

      <div class="campo_alerta">
        <?php
        if (isset($_SESSION['msg'])) {
          echo $_SESSION['msg'];
          unset($_SESSION['msg']);
        }

        if (isset($_SESSION['erro'])) {
          echo $_SESSION['erro'];
          unset($_SESSION['erro']);
        }

        if (isset($_SESSION['confirm'])) {
          echo $_SESSION['confirm'];
          unset($_SESSION['confirm']);
        }
        ?>
      </div>
    </div>
    <div class="col-xl-8"></div>
  </div>
</section>

<script>
  // MOSTRAR SENHA
  function mostrarSenha() {
    var tipo = document.getElementById("senha");
    if (tipo.type == "password") {
      tipo.type = "text";
    } else {
      tipo.type = "password";
    }
  }
</script>

<?php include_once 'includes/login/footer.php'; ?>