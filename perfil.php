<?php include 'includes/header.php'; ?>

<section class="px-2 px-sm-5">
  <div class="container-fluid">
    <h5 class="titulo_pagina">Configurações e Conta</h5>
  </div>
  <div class="container-fluid main_paginas px-4 px-sm-5 py-5">




    <?php
    include 'conexao/conexao_sqlsrv.php';
    $id = $_SESSION['us_id'];
    $sql = "SELECT * FROM sys_tb_usuarios WHERE us_id = '$id'";

    //CONFIGURAÇÃO DO TIPO DE USUÁRIO
    $papel = array('1' => 'ADMINISTRADOR', '2' => 'COMERCIAL', '3' => 'CLIENTE', '4' => 'USUÁRIO');

    $stmt = sqlsrv_query($conn, $sql);
    while ($row_user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

      $id            = $row_user['us_id'];
      $cnpj_cliente  = $row_user['us_identificador'];
      $cliente       = $row_user['us_cliente'];
      $ultimo_login  = $row_user['us_last_login'];
      $nome_completo = $row_user['us_nome_completo'];
      $usuario       = $row_user['us_usuario'];
      $email         = $row_user['us_email'];
      $nivel         = $row_user['us_nivel'];
      $status        = $row_user['us_status'];
      $grupo         = $row_user['us_grupo'];
      $data_entrada  = $row_user['us_data_entrada'];
    }
    sqlsrv_free_stmt($stmt);

    ?>


    <div class="row m-0">
      <div class="col-md-6 offset-md-3">
        <div class="row justify-content-center g-0 m-0">

          <ul class="nav nav-pills botoes_tabs mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="botao_vasado active me-2" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Conta</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="botao_vasado" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Alterar senha</button>
            </li>
          </ul>

          <div class="tab-content" id="pills-tabContent">

            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
              <div class="card text-dark mb-3">
                <div class="card-header header_tabs">Informações da conta</div>
                <div class="card-body text-dark">
                  <form method="post" action="Controller/ControllerUsuarios.php?funcao=edit_perfil" class="row g-3 needs-validation" novalidate>

                    <div class="col-md-12">
                      <input type="hidden" class="form-control" id="id" name="id" value="<?= $_SESSION['us_id']; ?>" required>
                      <label for="usuario" class="form-label">Nome</label>
                      <input type="text" class="form-control campo_form" id="nome_completo" name="nome_completo" value="<?= $nome_completo; ?>" disabled>
                    </div>

                    <div class="col-md-12">
                      <label for="usuario" class="form-label">Usuário</label>
                      <input type="text" class="form-control campo_form" id="nome_completo" name="nome_completo" value="<?= $_SESSION['us_usuario']; ?>" disabled>
                    </div>

                    <div class="col-md-12">
                      <label for="email" class="form-label">Email</label>
                      <input type="text" class="form-control campo_form" id="" name="" value="<?= $email; ?>" disabled>
                    </div>

                    <div class="col-md-12">
                      <label for="email" class="form-label">Grupo</label>
                      <input type="text" class="form-control campo_form" id="" name="" value="<?= $papel[$grupo]; ?>" disabled>
                    </div>

                    <div class="col-md-12">
                      <label for="email" class="form-label">Último acesso</label>
                      <input type="text" class="form-control campo_form" id="" name="" value="<?= date_format($ultimo_login, 'd/m/Y H:i:s'); ?>" disabled>
                    </div>

                    <!-- <div class="col-12 text-end">
                    <div class="modal-footer justify-content-between p-0 border-0">
                      <small>( <span class="aste-red">*</span> ) Campos obrigatórios</small>
                      <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                  </div> -->

                  </form>
                </div>
              </div>
            </div>

            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
              <div class="card text-dark mb-3">
                <div class="card-header header_tabs">Alterar Senha</div>
                <div class="card-body text-dark">
                  <form method="post" id="cadSenha" action="Controller/ControllerUsuarios.php?funcao=edit_senha" class="row g-3 needs-validation" onSubmit="return val_senha(this);" name="valida_senha" novalidate>

                    <input type="hidden" class="form-control" id="id" name="id" value="<?= $_SESSION['us_id']; ?>" required>

                    <div class="col-md-12">
                      <!-- <label class="form-label">Nova senha <span class="aste-red">*</span></label> -->
                      <input type="password" class="form-control campo_form" id="" name="senha" minlength="4" maxlength="8" size="8" placeholder="Nova senha" required>
                      <div class="invalid-feedback">Digite min: 4 / max: 8 caracteres</div>
                    </div>

                    <div class="col-md-12">
                      <!-- <label class="form-label">Confirme a nova senha <span class="aste-red">*</span></label> -->
                      <input type="password" class="form-control campo_form" id="" name="rep_senha" minlength="4" maxlength="8" size="8" placeholder="Confirme a nova senha" required>
                      <div class="invalid-feedback">Digite min: 4 / max: 8 caracteres</div>
                    </div>

                    <div class="col-12 text-end">
                      <!-- <small>( <span class="aste-red">*</span> ) Campos obrigatórios</small> -->
                      <button type="submit" class="botao_vasado">Salvar</button>
                    </div>

                  </form>
                </div>
              </div>
            </div>

          </div>

        </div>
      </div>
    </div>

  </div>

</section>

<?php include 'includes/footer.php'; ?>

<?php include 'includes/modal/contatos/cad_contato.php'; ?>

<?php include 'includes/modal/contatos/edit_contato.php'; ?>


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

<!-- VALIDA FORMULÁRIO -->
<script src="dist/js/valida_form.js"></script>