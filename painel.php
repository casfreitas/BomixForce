<?php include 'includes/painel/header.php'; ?>

<?php
//ACESSO RESTRITO AO SITE CASO NÃO SEJA LOGADO
if ((!isset($_SESSION['us_id'])) and (!isset($_SESSION['us_usuario']))) {
  $_SESSION['msg'] = "<div class=\"alert alert-warning mt-3\" role=\"alert\"><p>Acesso restrito!</p></div>";
  header("Location: index.php");
}
?>

<style>
  body {
    background-image: url('dist/images/bg_branco.jpg') !important;
    background-repeat: no-repeat;
    background-position: center top;
    background-size: cover;
    /* height: 100vh; */
  }
</style>


<section class="container bt_painel">
  <div class="row">
    <div class="col-lg-6 offset-lg-3">

      <?php
      // VISTA DO USUÁRIO DO CLIENTE 
      if ($_SESSION['us_grupo'] == '4') { ?>

        <div class="row g-4 justify-content-center p-3">

          <?php
          // CARD PARA USUARIOS COM PERMISSÃO AOS PEDIDOS
          if ($pedidos == 3) { ?>

            <div class="col-xs-6 col-sm-6 col-xl-6">
              <a href="pedidos.php">
                <div class="card bt_card">
                  <div class="card-body ">
                    <img src="dist/images/03.svg" alt="">
                    <h5 class="card-title">Pedidos</h5>
                  </div>
                </div>
              </a>
            </div>

          <?php } ?>

          <div class="col-xs-6 col-sm-6 col-xl-6">
            <a href="nao_conforme.php">
              <div class="card bt_card">
                <div class="card-body ">
                  <img src="dist/images/02.svg" alt="">
                  <h5 class="card-title">Não Conformidade</h5>
                </div>
              </div>
            </a>
          </div>

          <?php
          // CARD PARA USUARIOS COM PERMISSÃO AOS DOCUMENTOS
          if ($documentos == 1) { ?>

            <div class="col-xs-6 col-sm-6 col-xl-6">
              <a href="documentos.php">
                <div class="card bt_card">
                  <div class="card-body ">
                    <img src="dist/images/04.svg" alt="">
                    <h5 class="card-title">Documentos</h5>
                  </div>
                </div>
              </a>
            </div>

          <?php } ?>

          <?php
          // CARD PARA USUARIOS COM PERMISSÃO AO FINANCEIRO
          if ($financeiro == 2) { ?>

            <div class="col-xs-6 col-sm-6 col-xl-6">
              <a href="financeiro.php">
                <div class="card bt_card">
                  <div class="card-body ">
                    <img src="dist/images/01.svg" alt="">
                    <h5 class="card-title">Financeiro</h5>
                  </div>
                </div>
              </a>
            </div>

          <?php } ?>

          <div class="col-xs-6 col-sm-6 col-xl-6">
            <a href="contatos.php">
              <div class="card bt_card">
                <div class="card-body ">
                  <img src="dist/images/05.svg" alt="">
                  <h5 class="card-title">Contatos</h5>
                </div>
              </div>
            </a>
          </div>

        </div>

      <?php } ?>

      <?php
      // VISTA DO ADMINISTRADOR E COMERCIAL
      if ($_SESSION['us_grupo'] != '4') { ?>

        <div class="row g-4 justify-content-center p-3">

          <div class="col-xs-6 col-sm-6 col-xl-6">
            <a href="user.php">
              <div class="card bt_card">
                <div class="card-body ">
                  <img src="dist/images/06.svg" alt="">
                  <h5 class="card-title">Usuário</h5>
                </div>
              </div>
            </a>
          </div>

          <div class="col-xs-6 col-sm-6 col-xl-6">
            <a href="pedidos.php">
              <div class="card bt_card">
                <div class="card-body ">
                  <img src="dist/images/03.svg" alt="">
                  <h5 class="card-title">Pedidos</h5>
                </div>
              </div>
            </a>
          </div>

          <div class="col-xs-6 col-sm-6 col-xl-6">
            <a href="nao_conforme.php">
              <div class="card bt_card">
                <div class="card-body ">
                  <img src="dist/images/02.svg" alt="">
                  <h5 class="card-title">Não Conformidade</h5>
                </div>
              </div>
            </a>
          </div>

          <div class="col-xs-6 col-sm-6 col-xl-6">
            <a href="documentos.php">
              <div class="card bt_card">
                <div class="card-body ">
                  <img src="dist/images/04.svg" alt="">
                  <h5 class="card-title">Documentos</h5>
                </div>
              </div>
            </a>
          </div>

          <div class="col-xs-6 col-sm-6 col-xl-6">
            <a href="financeiro.php">
              <div class="card bt_card">
                <div class="card-body ">
                  <img src="dist/images/01.svg" alt="">
                  <h5 class="card-title">Financeiro</h5>
                </div>
              </div>
            </a>
          </div>

          <div class="col-xs-6 col-sm-6 col-xl-6">
            <a href="contatos.php">
              <div class="card bt_card">
                <div class="card-body ">
                  <img src="dist/images/05.svg" alt="">
                  <h5 class="card-title">Contatos</h5>
                </div>
              </div>
            </a>
          </div>

        </div>

      <?php } ?>

    </div>
  </div>
</section>

<?php include 'includes/painel/footer.php'; ?>