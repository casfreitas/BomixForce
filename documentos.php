<?php include 'includes/header.php'; ?>

<?php
//ACESSO RESTRITO AO SITE CASO NÃO SEJA LOGADO
if ((!isset($_SESSION['us_id'])) and (!isset($_SESSION['us_usuario']))) {
  $_SESSION['msg'] = "<div class=\"alert alert-warning mt-3\" role=\"alert\"><p>Acesso restrito!</p></div>";
  header("Location: index.php");
}

//ACESSO RESTRITO AOS 'DOCUMENTOS'
if ($_SESSION['us_grupo'] === '4' && $documentos != 1) {
  $_SESSION['msg'] = "<div class=\"alert alert-warning mt-3\" role=\"alert\"><p>Acesso restrito!</p></div>";
  header("Location: sair.php");
}
?>

<section class="px-2 px-sm-5">
  <div class="container-fluid">
    <h5 class="titulo_pagina">Documentos</h5>
  </div>

  <div class="container-fluid main_paginas px-4 px-sm-5 py-5">

    <?php if ($_SESSION['us_grupo'] <> 1 && $_SESSION['us_grupo'] <> 2) { ?>
      <div class="bt_cad_padao" data-bs-toggle="modal" data-bs-target="#DocModal" style="margin-bottom: 20px; margin-top: -30px; margin-left: auto;">
        <img src="dist/images/bt_cad_solicitacao.svg" alt="">
      </div>
    <?php } ?>

    <section class="campo_tabela">
      <div class="table-responsive">
        <table id="tabela" class="table tabela table-striped table-hover display">
          <thead>
            <tr>
              <th scope="col">Documento</th>
              <th scope="col">Data Solicitação</th>
              <th scope="col">Solicitante</th>
              <th scope="col">Status</th>
              <th scope="col" width="60px">Ações</th>

            </tr>
          </thead>
          <tbody>

            <?php
            include 'conexao/conexao_sqlsrv.php';
            if ($_SESSION['us_grupo'] == '1' || $_SESSION['us_grupo'] == '2') {
              $sql = "SELECT d.doc_id, d.us_fk, d.doc_documento, d.doc_solicitado, d.doc_enviado, d.doc_data_cadastro, u.us_id, u.us_usuario, u.us_email FROM sys_tb_documentos d
                      INNER JOIN sys_tb_usuarios u
                      ON u.us_id = d.us_fk
                      ";
            } else {
              $usuario = $_SESSION['us_usuario'];
              $sql = "SELECT d.doc_id, d.us_fk, d.doc_documento, d.doc_solicitado, d.doc_enviado, d.doc_data_cadastro, u.us_id, u.us_usuario, u.us_email FROM sys_tb_documentos d
                      INNER JOIN sys_tb_usuarios u
                      ON u.us_id = d.us_fk
                      WHERE u.us_usuario = '$usuario'
                      ";
            }

            $stmt = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

              $id            = $row['doc_id'];
              $documento     = $row['doc_documento'];
              $data_cadastro = $row['doc_data_cadastro'];
              $solicitado    = $row['doc_solicitado'];
              $enviado       = $row['doc_enviado'];
              $id_user       = $row['us_fk'];
              $solicitante   = $row['us_usuario'];
              $email         = $row['us_email'];

              //COR DO STATUS
              if ($row['doc_enviado'] == '') {
                $cor_status = "bg-orange text-dark";
                $doc = 'EM ANÁLISE';
              } else {
                $cor_status = "bg-yellow text-dark";
                $doc = 'FINALIZADO';
              }

              $cadastro_data = date_format($data_cadastro, 'd/m/Y');

            ?>
              <tr>
                <td><?= $documento ?></th>
                <td nowrap="nowrap"><?= date_format($data_cadastro, 'Y/m/d'); ?></td>
                <td nowrap="nowrap"><?= $solicitante ?></td>
                <td nowrap="nowrap"><span class="badge <?= $cor_status ?> p-2"><?= $doc ?></span></td>
                <td>

                  <?php if ($_SESSION['us_grupo'] == '1' || $_SESSION['us_grupo'] == '2') { ?>

                    <div class="row d-flex align-items-center bt_tabela">
                      <?php if (!isset($enviado)) { ?>
                        <div class="col-6 p-0 text-center">
                          <a href="#" data-bs-toggle="modal" data-bs-target="#RespDoc" data-id="<?= $id ?>" data-id_user="<?= $id_user ?>" data-email="<?= $email ?>" data-documento="<?= $documento ?>" data-solicitado="<?= $solicitado ?>" data-cadastro_data="<?= $cadastro_data ?>">
                            <i class="bi bi-folder-symlink-fill fs-3"></i>
                          </a>
                        </div>
                      <?php } else { ?>
                        <div class="col-6 p-0 text-center">
                          <i class="bi bi-hand-thumbs-up fs-3"></i>
                        </div>
                      <?php } ?>


                    <?php } else { ?>

                      <?php if (!isset($enviado)) { ?>
                        <div class="col-6 p-0 text-center">
                          <i class="bi bi-question-lg fs-4"></i>
                        </div>
                      <?php } else { ?>
                        <div class="col-6 p-0 text-center">
                          <a href="download.php?doc=<?= $enviado ?>&user=<?= $id_user ?>">
                            <i class="bi bi-file-earmark-arrow-down-fill fs-3"></i>
                          </a>
                        </div>
                    <?php }
                    } ?>
                    <?php if ($_SESSION['us_nivel'] == '1') { ?>
                      <div class="col-6 p-0 text-center">
                        <a href="Controller/ControllerDocumentos.php?doc_id=<?= $id ?>" title="Excluir" class="del-btn">
                          <i class="bi bi-trash-fill fs-5"></i>
                        </a>
                      </div>
                    <?php } ?>
                    </div>
                </td>
              </tr>

            <?php }
            sqlsrv_free_stmt($stmt); ?>
          </tbody>
        </table>
      </div>
    </section>

</section>





<!-- TABLE -->
<script src="dist/js/table/jquery-3.5.1.js"></script>
<script type="text/javascript" src="dist/js/table/datatables.min.js"></script>
<script>
  var table$ = jQuery.noConflict();
  table$(document).ready(function() {
    table$('#tabela').DataTable({
      // "order": [[ 1, 'asc' ]],
      "lengthMenu": [
        [10, 15, 20, 25, 30, 50, 100, -1],
        [10, 15, 20, 25, 30, 50, 100, "Todos"]
      ],
      "language": {
        "sProcessing": "Procurando...",
        "lengthMenu": "Mostrar _MENU_ registros",
        "zeroRecords": "Nenhum registro encontrado",
        "search": "Procurar: ",
        "info": "Mostrar _START_ até _END_ de _TOTAL_ registros",
        "infoEmpty": "Nenhum registro encontrad",
        "infoFiltered": "(filtrado de _MAX_ registros totais)",
        "paginate": {
          "first": "Primeiro",
          "last": "Último",
          "next": "Próximo",
          "previous": "Anterior"
        },
      }
    });

  });
</script>


<?php include 'includes/footer.php'; ?>

<?php if ($_SESSION['us_grupo'] != '1' && $_SESSION['us_grupo'] != '2') { ?>
  <!-- MODAL SOLICITA DOCUMENTOS -->
  <?php include 'includes/modal/documentos/sol_documento.php'; ?>
<?php } else { ?>
  <!-- MODAL ENVIA DOCUMENTOS -->
  <?php include 'includes/modal/documentos/env_documento.php'; ?>
<?php } ?>

<!-- VALIDA FORMULÁRIO -->
<script src="dist/js/valida_form.js"></script>