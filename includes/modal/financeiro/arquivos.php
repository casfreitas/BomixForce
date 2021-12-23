<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header header_modal">
        <h5 class="modal-title fs-4" id="exampleModalLabel">ARQUIVOS</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form class="row g-3 needs-validation" method="POST" action="download.php" novalidate>
          <div class="col-md-12">
            <input type="hidden" class="form-control" id="nota" name="nota">
            <!-- <label for="grupo" class="form-label">Tipo de Arquivo</label> -->
            <select class="form-select campo_select inputColor" id="" name="pasta" onchange="muda(this);" required>
              <option selected disabled value="">TIPO DE ARQUIVO</option>
              <option value="Danfe">NOTA FISCAL</option>
              <option value="Boletos/Itau">BOLETO</option>
              <option value="XML">XML</option>
            </select>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>

          <div class="col-md-12" id="parcela" style="display: none;">
            <!-- <label for="parcela" class="form-label">PARCELAS</label> -->
            <select class="form-select campo_select inputColor modal-dados" id="required_parcela" name="parcela">
              <!-- <option selected disabled value="">PARCELAS</option> -->
            </select>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>
          <div class="col-12 text-end">
            <div class="modal-footer p-0 pt-3">
              <!-- <small>( <span class="aste-red">*</span> ) Campos obrigatórios</small> -->
              <button type="button" class="botao_cancelar_vasado" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="botao_vasado">Baixar</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>


<!-- SELECT ABRE CAMPO ESCONDIDO -->
<script type='text/javascript'>
  function muda(obj) {
    var i = obj.selectedIndex;
    var j = obj.options[i].value;
    if (j == 'Boletos/Itau') {
      document.getElementById('parcela').style.display = "block";
      document.getElementById('required_parcela').required = true; //TORNA O CAMPO OBRIGATÓRIO
    } else {
      document.getElementById('parcela').style.display = "none";
      document.getElementById('required_parcela').required = false;
    }
  }
</script>


<!-- EDITAR MODAL -->
<script src="dist/js/table/jquery-3.5.1.js"></script>
<script type="text/javascript">
  $('#exampleModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget)
    var nota = button.data('nota')

    /////////////////////////////////////////////////////////////////

    var modal = $(this)
    //modal.find('.modal-title').text('Editar Usuário: ' + nome)
    modal.find('#nota').val(nota)

  })
</script>



<script>
  var ped$ = jQuery.noConflict();
  ped$(document).ready(function() {
    ped$(".modalButton").click(function() {
      var nota = ped$(this).data('nota');

      ped$.ajax({
        url: "Controller/Ajax/AjaxFinanceiro.php",
        method: "post",
        data: {
          nota: nota
        },
        success: function(response) {
          ped$(".modal-dados").html(response);
          ped$("#dynamicModal").modal('show');
        }
      })
    })
  })
</script>