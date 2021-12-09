<div class="modal fade" id="editCont" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header header_modal">
        <h5 class="modal-title fs-4" id="exampleModalLabel">EDITAR CONTATO</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form method="post" action="Controller/ControllerContatos.php?funcao=edit_contato" class="row g-3 needs-validation" novalidate>
          <div class="col-md-12">
            <input type="hidden" class="form-control campo_form" id="id_user" name="id_user" value="<?= $_SESSION['us_id'] ?>">
            <input type="hidden" class="form-control campo_form" id="id" name="id">
            <!-- <label for="nome" class="form-label">Nome <span class="aste-red">*</span></label> -->
            <input type="text" class="form-control campo_form" id="nome" name="nome" placeholder="Nome" required>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>
          <div class="col-md-12">
            <!-- <label for="endereco" class="form-label">Endereço <span class="aste-red">*</span></label> -->
            <textarea class="form-control campo_form campo_textarea" id="endereco" name="endereco" placeholder="Endereço" rows="5" required></textarea>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>
          <div class="col-md-12">
            <!-- <label for="email" class="form-label">Email <span class="aste-red">*</span></label> -->
            <input type="email" class="form-control campo_form text-lowercase" id="email" name="email" placeholder="Email">
          </div>
          <div class="col-12 text-end">
            <div class="modal-footer p-0 pt-3">
              <!-- <small>( <span class="aste-red">*</span> ) Campos obrigatórios</small> -->
              <button type="button" class="botao_cancelar_vasado" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="botao_vasado">Salvar</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<!-- EDITAR MODAL -->
<script src="js/table/jquery-3.5.1.js"></script>
<script type="text/javascript">
  $('#editCont').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    var nome = button.data('nome')
    var endereco = button.data('endereco')
    var email = button.data('email')

    /////////////////////////////////////////////////////////////////

    var modal = $(this)
    //modal.find('.modal-title').text('Editar Usuário: ' + nome)
    modal.find('#id').val(id)
    modal.find('#nome').val(nome)
    modal.find('#endereco').val(endereco)
    modal.find('#email').val(email)
  })
</script>