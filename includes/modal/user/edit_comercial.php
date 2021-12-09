<div class="modal fade" id="edtComer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header header_modal">
        <h5 class="modal-title fs-4" id="exampleModalLabel">EDITAR COMERCIAL</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <!-- IMPEDE FINALIZAR CADASTRO CLICANDO NA TACLA 'ENTER' -> onkeydown="return event.key != 'Enter';" -->
        <form method="post" id="cad_admin" action="Controller/ControllerUsuarios.php?funcao=edit_comer" class="row g-3 needs-validation" onkeydown="return event.key != 'Enter';" novalidate>

          <input type="hidden" class="form-control" id="id" name="id">

          <?php if ($_SESSION['us_nivel'] == '1') { ?>

            <!-- APENAS ADMINISTRADOR PODE VER ESTE CAMPO -->
            <div class="col-md-12">
              <!-- <label for="grupo" class="form-label ">Grupo <span class="aste-red">*</span></label> -->
              <select class="form-select campo_select inputColor" id="grupo" name="grupo" required>
                <option selected disabled value=""></option>
                <option value="1">ADMINISTRATIVO</option>
                <option value="2">COMERCIAL</option>
              </select>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

          <?php } else { ?>

            <input type="hidden" class="form-control" id="grupo" name="grupo" value="2">

          <?php } ?>

          <div class="col-lg-12">
            <!-- <label for="nome_completo" class="form-label">Nome</label> -->
            <input list="browsers" type="text" class="form-control campo_form text-uppercase" id="nome_completo" name="nome_completo" placeholder="Nome Completo" disabled>
            <datalist id="browsers">

              <?php
              include 'conexao/conexao_sqlsrv.php';
              $sql = "SELECT Usuario_ID, Totvs, Email, Login, Matricula_FK, Funcionario, Setor, CentroCusto_FK, CentroCusto, Senha
                      FROM Bomixbi.dbo.Sys_TB_Usuario (nolock)
                      ORDER BY Funcionario";
              $stmt = sqlsrv_query($conn, $sql);
              while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
              ?>

                <option value="<?= $row['Funcionario'] ?>">

                <?php }
              sqlsrv_free_stmt($stmt); ?>

            </datalist>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>

          <div class="col-lg-12">
            <!-- <label for="usuario" class="form-label">Usuário</label> -->
            <input type="text" class="form-control campo_form text-uppercase" id="usuario" name="usuario" placeholder="Usuário" disabled>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>
          <div class="col-lg-12">
            <!-- <label for="email" class="form-label">Email <span class="aste-red">*</span></label> -->
            <input type="text" class="form-control campo_form text-lowercase" id="email" name="email" placeholder="Email" required>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>

          <div class="row m-0 mt-4 p-0">
            <div class="col-6">
              <!-- <label for="cargo" class="form-label">Status</label> -->
              <div class="form-check">
                <input class="form-check-input bt_checkbox" type="checkbox" name="status" id="status">
                <label class="form-check-label" for="status">Usuário ativo</label>
              </div>
            </div>
          </div>

          <div class="col-12 text-end">
            <div class="modal-footer p-0 pt-3">
              <!-- <small>( <span class="aste-red">*</span> ) Campos obrigatórios</small> -->
              <button type="button" class="botao_cancelar_vasado" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="botao_vasado LoadEdtComercial">Salvar</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<script>
  // BOTAO LOAD
  document.querySelector('.LoadEdtComercial').addEventListener('click', function() {
    var _this = this;
    _this.classList.add('loading');
    setTimeout(function() {
      _this.classList.remove('loading');
    }, 100000);
  });
</script>

<script type="text/javascript">
  // EDITAR COMERCIAL
  $('#edtComer').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    var nome_completo = button.data('nome_completo')
    var usuario = button.data('usuario')
    var email = button.data('email')
    var grupo = button.data('grupo')
    var status = button.data('status')

    var modal = $(this)
    //modal.find('.modal-title').text('Editar Usuário: ' + nome)
    modal.find('#id').val(id)
    modal.find('#nome_completo').val(nome_completo)
    modal.find('#usuario').val(usuario)
    modal.find('#email').val(email)
    modal.find('#grupo').val(grupo)

    //VERIFICA DE O CHECKBOX ESTÁ MARCADO
    if (status) {
      modal.find('#status').prop("checked", true)
    } else {
      modal.find('#status').prop("checked", false)
    }
  })
</script>