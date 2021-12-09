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
    include 'conexao/conexao_sqlsrv.php';

    if (isset($_POST["dados"])) {
      $dados = $_POST['dados'];

      // $sql = "SELECT Pedido, OrdemCompra, Status, Emissao , Cidade, UF, Cliente
      //         FROM BomixBi.dbo.Fat_TB_PedidoVenda
      //         WHERE Pedido = '$dados'";

      $sql = "Exec [BomixForce].[dbo].[Bomix_GetPedidoVendaItem] '$dados'";

      $stmt = sqlsrv_query($conn, $sql);
      while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

        $ped_vend_id = $row['Pedido'];
        $ord_compra  = $row['OrdemCompra'];
        $status      = $row['Status'];
        $emissao     = $row['Emissao'];
        $cidade      = $row['Cidade'];
        $uf          = $row['UF'];
        $cliente     = $row['Cliente'];
      }

      sqlsrv_free_stmt($stmt);
    } else {
      header(sprintf('location: pedidos.php'));
    }
    ?>

    <div class="row m-0 g-4 p-0">
      <div class="col-lg-4">
        <div class="card card_dados_pedidos">
          <div class="card-body">
            <div class="row">
              <div class="col-12 mb-3"><small>Pedido</small><br><strong class="fs-3">
                  <h5><?= $ped_vend_id ?></h5>
                </strong></div>
              <ul>
                <li><strong>Ordem: </strong><?= $ord_compra ?></li>
                <li><strong>Status: </strong><?= $status ?></li>
                <li><strong>Saída: </strong><?= date_format($emissao, 'd/m/Y') ?></li>
                <li><strong>Cidade: </strong><?= $cidade . ' - ' . $uf ?></li>
                <li><strong>Cliente: </strong><?= $cliente ?></li>
              </ul>
            </div>
          </div>
        </div>
        <a href="Controller/EnviaPedido.php?pedido=<?= $ped_vend_id ?>" class="dup_pedido">
          <div class="botao_vasado text-center mt-4">DUPLICAR PEDIDO</div>
        </a>
      </div>

      <div class="col-lg-8">
        <div class="row g-4">

          <?php
          include 'conexao/conexao_sqlsrv.php';
          $dados = $_POST['dados'];
          $recno = $_SESSION['us_recno'];

          $sql = "SELECT v.PedidoVenda_ID, i.Pedido_FK, v.OrdemCompra, v.Status, v.Emissao, v.Cidade, v.UF, v.Cliente, i.Produto, i.Quantidade, i.Personalizacao, i.ValorUnitario
                FROM BomixBi.dbo.Fat_TB_PedidoVenda v
                INNER JOIN BomixBi.dbo.Fat_TB_PedidoVendaItem i
                ON v.PedidoVenda_ID = i.Pedido_FK
                WHERE v.PedidoVenda_ID = '$dados' AND v.Cliente = 'IBRATIN  NORDESTE LTDA'
                ORDER BY i.Produto";

          $stmt = sqlsrv_query($conn, $sql);
          while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

            $produto     = $row['Produto'];
            $quantidade  = $row['Quantidade'];
            $personali   = $row['Personalizacao'];
            $valor_uni   = $row['ValorUnitario'];

          ?>

            <div class="col-12">
              <div class="card card_pedidos">
                <div class="card-body">
                  <h5 class="card-title fs-5"><strong><?= $produto ?></strong></h5>
                  <ul>
                    <li><strong>Quantidade: </strong><?= $quantidade ?></li>
                    <li><strong>Personalização: </strong> <?= $personali ?></li>
                    <li><strong>Valor unitário <span class="aste-red">*</span>: </strong> R$ <?= $valor_uni ?></li>
                  </ul>
                </div>
              </div>
            </div>

          <?php }
          sqlsrv_free_stmt($stmt); ?>

          <div class="col-12 mt-2">
            <small><span class="aste-red fw-bold">*</span> Valor sujeito a mudanças. Consulte um vendedor.</small>
          </div>

        </div>
      </div>
    </div>
</section>

<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>

<!-- JQUERY EDITAR MODAL -->
<script src="js/table/jquery-3.5.1.js"></script>


<!--***************************************
 ************* CADASTRO *******************
 ***************************************-->

<!-- MODAL CADASTRO ADMINISTRADOR -->
<?php include 'includes/modal/user/cad_admin.php'; ?>

<!-- MODAL CADASTRO COMERCIAL -->
<?php include 'includes/modal/user/cad_comercial.php'; ?>

<!-- MODAL CADASTRO CLIENTE -->
<?php include 'includes/modal/user/cad_cliente.php'; ?>

<!-- MODAL CADASTRO USUÁRIO DO CLIENTE -->
<?php include 'includes/modal/user/cad_cliente_user.php'; ?>


<!--***************************************
 ************* EDIÇÃO *********************
 ***************************************-->

<!-- MODAL EDITAR ADMINISTRADOR -->
<?php include 'includes/modal/user/edit_admin.php'; ?>

<!-- MODAL EDITAR COMERCIAL -->
<?php include 'includes/modal/user/edit_comercial.php'; ?>

<!-- MODAL EDITA CLIENTE -->
<?php include 'includes/modal/user/edit_cliente.php'; ?>

<!-- MODAL EDITA USUÁRIO DO CLIENTE -->
<?php include 'includes/modal/user/edit_cliente_user.php'; ?>


<!--***************************************
 *************** SENHA ********************
 ***************************************-->

<!-- MODAL EDITAR SENHA -->
<?php include 'includes/modal/user/edit_senha.php'; ?>

<!--************************************-->

<!-- VALIDA FORMULÁRIO -->
<script src="dist/js/valida_form.js"></script>


<!-- PREENCHE OS CAMPOS DOS CADASTROS DE USUARIOS 'ADMIN' E 'COMERCIAL' -->
<script>
  $(document).ready(function() {
    $("input[id='nome_completo']").blur(function() {
      var $usuario = $("input[id='usuario']");
      var $email = $("input[id='email']");
      var nome_completo = $(this).val();

      $.getJSON('Controller/procura_user.php', {
          nome_completo
        },
        function(retorno) {
          $usuario.val(retorno.usuario);
          $email.val(retorno.email);
        }
      );
    });
  });
</script>


<!-- PREENCHE OS CAMPOS DOS CADASTROS DE USUARIO 'CLIENTE' -->
<script>
  $(document).ready(function() {
    $("input[id='nome_cliente']").blur(function() {
      var $usuario = $("input[id='usuario']");
      var $email = $("input[id='email']");
      var nome_cliente = $(this).val();

      $.getJSON('Controller/procura_cliente.php', {
          nome_cliente
        },
        function(retorno) {
          $usuario.val(retorno.usuario);
          $email.val(retorno.email);
        }
      );
    });
  });
</script>