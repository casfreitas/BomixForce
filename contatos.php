<?php include 'includes/header.php'; ?>

<section class="px-2 px-sm-5">
  <div class="container-fluid">
    <h5 class="titulo_pagina">Contatos</h5>
  </div>
  <div class="container-fluid main_paginas px-4 px-sm-5 py-5">

    <div class="bt_cad_padao <?php if ($_SESSION['us_grupo'] != 1) {
                                echo 'd-none';
                              } ?>" data-bs-toggle="modal" data-bs-target="#contatoModal" style="margin-bottom: 20px; margin-top: -30px; margin-left: auto;">
      <img src="dist/images/bt_cad_contato.svg" alt="">
    </div>

    <div class="row g-4">

      <?php
      include 'conexao/conexao_sqlsrv.php';
      $sql = "SELECT co_id, co_nome, co_endereco, co_email FROM sys_tb_contatos";

      $stmt = sqlsrv_query($conn, $sql);
      while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

        $id       = $row['co_id'];
        $nome     = $row['co_nome'];
        $endereco = $row['co_endereco'];
        $email    = $row['co_email'];

      ?>

        <div class="col-md-6 col-xl-4 col-xxl-3">
          <div class="card_contato">
            <div class="row bt_icone">
              <div class="col text-end <?php if ($_SESSION['us_grupo'] != 1) {
                                          echo 'd-none';
                                        } else {
                                          echo 'border-bottom pb-3 mb-3';
                                        } ?>">
                <a href="" data-bs-toggle="modal" data-bs-target="#editCont" data-id="<?= $id ?>" data-nome="<?= $nome ?>" data-endereco="<?= str_replace('<br />', '', $row['co_endereco']) ?>" data-email="<?= $email ?>"><i class="bi bi-pen-fill fs-5 me-2"></i></a>
                <a href="Controller/ControllerContatos.php?co_id=<?= $id ?>" class="del-btn"><i class="bi bi-trash-fill fs-5"></i></a>
              </div>
            </div>
            <h3><?= $nome ?></h3>
            <ul>
              <li>
                <p><?= $endereco ?></p>
              </li>
              <?php if ($email != '') { ?>
                <li>Email: <a href="mailto:<?= $email ?>"><?= $email ?></a></li>
              <?php } ?>
            </ul>
          </div>
        </div>

      <?php }
      sqlsrv_free_stmt($stmt); ?>

    </div>
  </div>

</section>

<?php include 'includes/footer.php'; ?>

<?php
// APENAS ADMINISTRADOR MASTER PODE ADICIONAR E EDITAR OS CONTATOS
if ($_SESSION['us_grupo'] == 1) { ?>

  <?php include 'includes/modal/contatos/cad_contato.php'; ?>

  <?php include 'includes/modal/contatos/edit_contato.php'; ?>

  <!-- VALIDA FORMULÁRIO -->
  <script src="dist/js/valida_form.js"></script>

<?php } ?>