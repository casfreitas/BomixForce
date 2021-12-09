<div class="modal fade" id="DocModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header header_modal">
        <h5 class="modal-title fs-4" id="exampleModalLabel">SOLICITAR DOCUMENTOS</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="Controller/ControllerDocumentos.php?funcao=cad_documento" class="row g-3 needs-validation" enctype="multipart/form-data" novalidate>
          <input type="hidden" class="form-control" id="" name="id_user" value="<?= $_SESSION['us_id'] ?>">
          <input type="hidden" class="form-control" id="" name="nome_completo" value="<?= $_SESSION['us_nome_completo'] ?>">
          <input type="hidden" class="form-control" id="" name="cliente" value="<?= $_SESSION['us_cliente'] ?>">
          <input type="hidden" class="form-control" id="" name="email" value="<?= $_SESSION['us_email'] ?>">
          <div class="col-md-12">
            <!-- <label for="documento" class="form-label">Grupo <span class="aste-red">*</span></label> -->
            <select class="form-select campo_select inputColor" id="documento" name="documento" onchange="muda(this);" required>
              <option selected disabled value="">GRUPO</option>
              <option value="ALVARÁ DE FUNCIONAMENTO">ALVARÁ DE FUNCIONAMENTO</option>
              <option value="AVCB – AUTO DE VISTORIA DE CORPO DE BOMBEIROS">AVCB – AUTO DE VISTORIA DE CORPO DE BOMBEIROS</option>
              <option value="ALVARÁ SANITÁRIO">ALVARÁ SANITÁRIO</option>
              <option value="LICENÇA AMBIENTAL">LICENÇA AMBIENTAL</option>
              <option value="CERTIFICAÇÃO ISO 9001">CERTIFICAÇÃO ISO 9001</option>
              <option value="CERTIFICAÇÃO FSSC 22000">CERTIFICAÇÃO FSSC 22000</option>
              <option value="LAUDO DE MIGRAÇÃO">LAUDO DE MIGRAÇÃO</option>
              <option value="LAUDO MICROBIOLÓGICO">LAUDO MICROBIOLÓGICO</option>
              <option value="DECLARAÇÃO AUSÊNCIA DE ALERGÊNICOS">DECLARAÇÃO AUSÊNCIA DE ALERGÊNICOS</option>
              <option value="ESPECIFICAÇÕES TÉCNICAS">ESPECIFICAÇÕES TÉCNICAS</option>
              <option value="CERTIDÕES NEGATIVAS DE DÉBITOS">CERTIDÕES NEGATIVAS DE DÉBITOS</option>
              <option value="OUTROS">OUTROS</option>
            </select>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>
          <div class="col-md-12" id="outros" style="display: none;">
            <!-- <label for="outros" class="form-label">Outros <span class="aste-red">*</span></label> -->
            <input type="text" class="form-control campo_form text-uppercase" id="required_outros" name="outros" placeholder="Descreva">
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>
          <div class="col-md-12" id="tipo_tampa" style="display: none;">
            <!-- <label for="tipo_tampa" class="form-label">Tipo de Tampa <span class="aste-red">*</span></label> -->
            <input type="text" class="form-control campo_form text-uppercase" id="required_tipo_tampa" name="tipo_tampa" placeholder="Tipo de Tampa">
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>
          <div class="col-md-12" id="tipo_balde" style="display: none;">
            <!-- <label for="tipo_balde" class="form-label">Tipo de Balde <span class="aste-red">*</span></label> -->
            <input type="text" class="form-control campo_form text-uppercase" id="required_tipo_balde" name="tipo_balde" placeholder="Tipo de Balde">
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>
          <div class="col-md-12" id="tipo_certidao" style="display: none;">
            <!-- <label for="tipo_certidao" class="form-label">Tipo</label> -->
            <select class="form-select campo_select inputColor" id="required_tipo_certidao" name="tipo_certidao">
              <option selected disabled value="">TIPO</option>
              <option value="ESTADUAL">ESTADUAL</option>
              <option value="MUNICIPAL">MUNICIPAL</option>
              <option value="FEDERAL">FEDERAL</option>
            </select>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>
          <h5 class="fs-6">
            <p>Informação adicional</p>
          </h5>
          <div class="col-md-12 mt-1">
            <!-- <label for="formFile" class="form-label">Default file input example</label> -->
            <input class="form-control campo_form" type="file" name="arquivo" id="formFile"> <!-- accept=".doc,.pdf" -->
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>
          <small class="mt-1">Aqui, você poderá inserir algum anexo, como questionários ou alguma declaração para assinatura.</small>
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


<!-- SELECT ABRE CAMPO ESCONDIDO -->
<script type='text/javascript'>
  function muda(obj) {
    var i = obj.selectedIndex;
    var j = obj.options[i].value;
    if (j == 'ESPECIFICAÇÕES TÉCNICAS') {
      document.getElementById('tipo_tampa').style.display = "block";
      document.getElementById('tipo_balde').style.display = "block";
      document.getElementById('required_tipo_tampa').required = true; //TORNA O CAMPO OBRIGATÓRIO
      document.getElementById('required_tipo_balde').required = true; //TORNA O CAMPO OBRIGATÓRIO
    }
    if (j != 'ESPECIFICAÇÕES TÉCNICAS') {
      document.getElementById('tipo_tampa').style.display = "none"; //ESCONDE O CMAPO
      document.getElementById('tipo_balde').style.display = "none"; //ESCONDE O CMAPO
      document.getElementById('required_tipo_tampa').required = false;
      document.getElementById('required_tipo_balde').required = false;
    }
    if (j == 'OUTROS') {
      document.getElementById('outros').style.display = "block";
      document.getElementById('required_outros').required = true; //TORNA O CAMPO OBRIGATÓRIO
    }
    if (j != 'OUTROS') {
      document.getElementById('outros').style.display = "none"; //ESCONDE O CMAPO
      document.getElementById('required_outros').required = false;
    }
    if (j == 'CERTIDÕES NEGATIVAS DE DÉBITOS') {
      document.getElementById('tipo_certidao').style.display = "block";
      document.getElementById('required_tipo_certidao').required = true; //TORNA O CAMPO OBRIGATÓRIO
    }
    if (j != 'CERTIDÕES NEGATIVAS DE DÉBITOS') {
      document.getElementById('tipo_certidao').style.display = "none"; //ESCONDE O CMAPO
      document.getElementById('required_tipo_certidao').required = false;
    }
  }
</script>