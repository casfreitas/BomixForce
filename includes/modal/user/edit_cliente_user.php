<!-- MODAL USER CLIENTE EDITAR -->
<div class="modal fade" id="edtUserClie" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header header_modal">
        <h5 class="modal-title fs-4" id="exampleModalLabel">EDITAR USUÁRIO</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">


        <?php
        // MODAL DO ADMINISTRADOR MASTER
        if ($_SESSION['us_nivel'] == '1') { ?>

          <form method="post" id="cad_cliente_user" action="Controller/ControllerUsuarios.php?funcao=editUserClienteAdmin" class="row g-3 needs-validation" novalidate>

            <input type="hidden" class="form-control" id="id" name="id">

            <div class="col-md-12">
              <!-- <label for="cnpj" class="form-label">Cliente <span class="aste-red">*</span></label> -->
              <select class="form-select campo_select inputColor" id="cnpj" name="cnpj" required>
                <?php
                $sql = "SELECT us_nome_completo, us_usuario FROM sys_tb_usuarios WHERE us_grupo = '3' ORDER BY us_usuario";
                $stmt_uc = sqlsrv_query($conn, $sql);
                while ($row = sqlsrv_fetch_array($stmt_uc, SQLSRV_FETCH_ASSOC)) {
                ?>
                  <option value="<?= $row['us_usuario'] ?>"><?= $row['us_nome_completo'] ?></option>
                <?php }
                sqlsrv_free_stmt($stmt_uc); ?>
              </select>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="col-lg-12">
              <!-- <label for="nome_completo" class="form-label">Nome <span class="aste-red">*</span></label> -->
              <input type="text" class="form-control campo_form text-uppercase" id="nome_completo" name="nome_completo" placeholder="Nome Completo" disabled>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="col-lg-12">
              <!-- <label for="usuario" class="form-label">Usuário <span class="aste-red">*</span></label> -->
              <input type="text" class="form-control campo_form text-uppercase" id="usuario" name="usuario" placeholder="Usuário" disabled>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="col-lg-12">
              <!-- <label for="email" class="form-label">Email <span class="aste-red">*</span></label> -->
              <input type="text" class="form-control campo_form text-lowercase" id="email" name="email" placeholder="Email" required>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="col-lg-6">
              <!-- <label for="setor" class="form-label">Setor <span class="aste-red">*</span></label> -->
              <input type="text" class="form-control campo_form text-uppercase" id="setor" name="setor" placeholder="Setor" required>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="col-lg-6">
              <!-- <label for="cargo" class="form-label">Cargo <span class="aste-red">*</span></label> -->
              <input type="text" class="form-control campo_form text-uppercase" id="cargo" name="cargo" placeholder="Cargo" required>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="row m-0 mt-4 p-0">

              <div class="col-6">
                <!-- <label for="cargo" class="form-label">Acesso <span class="aste-red">*</span></label> -->
                <div class="form-check mb-2">
                  <input class="form-check-input bt_checkbox" type="checkbox" name="acesso[]" value="1" id="documentos">
                  <label class="form-check-label" for="">Documentos</label>
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input bt_checkbox" type="checkbox" name="acesso[]" value="2" id="financeiro">
                  <label class="form-check-label" for="">Financeiro</label>
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input bt_checkbox" type="checkbox" name="acesso[]" value="3" id="pedidos">
                  <label class="form-check-label" for="">Pedidos</label>
                </div>
              </div>

              <?php if ($_SESSION['us_grupo'] == '1') { ?>

                <div class="col-6">
                  <!-- <label for="cargo" class="form-label">Status</label> -->
                  <div class="form-check">
                    <input class="form-check-input bt_checkbox" type="checkbox" name="status" id="status">
                    <label class="form-check-label" for="status">Usuário ativo</label>
                  </div>
                </div>

              <?php } else { ?>

                <div class="col-6">
                  <label for="cargo" class="form-label">Status</label>
                  <div class="form-check form-switch">
                    <input class="form-control" type="hidden" name="status" id="status">
                    <input class="form-check-input" type="checkbox" role="switch" id="status" disabled>
                    <label class="form-check-label" for="status">Usuário ativo</label>
                  </div>
                </div>

              <?php } ?>

            </div>

            <div class="col-12 text-end">
              <div class="modal-footer p-0 pt-3">
                <!-- <small>( <span class="aste-red">*</span> ) Campos obrigatórios</small> -->
                <button type="button" class="botao_cancelar_vasado" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="botao_vasado LoadEdtUserCliente">Salvar</button>
              </div>
            </div>
          </form>

        <?php } ?>



        <?php
        // MODAL DO ADMINISTRADOR
        if ($_SESSION['us_grupo'] == '1' && $_SESSION['us_nivel'] != '1') { ?>

          <form method="post" id="cad_cliente_user" action="Controller/ControllerUsuarios.php?funcao=editUserClienteStatus" class="row g-3 needs-validation" novalidate>

            <input type="hidden" class="form-control" id="id" name="id">

            <div class="col-lg-12">
              <!-- <label for="nome_completo" class="form-label">Nome <span class="aste-red">*</span></label> -->
              <input type="text" class="form-control campo_form text-uppercase" id="nome_completo" name="nome_completo" disabled>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="col-lg-12">
              <!-- <label for="usuario" class="form-label">Usuário <span class="aste-red">*</span></label> -->
              <input type="text" class="form-control campo_form text-uppercase" id="usuario" name="usuario" disabled>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="col-lg-12">
              <!-- <label for="email" class="form-label">Email <span class="aste-red">*</span></label> -->
              <input type="text" class="form-control campo_form text-lowercase" id="email" name="email" disabled>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="col-lg-6">
              <!-- <label for="setor" class="form-label">Setor <span class="aste-red">*</span></label> -->
              <input type="text" class="form-control campo_form text-uppercase" id="setor" name="setor" disabled>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="col-lg-6">
              <!-- <label for="cargo" class="form-label">Cargo <span class="aste-red">*</span></label> -->
              <input type="text" class="form-control campo_form text-uppercase" id="cargo" name="cargo" disabled>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="row m-0 mt-4 p-0">

              <div class="col-6">
                <!-- <label for="cargo" class="form-label">Acesso <span class="aste-red">*</span></label> -->
                <div class="form-check mb-2">
                  <input class="form-check-input bt_checkbox" type="checkbox" name="acesso[]" value="1" id="documentos" disabled>
                  <label class="form-check-label" for="">Documentos</label>
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input bt_checkbox" type="checkbox" name="acesso[]" value="2" id="financeiro" disabled>
                  <label class="form-check-label" for="">Financeiro</label>
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input bt_checkbox" type="checkbox" name="acesso[]" value="3" id="pedidos" disabled>
                  <label class="form-check-label" for="">Pedidos</label>
                </div>
              </div>

              <div class="col-6">
                <!-- <label for="cargo" class="form-label">Status</label> -->
                <div class="form-check">
                  <input class="form-check-input bt_checkbox" type="checkbox" name="status" id="status">
                  <label class="form-check-label" for="status">Usuário ativo</label>
                </div>
              </div>

            </div>

            <div class="modal-footer p-0 pt-3">
              <!-- <small>( <span class="aste-red">*</span> ) Campos obrigatórios</small> -->
              <button type="button" class="botao_cancelar_vasado" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="botao_vasado LoadEdtUserCliente">Salvar</button>
            </div>
          </form>

        <?php } ?>



        <?php
        // MODAL DO COMERCIAL
        if ($_SESSION['us_grupo'] == '2') { ?>

          <form method="post" id="cad_cliente_user" action="Controller/ControllerUsuarios.php?funcao=editUserClienteStatus" class="row g-3 needs-validation" novalidate>

            <input type="hidden" class="form-control" id="id" name="id">

            <div class="col-lg-12">
              <!-- <label for="nome_completo" class="form-label">Nome <span class="aste-red">*</span></label> -->
              <input type="text" class="form-control campo_form text-uppercase" id="nome_completo" name="nome_completo" disabled>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="col-lg-12">
              <!-- <label for="usuario" class="form-label">Usuário <span class="aste-red">*</span></label> -->
              <input type="text" class="form-control campo_form text-uppercase" id="usuario" name="usuario" disabled>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="col-lg-12">
              <!-- <label for="email" class="form-label">Email <span class="aste-red">*</span></label> -->
              <input type="text" class="form-control campo_form text-lowercase" id="email" name="email" disabled>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="col-lg-6">
              <!-- <label for="setor" class="form-label">Setor <span class="aste-red">*</span></label> -->
              <input type="text" class="form-control campo_form text-uppercase" id="setor" name="setor" disabled>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="col-lg-6">
              <!-- <label for="cargo" class="form-label">Cargo <span class="aste-red">*</span></label> -->
              <input type="text" class="form-control campo_form text-uppercase" id="cargo" name="cargo" disabled>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="row m-0 mt-4 p-0">

              <div class="col-6">
                <!-- <label for="cargo" class="form-label">Acesso <span class="aste-red">*</span></label> -->

                <div class="form-check mb-2">
                  <input class="form-check-input bt_checkbox" type="checkbox" name="acesso[]" value="1" id="documentos" disabled>
                  <label class="form-check-label" for="">Documentos</label>
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input bt_checkbox" type="checkbox" name="acesso[]" value="2" id="financeiro" disabled>
                  <label class="form-check-label" for="">Financeiro</label>
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input bt_checkbox" type="checkbox" name="acesso[]" value="3" id="pedidos" disabled>
                  <label class="form-check-label" for="">Pedidos</label>
                </div>
              </div>

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
                <button type="submit" class="botao_vasado LoadEdtUserCliente">Salvar</button>
              </div>
            </div>
          </form>

        <?php } ?>



        <?php
        // MODAL DO CLIENTE
        if ($_SESSION['us_grupo'] == '3') { ?>

          <form method="post" id="cad_cliente_user" action="Controller/ControllerUsuarios.php?funcao=editUserCliente" class="row g-3 needs-validation" novalidate>

            <input type="hidden" class="form-control" id="id" name="id">
            <input type="hidden" class="form-control" id="" name="cnpj" value="<?= $_SESSION['us_usuario'] ?>">

            <div class="col-lg-12">
              <!-- <label for="nome_completo" class="form-label">Nome <span class="aste-red">*</span></label> -->
              <input type="text" class="form-control campo_form text-uppercase" id="nome_completo" name="nome_completo" placeholder="Nome Completo" disabled>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="col-lg-12">
              <!-- <label for="usuario" class="form-label">Usuário <span class="aste-red">*</span></label> -->
              <input type="text" class="form-control campo_form text-uppercase" id="usuario" name="usuario" placeholder="Usuário" disabled>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="col-lg-12">
              <!-- <label for="email" class="form-label">Email <span class="aste-red">*</span></label> -->
              <input type="text" class="form-control campo_form text-lowercase" id="email" name="email" placeholder="Email" required>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="col-lg-6">
              <!-- <label for="setor" class="form-label">Setor <span class="aste-red">*</span></label> -->
              <input type="text" class="form-control campo_form text-uppercase" id="setor" name="setor" placeholder="Setor" required>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="col-lg-6">
              <!-- <label for="cargo" class="form-label">Cargo <span class="aste-red">*</span></label> -->
              <input type="text" class="form-control campo_form text-uppercase" id="cargo" name="cargo" placeholder="Cargo" required>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

            <div class="row m-0 mt-4 p-0">

              <div class="col-6">
                <!-- <label for="cargo" class="form-label">Acesso <span class="aste-red">*</span></label> -->
                <div class="form-check mb-2">
                  <input class="form-check-input bt_checkbox" type="checkbox" name="acesso[]" value="1" id="documentos">
                  <label class="form-check-label" for="">Documentos</label>
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input bt_checkbox" type="checkbox" name="acesso[]" value="2" id="financeiro">
                  <label class="form-check-label" for="">Financeiro</label>
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input bt_checkbox" type="checkbox" name="acesso[]" value="3" id="pedidos">
                  <label class="form-check-label" for="">Pedidos</label>
                </div>

              </div>

              <div class="col-6">
                <!-- <label for="cargo" class="form-label">Status</label> -->
                <div class="form-check">
                  <input class="form-control" type="hidden" name="" id="status">
                  <input class="form-check-input bt_checkbox" type="checkbox" id="status" disabled>
                  <label class="form-check-label" for="status">Usuário ativo</label>
                </div>
              </div>

            </div>

            <div class="col-12 text-end">
              <div class="modal-footer p-0 pt-3">
                <!-- <small>( <span class="aste-red">*</span> ) Campos obrigatórios</small> -->
                <button type="button" class="botao_cancelar_vasado" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="botao_vasado LoadEdtUserCliente">Salvar</button>
              </div>
            </div>
          </form>

        <?php } ?>

      </div>
    </div>
  </div>
</div>

<script>
  // BOTAO LOAD
  document.querySelector('.LoadEdtUserCliente').addEventListener('click', function() {
    var _this = this;
    _this.classList.add('loading');
    setTimeout(function() {
      _this.classList.remove('loading');
    }, 100000);
  });
</script>


<script type="text/javascript">
  // EDITAR USUARIO CLIENTE
  $('#edtUserClie').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    var nome_completo = button.data('nome_completo')
    var usuario = button.data('usuario')
    var email = button.data('email')
    var grupo = button.data('grupo')
    var setor = button.data('setor')
    var cargo = button.data('cargo')
    var recno = button.data('recno')
    var cnpj_cliente = button.data('cliente')
    var cnpj = button.data('cnpj')
    var acess_doc = button.data('acess_doc')
    var acess_fin = button.data('acess_fin')
    var acess_ped = button.data('acess_ped')
    var status = button.data('status')

    var modal = $(this)
    //modal.find('.modal-title').text('Editar Usuário: ' + nome)
    modal.find('#id').val(id)
    modal.find('#nome_completo').val(nome_completo)
    modal.find('#usuario').val(usuario)
    modal.find('#email').val(email)
    modal.find('#grupo').val(grupo)
    modal.find('#setor').val(setor)
    modal.find('#cargo').val(cargo)
    modal.find('#recno').val(recno)
    modal.find('#cliente').val(cnpj_cliente)
    modal.find('#cnpj').val(cnpj)
    modal.find('#status').val(status)

    //VERIFICA DE O CHECKBOX ESTÁ MARCADO
    if (acess_doc == 1) {
      modal.find('#documentos').prop("checked", true)
    } else {
      modal.find('#documentos').prop("checked", false)
    }

    if (acess_fin == 2) {
      modal.find('#financeiro').prop("checked", true)
    } else {
      modal.find('#financeiro').prop("checked", false)
    }

    if (acess_ped == 3) {
      modal.find('#pedidos').prop("checked", true)
    } else {
      modal.find('#pedidos').prop("checked", false)
    }

    //VERIFICA DE O CHECKBOX ESTÁ MARCADO
    if (status) {
      modal.find('#status').prop("checked", true)
    } else {
      modal.find('#status').prop("checked", false)
    }
  })
</script>