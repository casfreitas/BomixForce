<div class="modal fade" id="ModalPedido" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header header_modal">
        <h5 class="modal-title fs-4" id="exampleModalLabel"></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="table-responsive px-3 pt-2 mb-0">
        <table class="table table-striped table-hover" style="padding: 0px !important;">
          <thead>
            <tr>
              <th scope="col" style="padding: 5px !important;">Produto</th>
              <th scope="col" style="padding: 5px !important;">Quantidade</th>
              <th scope="col" style="padding: 5px !important;">Personalizado</th>
              <th scope="col" style="padding: 5px !important;">Valor unitário *</th>
            </tr>
          </thead>
          <tbody class="modal-dados"></tbody>
        </table>
      </div>

      <?php if ($_SESSION['us_grupo'] == '4') { ?>
        <div class="row d-flex align-items-center border-top py-3 m-0">
          <div class="col-md-6 text-start">
            <small>( <span class="aste-red">*</span> ) Valor sujeito a mudanças. Consulte um vendedor.</small>
          </div>
          <div class="col-md-6 text-end">
            <form method="post" action="Controller/EnviaPedido.php">
              <input type="hidden" class="campo_form" name="pedido" id="pedido">
              <!-- <button type="submit" class="botao_vasado" onclick="this.classList.toggle('button--loading')"><span class="button__text">Duplicar pedido</span></button> -->
              <button class="botao_vasado LoadPedido">Duplicar pedido</button>
            </form>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</div>

<script>
  // BOTAO LOAD
  document.querySelector('.LoadPedido').addEventListener('click', function() {
    var _this = this;
    _this.classList.add('loading');
    setTimeout(function() {
      _this.classList.remove('loading');
    }, 1000000);
  });
</script>


<!-- EDITAR MODAL -->
<script src="dist/js/table/jquery-3.5.1.js"></script>
<script type="text/javascript">
  $('#ModalPedido').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget)
    var pedido = button.data('pedido')

    /////////////////////////////////////////////////////////////////

    var modal = $(this)
    modal.find('.modal-title').text('PEDIDO: ' + pedido)
    modal.find('#pedido').val(pedido)

  })
</script>


<script>
  var ped$ = jQuery.noConflict();
  ped$(document).ready(function() {
    ped$(".modalButton").click(function() {
      var pedido = ped$(this).data('pedido');

      ped$.ajax({
        url: "Controller/Ajax/AjaxPedidos.php",
        method: "post",
        data: {
          pedido: pedido
        },
        success: function(response) {
          ped$(".modal-dados").html(response);
          ped$("#dynamicModal").modal('show');
        }
      })
    })
  })
</script>