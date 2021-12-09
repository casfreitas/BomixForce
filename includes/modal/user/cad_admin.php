<!-- MODAL CADASTRA ADMINISTRADOR -->
<div class="modal fade" id="cadAdmin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header header_modal">
        <h5 class="modal-title fs-4" id="exampleModalLabel">CRIAR ADMINISTRADOR</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <!-- IMPEDE FINALIZAR CADASTRO CLICANDO NA TACLA 'ENTER' -> onkeydown="return event.key != 'Enter';" -->
        <form method="post" id="cad_admin" action="Controller/ControllerUsuarios.php?funcao=cad_admin" class="row g-3 needs-validation" onkeydown="return event.key != 'Enter';" novalidate>

          <input type="hidden" class="form-control" id="grupo" name="grupo" value="1">
          <input type="hidden" class="form-control" id="identificador" name="identificador">

          <div class="col-lg-12">
            <!-- <label for="nome_completo" class="form-label">Nome <span class="aste-red">*</span></label> -->
            <input list="browsers" type="text" class="form-control inputColor text-uppercase" id="nome_completo" name="nome_completo" placeholder="Nome" required>
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
              <button type="submit" class="botao_vasado LoadCadAdmin">Salvar</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>