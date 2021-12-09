<!-- MODAL USER CLIENTE -->
<div class="modal fade" id="cadUserCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header header_modal">
        <h5 class="modal-title fs-4" id="exampleModalLabel">CRIAR USUÁRIO</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form method="post" id="cad_cliente_user" action="Controller/ControllerUsuarios.php?funcao=cadUserCliente" class="row g-3 needs-validation" novalidate>

          <input type="hidden" class="form-control" id="grupo" name="grupo" value="4">

          <?php if ($_SESSION['us_grupo'] == '1') { ?>

            <div class="col-md-12">
              <!-- <label for="cnpj" class="form-label">Cliente <span class="aste-red">*</span></label> -->
              <select class="form-select inputColor campo_select" id="cnpj" name="cnpj" required>
                <option selected disabled value="">CLIENTE</option>
                <?php
                $sql = "SELECT us_id, us_cliente, us_nome_completo, us_usuario FROM sys_tb_usuarios WHERE us_grupo = '3' ORDER BY us_usuario";
                $stmt_uc = sqlsrv_query($conn, $sql);
                while ($row = sqlsrv_fetch_array($stmt_uc, SQLSRV_FETCH_ASSOC)) {
                ?>
                  <option value="<?= $row['us_usuario'] ?>"><?= $row['us_nome_completo'] ?></option>
                <?php }
                sqlsrv_free_stmt($stmt_uc); ?>
              </select>
              <div class="invalid-feedback">Campo obrigatório</div>
            </div>

          <?php } else { ?>

            <input type="hidden" class="form-control" id="" name="cnpj" value="<?= $_SESSION['us_usuario'] ?>">

          <?php } ?>


          <div class="col-lg-12">
            <!-- <label for="nome_completo" class="form-label">Nome <span class="aste-red">*</span></label> -->
            <input type="text" class="form-control campo_form text-uppercase" id="" name="nome_completo" placeholder="Nome Completo" required>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>

          <div class="col-lg-12">
            <!-- <label for="usuario" class="form-label">Usuário <span class="aste-red">*</span></label> -->
            <input type="text" class="form-control campo_form text-uppercase" id="" name="usuario" placeholder="Usuário" required>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>

          <div class="col-lg-12">
            <!-- <label for="email" class="form-label">Email <span class="aste-red">*</span></label> -->
            <input type="text" class="form-control campo_form text-lowercase" id="" name="email" placeholder="Email" required>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>

          <div class="col-lg-6">
            <!-- <label for="setor" class="form-label">Setor <span class="aste-red">*</span></label> -->
            <input type="text" class="form-control campo_form text-uppercase" id="" name="setor" placeholder="Setor" required>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>

          <div class="col-lg-6">
            <!-- <label for="cargo" class="form-label">Cargo <span class="aste-red">*</span></label> -->
            <input type="text" class="form-control campo_form text-uppercase" id="" name="cargo" placeholder="Cargo" required>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>

          <div class="row m-0 mt-4 p-0">
            <div class="col-6">
              <!-- <label for="cargo" class="form-label">Acesso <span class="aste-red">*</span></label> -->
              <div class="form-check mb-2">
                <input class="form-check-input bt_checkbox" type="checkbox" name="acesso[]" value="1" id="" checked>
                <label class="form-check-label" for="">Documentos</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input bt_checkbox" type="checkbox" name="acesso[]" value="2" id="" checked>
                <label class="form-check-label" for="">Financeiro</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input bt_checkbox" type="checkbox" name="acesso[]" value="3" id="" checked>
                <label class="form-check-label" for="">Pedidos</label>
              </div>
            </div>

            <div class="col-6">
              <!-- <label for="cargo" class="form-label">Status</label> -->
              <div class="form-check">
                <input class="form-check-input bt_checkbox" type="checkbox" name="status" id="status" <?php if ($_SESSION['us_grupo'] != '1') {
                                                                                                        echo 'disabled';
                                                                                                      } ?>>
                <label class="form-check-label" for="status">Usuário ativo</label>
              </div>
            </div>
          </div>

          <div class="col-12 text-end">
            <div class="modal-footer p-0 pt-3">
              <!-- <small>( <span class="aste-red">*</span> ) Campos obrigatórios</small> -->
              <button type="button" class="botao_cancelar_vasado" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="botao_vasado LoadCadUserCliente">Salvar</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<script>
  // BOTAO LOAD
  document.querySelector('.LoadCadUserCliente').addEventListener('click', function() {
    var _this = this;
    _this.classList.add('loading');
    setTimeout(function() {
      _this.classList.remove('loading');
    }, 100000);
  });
</script>