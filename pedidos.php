<?php include 'includes/header.php'; ?>

<?php
//ACESSO RESTRITO AO SITE CASO NÃO SEJA LOGADO
if ((!isset($_SESSION['us_id'])) and (!isset($_SESSION['us_usuario']))) {
  $_SESSION['msg'] = "<div class=\"alert alert-warning mt-3\" role=\"alert\"><p>Acesso restrito!</p></div>";
  header("Location: index.php");
}

//ACESSO RESTRITO AOS 'PEDIDOS'
if ($_SESSION['us_grupo'] === '4' && $pedidos != 3) {
  $_SESSION['msg'] = "<div class=\"alert alert-warning mt-3\" role=\"alert\"><p>Acesso restrito!</p></div>";
  header("Location: sair.php");
}
?>

<section class="px-2 px-sm-5">
  <div class="container-fluid">
    <h5 class="titulo_pagina">Pedidos</h5>
  </div>
  <div class="container-fluid main_paginas px-4 px-sm-5 py-5">

    <?php
    // $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    // var_dump($dados);
    ?>

    <section class="buscaPedido mb-5">
      <form method="POST" action="<?php $PHP_SELF; ?>" class="row g-3">
        <div class="col-md-6 col-lg-4 col-xl-3">
          <label class="form-label">Busca</label>
          <div class="input-group">
            <input type="text" class="form-control campo_form text-uppercase" name="pedido">
            <span class="input-group-text border icone_imput_menor"><i class="bi bi-search"></i></span>
          </div>
        </div>
        <div class="col-md-6 col-lg-4 col-xl-3">
          <label class="form-label">Data início</label>
          <div class="input-group">
            <!-- <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control campo_form_data" name="data_inicio" placeholder="Data Início"> -->
            <input type="text" onfocus="(this.type='date')" class="form-control campo_form_data text-uppercase" name="data_inicio" placeholder="<?= date('d/m/Y', strtotime('-7 days')) ?>" required>
            <span class="input-group-text border icone_imput_menor"><i class="bi bi-calendar3"></i></span>
          </div>
        </div>
        <div class="col-md-6 col-lg-4 col-xl-3">
          <label class="form-label">Data fim</label>
          <div class="input-group">
            <!-- <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control campo_form_data" name="data_fim" placeholder="Data Fim"> -->
            <input type="text" onfocus="(this.type='date')" class="form-control campo_form_data text-uppercase" name="data_fim" placeholder="<?= date('d/m/Y') ?>" required>
            <span class="input-group-text border icone_imput_menor"><i class="bi bi-calendar3"></i></span>
          </div>
        </div>
        <div class="col-auto bt_busca">
          <button type="submit" name="pesqPedido" id="pesqPedido" class="botao_vasado">Filtrar</button>
        </div>
        <div class="col-auto bt_busca">
          <button type="reset" name="" id="" class="botao_cancelar_vasado">Limpar</button>
        </div>
      </form>
    </section>

    <div class="table-responsive">
      <table id="pedidos" class="table tabela table-striped table-hover display">
        <thead>
          <tr>
            <th scope="col">Pedido</th>
            <th scope="col">Ordem</th>
            <th scope="col">Status</th>
            <th scope="col">Saída</th>
            <th scope="col">Cidade</th>
            <th scope="col">Cliente</th>
            <th scope="col" width="20px">Ações</th>
          </tr>
        </thead>
        <tbody>

          <?php
          include 'conexao/conexao_sqlsrv.php';
          if (isset($_POST['pesqPedido'])) {

            if (isset($_POST['pedido'])) {
              $pedido = $_POST['pedido'];
              //echo $pedido . '<br>';
            }

            if ($_POST['data_inicio'] <> '') {
              $data_day7menos = date_create($_POST['data_inicio']);
              $data_day7menos = date_format($data_day7menos, 'd/m/Y');
              //echo $data_day . '<br>';
            }

            if ($_POST['data_fim'] <> '') {
              $data_day = date_create($_POST['data_fim']);
              $data_day = date_format($data_day, 'd/m/Y');
              //echo $data_day7mais . '<br>';
            }

            $id_user = $_SESSION['us_id']; // ID DO USUÁRIO LOGADO
            @$sql = "Exec [BomixForce].[dbo].[Bomix_GetPedidoVenda] '$data_day7menos', '$data_day', '$id_user', '$pedido'";
          } else {

            $data_day = date('d/m/Y'); // DATA DE HOJE
            //echo  $data_day . '<br>';
            $data_day7menos = date('d/m/Y', strtotime('-7 days')); // DATA DE HOJE MENOS 7 DIAS
            //echo  $data_day7mais . '<br>';
            $id_user = $_SESSION['us_id']; // ID DO USUÁRIO LOGADO
            $sql = "Exec [BomixForce].[dbo].[Bomix_GetPedidoVenda] '$data_day7menos', '$data_day', '$id_user', ''";
          }

          $stmt = sqlsrv_query($conn, $sql);
          while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

            $ped_vend_id = $row['PedidoVenda_ID'];
            $ord_compra  = $row['OrdemCompra'];
            $status      = $row['Status'];
            $emissao     = $row['Emissao'];
            $cidade      = $row['Cidade'];
            $uf          = $row['UF'];
            $cliente     = $row['Cliente'];

            //SE STATOS FOR 'ENCERRADO', MUDA PARA FINALIZADO
            if ($row['Status'] == trim('ENCERRADO')) {
              $status = 'FINALIZADO';
            }

            //COR DO STATUS
            $cor_status = $row['Status'];
            if ($row['Status'] == trim('LIBERADO')) {
              $cor_status = "bg-azul";
            }
            if ($row['Status'] == trim('ENCERRADO')) {
              $cor_status = "bg-success";
            }
            if ($row['Status'] == 'BLOQUEADO LO' || $row['Status'] == 'BLOQUEADO PR') {
              $cor_status = "bg-gray";
            }
            if ($row['Status'] == 'PARCIAL') {
              $cor_status = "bg-warning text-dark";
            }
            if ($row['Status'] == 'ABERTO') {
              $cor_status = "bg-danger";
            }
            if ($row['Status'] == 'ORCAMENTO') {
              $cor_status = "bg-primary";
            }

          ?>
            <tr>
              <td nowrap="nowrap"><?= $ped_vend_id ?></th>
              <td><?= $ord_compra ?></td>
              <td nowrap="nowrap"><span class="badge <?= $cor_status ?> p-2"><?= $status ?></span></td>
              <td nowrap="nowrap"><?= date_format($emissao, 'Y/m/d'); ?></td>
              <td nowrap="nowrap"><?= $cidade ?> - <?= $uf ?></td>
              <td><?= $cliente ?></td>
              <td>
                <div class="row d-flex align-items-center bt_tabela">
                  <div class="col-12 p-0 text-end pe-2">
                    <a href="#" class="modalButton" data-bs-toggle="modal" data-bs-target="#ModalPedido" data-pedido="<?= $ped_vend_id ?>">
                      <!-- <i class="bi bi-file-earmark-arrow-down-fill fs-3"></i> -->
                      <i class="bi bi-justify fs-4"></i>
                    </a>
                  </div>
                </div>
              </td>
            </tr>

          <?php }
          sqlsrv_free_stmt($stmt); ?>

        </tbody>
      </table>
    </div>
  </div>
</section>



<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>

<!-- MODAL DUPLICAR PEDIDO -->
<?php include 'includes/modal/pedidos/sol_pedido.php'; ?>

<!-- BOTAO LOAD -->
<script src="dist/js/btLoad/botaoLoad.js"></script>

<!-- TABLE -->
<!-- <script src="dist/js/table/jquery-3.5.1.js"></script> -->

<style>
  .dataTables_length {
    display: none !important;
  }
</style>
<script type="text/javascript" src="dist/js/table/datatables.min.js"></script>
<script>
  var table$ = jQuery.noConflict();
  table$(document).ready(function() {
    table$('#pedidos').DataTable({
      "searching": false,
      "order": [
        [3, 'desc']
      ],
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