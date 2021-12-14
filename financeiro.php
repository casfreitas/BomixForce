<?php include 'includes/header.php'; ?>

<?php
//ACESSO RESTRITO AO SITE CASO NÃO SEJA LOGADO
if ((!isset($_SESSION['us_id'])) and (!isset($_SESSION['us_usuario']))) {
  $_SESSION['msg'] = "<div class=\"alert alert-warning mt-3\" role=\"alert\"><p>Acesso restrito!</p></div>";
  header("Location: index.php");
}

//ACESSO RESTRITO AOS 'FINANCEIRO'
if ($_SESSION['us_grupo'] === '4' && $financeiro != 2) {
  $_SESSION['msg'] = "<div class=\"alert alert-warning mt-3\" role=\"alert\"><p>Acesso restrito!</p></div>";
  header("Location: sair.php");
}
?>

<section class="px-2 px-sm-5">
  <div class="container-fluid">
    <h5 class="titulo_pagina">Financeiro</h5>
  </div>
  <div class="container-fluid main_paginas px-4 px-sm-5 py-5">

    <section class="mb-5">
      <form method="POST" action="<?php $PHP_SELF; ?>" class="row g-3">
        <div class="col-md-6 col-lg-4 col-xl-3">
          <label class="form-label">Busca</label>
          <div class="input-group">
            <input type="text" class="form-control campo_form text-uppercase" name="nota">
            <span class="input-group-text border icone_imput_menor"><i class="bi bi-search"></i></span>
          </div>
        </div>
        <div class="col-md-6 col-lg-4 col-xl-3">
          <label class="form-label">Data início</label>
          <div class="input-group">
            <input type="text" onfocus="(this.type='date')" class="form-control campo_form_data text-uppercase" name="data_inicio" placeholder="<?= date('d/m/Y', strtotime('-30 days')) ?>" required>
            <span class="input-group-text border icone_imput_menor"><i class="bi bi-calendar3"></i></span>
          </div>
        </div>
        <div class="col-md-6 col-lg-4 col-xl-3">
          <label class="form-label">Data fim</label>
          <div class="input-group">
            <input type="text" onfocus="(this.type='date')" class="form-control campo_form_data text-uppercase" name="data_fim" placeholder="<?= date('d/m/Y') ?>" required>
            <span class="input-group-text border icone_imput_menor"><i class="bi bi-calendar3"></i></span>
          </div>
        </div>
        <div class="col-auto bt_busca">
          <button type="submit" name="pesqNota" id="pesqNota" class="botao_vasado">Filtrar</button>
        </div>
        <div class="col-auto bt_busca">
          <a href="financeiro.php">
            <div name="" id="" class="botao_cancelar_vasado">Limpar</div>
          </a>
          <!-- <button type="reset" name="" id="" class="botao_cancelar_vasado">Limpar</button> -->
        </div>
      </form>
    </section>

    <div class="table-responsive">
      <table id="financeiro" class="table tabela table-striped table-hover display">
        <thead>
          <tr>
            <th scope="col">Nota Fiscal</th>
            <th scope="col">Emissão</th>
            <th scope="col">Cliente</th>
            <th scope="col" width="50px">Ações</th>
          </tr>
        </thead>
        <tbody>

          <?php
          include 'conexao/conexao_sqlsrv.php';
          if (isset($_POST['pesqNota'])) {

            if (isset($_POST['nota'])) {
              $nota = $_POST['nota'];
              //echo $nota . '<br>';
            }

            if ($_POST['data_inicio'] <> '') {
              $data_dayMais = date_create($_POST['data_inicio']);
              $data_dayMais = date_format($data_dayMais, 'd/m/Y');
              //echo $data_day . '<br>';
            }

            if ($_POST['data_fim'] <> '') {
              $data_day = date_create($_POST['data_fim']);
              $data_day = date_format($data_day, 'd/m/Y');
              //echo $data_dayMais . '<br>';
            }

            $id_user = $_SESSION['us_id']; // ID DO USUÁRIO LOGADO
            @$sql = "Exec [BomixForce].[dbo].[Bomix_GetNotaFiscalVenda] '$data_dayMais', '$data_day', '$id_user', '$nota'";
          } else {

            $data_day = date('d/m/Y'); // DATA DE HOJE
            $data_dayMais = date('d/m/Y', strtotime('-30 days')); // DATA DE HOJE MENOS 30 DIAS
            $id_user = $_SESSION['us_id']; // ID DO USUÁRIO LOGADO
            $sql = "Exec [BomixForce].[dbo].[Bomix_GetNotaFiscalVenda] '$data_dayMais', '$data_day', '$id_user', ''";
          }

          $stmt = sqlsrv_query($conn, $sql);
          while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $nota = $row['Nota'];
          ?>

            <tr>
              <td nowrap="nowrap"><?= $row['Nota'] ?></th>
              <td nowrap="nowrap"><?= date_format($row['Emissao'], 'Y/m/d'); ?></td>
              <td nowrap="nowrap"><?= $row['Cliente'] ?></td>
              <td>
                <div class="row d-flex align-items-center bt_tabela">
                  <div class="col-12 p-0 text-center">
                    <!-- <a href="#" class="modalButton" data-bs-toggle="modal" data-bs-target="#exampleModal" data-nota="<?= $row['Nota'] ?>"> -->
                    <a href="#" class="modalButton" data-bs-toggle="modal" data-bs-target="#exampleModal" data-nota="<?= $row['Nota'] ?>">
                      <i class="bi bi-file-earmark-arrow-down-fill fs-3"></i>
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
</section>





<!-- TABLE -->
<style>
  .dataTables_length {
    display: none !important;
  }
</style>

<script src="dist/js/table/jquery-3.5.1.js"></script>
<script type="text/javascript" src="dist/js/table/datatables.min.js"></script>
<script>
  var table$ = jQuery.noConflict();
  table$(document).ready(function() {
    table$('#financeiro').DataTable({
      "searching": false,
      "order": [
        [1, 'desc']
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


<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>

<!-- MODAL SOLICITA DOCUMENTOS -->
<?php include 'includes/modal/financeiro/arquivos.php'; ?>

<!-- VALIDA FORMULÁRIO -->
<script src="dist/js/valida_form.js"></script>