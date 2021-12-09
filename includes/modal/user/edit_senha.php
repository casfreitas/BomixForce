<div class="modal fade" id="cadSenha" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header header_modal">
        <h5 class="modal-title fs-4" id="exampleModalLabel">ALTERAR SENHA</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form method="post" id="cadSenha" action="Controller/ControllerUsuarios.php?funcao=edit_senha" class="row g-3 needs-validation" onSubmit="return val_senha(this);" name="valida_senha" novalidate>

          <input type="hidden" class="form-control" id="id" name="id">

          <div class="col-lg-12">
            <!-- <label class="form-label">Usuário</label> -->
            <input type="text" class="form-control campo_form" id="nome_completo" name="" placeholder="Usuário" disabled>
          </div>

          <div class="col-lg-12">
            <!-- <label class="form-label">Nova Senha <span class="aste-red">*</span></label> -->
            <input type="password" class="form-control campo_form" id="" name="senha" minlength="4" maxlength="8" size="8" placeholder="Nova Senha" required>
            <div class="invalid-feedback">Digite min: 4 / max: 8 caracteres</div>
          </div>

          <div class="col-lg-12">
            <!-- <label class="form-label">Confirme a nova senha <span class="aste-red">*</span></label> -->
            <input type="password" class="form-control campo_form" id="" name="rep_senha" minlength="4" maxlength="8" size="8" placeholder="Confirme a nova senha" required>
            <div class="invalid-feedback">Digite min: 4 / max: 8 caracteres</div>
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


<script type="text/javascript">
  // EDITAR SENHA
  $('#cadSenha').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    var nome_completo = button.data('nome_completo')

    var modal = $(this)
    //modal.find('.modal-title').text('Editar Usuário: ' + nome)
    modal.find('#id').val(id)
    modal.find('#nome_completo').val(nome_completo)
  })
</script>

<!--VALIDAR SENHA-->
<script>
  function val_senha(valida_senha) {
    senha = document.valida_senha.senha.value
    rep_senha = document.valida_senha.rep_senha.value
    if (senha != rep_senha) {

      Swal.fire({
        icon: 'error',
        title: 'Erro encontrado!',
        text: 'As senhas digitadas estão diferentes.',
      })

      return false;
    }
    return true;
  }
</script>