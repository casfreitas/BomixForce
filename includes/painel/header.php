<?php
session_start();
?>
<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="dist/images/favicon.png" type="image/x-icon" />
  <!-- BOOTSTRAP CSS -->
  <link href="dist/css/bootstrap/bootstrap.min.css" rel="stylesheet">
  <!-- BOOTSTRAP ICONE -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <!-- TABLE -->
  <link rel="stylesheet" type="text/css" href="css/table/datatables.css" />
  <!-- RESET -->
  <link href="dist/css/reset.css" rel="stylesheet">
  <link href="dist/css/normalize.css" rel="stylesheet">
  <!-- STYLE -->
  <link href="dist/css/style.css" rel="stylesheet">

  <title>Bomix Force</title>
</head>

<body>

  <?php
  /******************************************************************************
   ************ DESCONECTA O USUÁRIO (GRUPOS = USUÁRIO E CLIENTE) ***************
   *********************** FORA DO HORÁRIO COMERCIAL ****************************
   ******************************************************************************/

  $diasemana = array('Domingo', 'Segunda', 'Terca', 'Quarta', 'Quinta', 'Sexta', 'Sabado'); // ARRAY COM OS DIAS DA SEMANA
  $data = date('Y-m-d'); //AQUI PODEMOS USAR A DATA ATUAL OU QUALQUER OUTRA DATA DO FORMATO ANO-MÊS-DIA
  $diasemana_numero = date('w', strtotime($data)); // VARIAVEL QUE RECEBE O DIA DA SEMANA (0 = Domingo, 1 = Segunda ...)
  $dia = $diasemana[$diasemana_numero]; // EXIBE O DIA DA SEMANA COM O ARRAY

  date_default_timezone_set('America/Sao_Paulo');

  $hora = strtotime(date('H:i')); // HORA ATUAL
  $abre = strtotime('07:30');
  $fecha = strtotime('17:30');

  if ($_SESSION['us_grupo'] != 1 && $_SESSION['us_grupo'] != 2) {

    if ($hora < $abre || $hora > $fecha) {
      header("Location: sair.php");
    }

    if ($dia == 'Sabado' || $dia == 'Domingo') {
      header("Location: sair.php");
    }
  }
  ?>

  <!-- PRELOADER -->
  <div id="preloader">
    <div class="inner">
      <div class="bolas">
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>
  </div>

  <header>

    <div class="row d-flex align-items-center m-0  pt-4 px-sm-5 p-3">
      <div class="col-sm-6 d-flex justify-content-center justify-content-sm-start">
        <a class="navbar-brand p-0" href="#"><img src="dist/images/logo_bomix.svg" class="img-fluid" width="180px" alt=""></a>
      </div>
      <div class="col-sm-6 d-flex justify-content-center justify-content-sm-end">
        <nav class="navbar navbar-expand">
          <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2"></ul>
            <span class="navbar-text drop_menu">
              <a href="#" class="nav-link active d-flex align-items-center text-dark text-end p-0" data-bs-toggle="dropdown" aria-expanded="false">OLÁ, <?= $_SESSION['us_nome_completo']; ?><img src="dist/images/ico_user_topo.svg" alt="" style="width: 50px; height: auto; margin-left: 10px;"></a>
              <ul class="dropdown-menu dropdown-menu-end">
                <a href="perfil.php">
                  <li><button class="dropdown-item" type="button">Configurações e Conta</button></li>
                </a>
                <a href="sair.php">
                  <li><button class="dropdown-item" type="button">Sair</button></li>
                </a>
              </ul>
            </span>
          </div>
        </nav>
      </div>
    </div>

  </header>

  <?php
  //PEGA DADOS DAS PERMISSÕES DE PÁGINA
  include 'conexao/conexao_sqlsrv.php';
  $id_user = $_SESSION['us_id'];
  $sql = "SELECT * FROM sys_tb_usuario_empresa WHERE us_fk = '$id_user'";
  $stmt = sqlsrv_query($conn, $sql);
  while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

    $id        = $row['us_fk'];
    $setor     = $row['ue_setor'];
    $cargo     = $row['ue_cargo'];
    $recno     = $row['ue_recno'];
    $cliente   = $row['ue_cliente'];
    $cnpj      = $row['ue_cnpj'];
    $permissao = $row['ue_permissao'];

    //TRATA VALORES DA PEMISSÃO DE PÁGINA
    $permissao = explode(",", $permissao);
    @$documentos = $permissao[0];
    @$financeiro = $permissao[1];
    @$pedidos    = $permissao[2];

    if (@$permissao[0] == 1 || @$permissao[1] == 1 || @$permissao[2] == 1) {
      $documentos = 1;
    }
    if (@$permissao[0] == 2 || @$permissao[1] == 2 || @$permissao[2] == 2) {
      $financeiro = 2;
    }
    if (@$permissao[0] == 3 || @$permissao[1] == 3 || @$permissao[2] == 3) {
      $pedidos = 3;
    }
  }
  sqlsrv_free_stmt($stmt);

  ?>

  <?php if (isset($_SESSION["erro"])) { // EXIBE ALERTA
  ?>
    <div class="alert msg_alerta alert-warning alert-dismissible fade show" style="background: var(--vermelho);" role="alert">
      <strong><i class="bi bi-exclamation-triangle-fill"></i></strong> <?php print $_SESSION["erro"]; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php unset($_SESSION["erro"]);
  } ?>

  <?php if (isset($_SESSION["msg"])) {  // EXIBE ALERTA
  ?>
    <div class="alert msg_alerta alert-warning alert-dismissible fade show" style="background: var(--verde);" role="alert">
      <strong><i class="bi bi-check-lg"></i></strong> <?php print $_SESSION["msg"]; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php unset($_SESSION["msg"]);
  }
  ?>