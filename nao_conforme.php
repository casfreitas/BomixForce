<?php include 'includes/header.php'; ?>

<?php
//ACESSO RESTRITO AO SITE CASO NÃO SEJA LOGADO
if ((!isset($_SESSION['us_id'])) and (!isset($_SESSION['us_usuario']))) {
  $_SESSION['msg'] = "<div class=\"alert alert-warning mt-3\" role=\"alert\"><p>Acesso restrito!</p></div>";
  header("Location: index.php");
}
?>

<section class="px-2 px-sm-5">
  <div class="container-fluid">
    <h5 class="titulo_pagina">Não Conformidade</h5>
  </div>

  <div class="container-fluid main_paginas px-4 px-sm-5 py-5">

    <?php if ($_SESSION['us_grupo'] <> 1 && $_SESSION['us_grupo'] <> 2) { ?>
      <div class="bt_cad_padao" data-bs-toggle="modal" data-bs-target="#exampleModal" style="margin-bottom: 20px; margin-top: -30px; margin-left: auto;">
        <img src="dist/images/bt_cad_registro.svg" alt="">
      </div>
    <?php } ?>

    <?php
    // VISÃO DO ADMINISTRADOR E COMERCIAL
    if ($_SESSION['us_grupo'] == '1' || $_SESSION['us_grupo'] == '2') { ?>

      <section class="campo_tabela">
        <div class="table-responsive">
          <table id="tabela" class="table tabela table-striped table-hover display">
            <thead>
              <tr>
                <th scope="col">Lote</th>
                <th scope="col">Nota Fiscal</th>
                <th scope="col">Quantidade</th>
                <th scope="col">Item</th>
                <th scope="col">Criação</th>
                <th scope="col">Cliente</th>
                <th scope="col">Status</th>
                <?php
                // VISÃO DO ADMINISTRADOR MASTER
                if ($_SESSION['us_nivel'] == '1') { ?>
                  <th scope="col" width="80px">Ações</th>
                <?PHP } else { ?>
                  <th scope="col" width="50px">Ações</th>
                <?PHP } ?>
              </tr>
            </thead>
            <tbody>

              <?php
              include 'conexao/conexao_sqlsrv.php';
              $sql = "SELECT nc_id, us_fk, nc_data_entrada, nc_lote, nc_nota, nc_quant, nc_item, nc_status, us_id, us_cliente, us_nome_completo
                  FROM sys_tb_nao_conforme
                  INNER JOIN sys_tb_usuarios
                  ON sys_tb_nao_conforme.us_fk = sys_tb_usuarios.us_id
                  ";

              $stmt = sqlsrv_query($conn, $sql);
              while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

                $id           = $row['nc_id'];
                $data_entrada = $row['nc_data_entrada'];
                $lote         = $row['nc_lote'];
                $nota         = $row['nc_nota'];
                $quantidade   = $row['nc_quant'];
                $item         = $row['nc_item'];
                //$descricao    = str_replace('<br />', '', $row['nc_descricao']);
                $status       = $row['nc_status'];

                $cliente      = $row['us_cliente'];
                $nome         = $row['us_nome_completo'];

                //COR DO STATUS
                $cor_status = $row['nc_status'];
                if ($row['nc_status'] == 'ABERTO') {
                  $cor_status = "bg-yellow text-dark";
                }
                if ($row['nc_status'] == 'RESPONDIDO') {
                  $cor_status = "bg-blue";
                }
                if ($row['nc_status'] == 'CONCLUÍDO') {
                  $cor_status = "bg-green";
                }
              ?>

                <tr>
                  <td nowrap="nowrap"><?= $lote ?></th>
                  <td nowrap="nowrap"><?= $nota ?></td>
                  <td nowrap="nowrap"><?= $quantidade ?></td>
                  <td nowrap="nowrap"><?= $item ?></td>
                  <td nowrap="nowrap"><?= date_format($data_entrada, 'Y/m/d'); ?></td>
                  <td nowrap="nowrap"><?= $cliente ?></td>
                  <td nowrap="nowrap"><span class="badge <?= $cor_status ?> p-2"><?= $status ?></span></td>
                  <td>

                    <?php
                    // VISÃO DO ADMINISTRADOR MASTER
                    if ($_SESSION['us_nivel'] == '1') { ?>
                      <div class="row d-flex align-items-center bt_tabela">
                        <div class="col-6 p-0 text-center">
                          <a href="nao_conforme_single.php?id=<?= $id ?>&cliente=<?= $cliente ?>"><i class="bi bi-chat-left-text-fill fs-5"></i></a>
                        </div>
                        <div class="col-6 p-0 text-center">
                          <a href="Controller/ControllerNaoConforme.php?nc_id=<?= $id ?>" class="del-btn">
                            <i class="bi bi-trash-fill fs-5"></i>
                          </a>
                        </div>
                      </div>
                    <?PHP } else { ?>
                      <div class="row d-flex align-items-center bt_tabela">
                        <div class="col-12 p-0 text-center">
                          <a href="nao_conforme_single.php?id=<?= $id ?>&cliente=<?= $cliente ?>"><i class="bi bi-chat-left-text-fill fs-5"></i></a>
                        </div>
                      </div>
                    <?PHP } ?>
                  </td>
                </tr>

              <?php }
              sqlsrv_free_stmt($stmt); ?>

            </tbody>
          </table>
        </div>
      </section>

    <?php
      // VISÃO DO CLIENTE
    } else {
    ?>

      <section class="campo_tabela">
        <div class="table-responsive">
          <table id="tabela" class="table tabela table-striped table-hover display">
            <thead>
              <tr>
                <th scope="col">Lote</th>
                <th scope="col">Nota Fiscal</th>
                <th scope="col">Quantidade</th>
                <th scope="col">Item</th>
                <th scope="col">Criação</th>
                <th scope="col">Cliente</th>
                <th scope="col">Status</th>
                <th scope="col" width="50px">Ações</th>
              </tr>
            </thead>
            <tbody>

              <?php
              include 'conexao/conexao_sqlsrv.php';
              $id = $_SESSION['us_id'];
              $sql = "SELECT nc_id, us_fk, nc_data_entrada, nc_lote, nc_nota, nc_quant, nc_item, nc_status, us_id, us_cliente, us_nome_completo
                  FROM sys_tb_nao_conforme
                  INNER JOIN sys_tb_usuarios
                  ON sys_tb_nao_conforme.us_fk = sys_tb_usuarios.us_id
                  WHERE us_fk = '$id'
                  ";


              $stmt = sqlsrv_query($conn, $sql);
              while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

                $id           = $row['nc_id'];
                $data_entrada = $row['nc_data_entrada'];
                $lote         = $row['nc_lote'];
                $nota         = $row['nc_nota'];
                $quantidade   = $row['nc_quant'];
                $item         = $row['nc_item'];
                //$descricao    = str_replace('<br />', '', $row['nc_descricao']);
                $status       = $row['nc_status'];

                $cliente      = $row['us_cliente'];
                $nome         = $row['us_nome_completo'];

                //COR DO STATUS
                $cor_status = $row['nc_status'];
                if ($row['nc_status'] == 'ABERTO') {
                  $cor_status = "bg-yellow text-dark";
                }
                if ($row['nc_status'] == 'RESPONDIDO') {
                  $cor_status = "bg-blue";
                }
                if ($row['nc_status'] == 'CONCLUÍDO') {
                  $cor_status = "bg-green";
                }
              ?>

                <tr>
                  <td nowrap="nowrap"><?= $lote ?></th>
                  <td nowrap="nowrap"><?= $nota ?></td>
                  <td nowrap="nowrap"><?= $quantidade ?></td>
                  <td nowrap="nowrap"><?= $item ?></td>
                  <td nowrap="nowrap"><?= date_format($data_entrada, 'Y/m/d'); ?></td>
                  <td nowrap="nowrap"><?= $cliente ?></td>
                  <td nowrap="nowrap"><span class="badge <?= $cor_status ?> p-2"><?= $status ?></span></td>
                  <td>
                    <div class="row d-flex align-items-center bt_tabela">
                      <div class="col-12 p-0 text-center">
                        <a href="nao_conforme_single.php?id=<?= $id ?>&cliente=<?= $cliente ?>"><i class="bi bi-chat-left-text-fill fs-5"></i></a>
                      </div>
                    </div>
                  </td>
                </tr>

              <?php }
              sqlsrv_free_stmt($stmt); ?>

            </tbody>
          </table>
        </div>
      </section>

    <?php } ?>

</section>


<?php include 'includes/footer.php'; ?>

<!-- MODAL CADASTRO ADMINISTRADOR -->
<?php include 'includes/modal/nc/registra_nc.php'; ?>

<!-- VALIDA FORMULÁRIO -->
<script src="js/valida_form.js"></script>



<!-- TABLE -->
<!-- <script src="dist/js/table/jquery-3.5.1.js"></script> -->
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