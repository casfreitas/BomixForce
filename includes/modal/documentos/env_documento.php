<div class="modal fade" id="RespDoc" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header header_modal">
        <h5 class="modal-title fs-4" id="exampleModalLabel">RESPONDER SOLICITAÇÃO</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-12">
            <h5 class="fs-5"><strong id="documento"></strong></h5>
          </div>
          <div class="col-12 mt-3" id="campo_link">
            <div class="row d-flex align-items-center bt_tabela border-top border-bottom py-2">
              <div class="col-10"><label>Informação complementar</label></div>
              <div class="col-2 text-end">
                <a href="#" id="doc_link">
                  <i class="bi bi-file-earmark-arrow-down-fill fs-3"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <form method="post" action="Controller/ControllerDocumentos.php?funcao=envia_documento" class="row g-3 needs-validation" enctype="multipart/form-data" novalidate>
          <div class="col-md-12 m-0">
            <label for="formFile" class="form-label mt-2">Carregar arquivo</label>
            <input type="hidden" class="form-control" name="id" id="id">
            <input type="hidden" class="form-control" name="id_user" id="id_user">
            <input type="hidden" class="form-control" name="cadastro_data" id="cadastro_data">
            <input type="hidden" class="form-control" name="email" id="email">

            <input type="file" class="form-control campo_form" name="arquivo" id="formFile" required>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>
          <div class="col-12 text-end">
            <div class="modal-footer p-0 pt-3">
              <!-- <small>( <span class="aste-red">*</span> ) Campos obrigatórios</small> -->
              <button type="button" class="botao_cancelar_vasado" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="botao_vasado LoadDoc">Enviar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
  // BOTAO LOAD
  document.querySelector('.LoadDoc').addEventListener('click', function() {
    var _this = this;
    _this.classList.add('loading');
    setTimeout(function() {
      _this.classList.remove('loading');
    }, 1000000);
  });
</script>



<!-- EDITAR MODAL -->
<script src="js/table/jquery-3.5.1.js"></script>
<script type="text/javascript">
  $('#RespDoc').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    var id_user = button.data('id_user')
    var email = button.data('email')
    var documento = button.data('documento')
    var cadastro_data = button.data('cadastro_data')
    var solicitado = button.data('solicitado')


    /////////////////////////////////////////////////////////////////

    var modal = $(this)
    //modal.find('.modal-title').text('Editar Usuário: ' + nome)
    modal.find('#id').val(id)
    modal.find('#id_user').val(id_user)
    modal.find('#email').val(email)
    modal.find('#documento').text(documento)
    modal.find('#cadastro_data').val(cadastro_data)

    if (solicitado) {
      modal.find('#doc_link').attr("href", "download.php" + "?doc=" + solicitado + "&user=" + id_user)
      document.getElementById('campo_link').style.display = "block";
    } else {
      document.getElementById('campo_link').style.display = "none";
    }
  })
</script>