<!-- MODAL CADASTRO CLIENTE -->
<div class="modal fade" id="cadCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header header_modal">
        <h5 class="modal-title fs-4" id="exampleModalLabel">CRIAR CLIENTE</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <!-- IMPEDE FINALIZAR CADASTRO CLICANDO NA TACLA 'ENTER' -> onkeydown="return event.key != 'Enter';" -->
        <form method="post" id="cad_cliente" action="Controller/ControllerUsuarios.php?funcao=cad_cliente" class="row g-3 needs-validation" onkeydown="return event.key != 'Enter';" novalidate>

          <input type="hidden" class="form-control" id="grupo" name="grupo" value="3">

          <div class="col-lg-12">
            <!-- <label for="nome_completo" class="form-label">Cliente <span class="aste-red">*</span></label> -->
            <input list="clientes" type="text" class="form-control inputColor text-uppercase" id="nome_cliente" name="nome_completo" placeholder="Nome Completo" required>
            <datalist id="clientes">

              <?php
              include 'conexao/conexao_sqlsrv.php';
              $sql_cl = "SELECT CNPJ, Recno, RazaoSocial, NomeFantasia, Email, Status
                         FROM Bomixbi.dbo.Fat_TB_Cliente
                         WHERE Status = 'ATIVO' AND CNPJ <> ''
                         ORDER BY NomeFantasia";
              $stmt_cl = sqlsrv_query($conn, $sql_cl);
              while ($row = sqlsrv_fetch_array($stmt_cl, SQLSRV_FETCH_ASSOC)) {
              ?>

                <option value="<?= $row['NomeFantasia'] ?>">

                <?php }
              sqlsrv_free_stmt($stmt_cl); ?>

            </datalist>
            <div class="invalid-feedback">Campo obrigatório</div>
          </div>

          <div class="col-lg-12">
            <!-- <label for="usuario" class="form-label">Usuário <span class="aste-red">*</span></label> -->
            <input type="text" class="form-control campo_form text-uppercase" id="usuario" name="usuario" placeholder="Usuário" readonly>
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
              <button type="submit" class="botao_vasado LoadCadCliente">Salvar</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>