<div class="modal fade" id="contatoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header header_modal">
        <h5 class="modal-title fs-4" id="exampleModalLabel">Criar Contato</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form method="post" action="Controller/ControllerContatos.php?funcao=cad_contato" class="row g-3 needs-validation" novalidate>
          <div class="col-md-12">
            <input type="hidden" class="form-control campo_form" id="id_user" name="id_user" value="<?= $_SESSION['us_id'] ?>">
            <!-- <label for="nome" class="form-label">Nome <span class="aste-red">*</span></label> -->
            <input type="text" class="form-control campo_form" id="nome" name="nome" placeholder="Nome" required>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>
          <div class="col-md-12">
            <!-- <label for="endereco" class="form-label">Endereço <span class="aste-red">*</span></label> -->
            <textarea class="form-control campo_textarea" id="endereco" name="endereco" rows="5" placeholder="Endereço" required></textarea>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>
          <div class="col-md-12">
            <!-- <label for="email" class="form-label">Email <span class="aste-red">*</span></label> -->
            <input type="email" class="form-control campo_form text-lowercase" id="email" name="email" placeholder="Email">
            <div class="invalid-feedback">Campo obrigatório</div>
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