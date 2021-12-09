<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header header_modal">
        <h5 class="modal-title fs-4" id="exampleModalLabel">REGISTRO DE NÃO CONFORMIDADE</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form method="post" action="Controller/ControllerNaoConforme.php?funcao=cad_nao_conforme" class="row g-3 needs-validation" enctype="multipart/form-data" novalidate>
          <div class="col-md-6">
            <input type="hidden" class="form-control" id="" name="id_user" value="<?= $_SESSION['us_id'] ?>">
            <input type="hidden" class="form-control" id="" name="status" value="ABERTO">
            <!-- <label for="lote" class="form-label">Lote <span class="aste-red">*</span></label> -->
            <input type="text" class="form-control campo_form" id="lote" name="lote" placeholder="Lote" required>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>
          <div class="col-md-6">
            <!-- <label for="nota" class="form-label">Nota Fiscal <span class="aste-red">*</span></label> -->
            <input type="text" class="form-control campo_form" id="nota" name="nota" placeholder="Nota" required>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>
          <div class="col-md-6">
            <!-- <label for="quantidade" class="form-label">Quantidade <span class="aste-red">*</span></label> -->
            <input type="number" class="form-control campo_form" id="quantidade" name="quantidade" placeholder="Quantidade" required>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>
          <div class="col-md-6">
            <!-- <label for="item" class="form-label">Item <span class="aste-red">*</span></label> -->
            <select class="form-select campo_select inputColor" id="item" name="item" required>
              <option selected disabled value="">Item</option>
              <option value="TAMPA">TAMPA</option>
              <option value="BALDE">BALDE</option>
            </select>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>
          <!-- <h5 class="fs-6">
            <p>Informação adicional</p>
          </h5> -->
          <div class="col-md-12 mt-3 mb-2">
            <!-- <label for="formFile" class="form-label">Default file input example</label> -->
            <input class="form-control campo_form" type="file" name="arquivo" id="arquivo">
          </div>
          <div class="col-md-12 mt-1">
            <!-- <label for="descricao" class="form-label">Descrição <span class="aste-red">*</span></label> -->
            <textarea class="form-control campo_textarea" id="descricao" name="descricao" rows="4" maxlength="500" placeholder="Descrição" required></textarea>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>
          <small class="mt-0">Máximo 500 caracteres</small>
          <div class="col-12 text-end">
            <div class="modal-footer p-0 pt-3">
              <!-- <small>( <span class="aste-red">*</span> ) Campos obrigatórios</small> -->
              <button type="button" class="botao_cancelar_vasado" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="botao_vasado LoadNc">Salvar</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>


<script>
  // BOTAO LOAD
  document.querySelector('.LoadNc').addEventListener('click', function() {
    var _this = this;
    _this.classList.add('loading');
    setTimeout(function() {
      _this.classList.remove('loading');
    }, 1000000);
  });
</script>