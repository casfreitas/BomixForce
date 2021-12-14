<?php include 'includes/header.php'; ?>

<?php
//ACESSO RESTRITO AO SITE CASO NÃO SEJA LOGADO
if ((!isset($_SESSION['us_id'])) and (!isset($_SESSION['us_usuario']))) {
  $_SESSION['msg'] = "<div class=\"alert alert-warning mt-3\" role=\"alert\"><p>Acesso restrito!</p></div>";
  header("Location: index.php");
}
?>

<section class="px-2 px-sm-5">
  <div class="container-fluid">
    <h5 class="titulo_pagina">Não Conformidade</h5>
  </div>

  <div class="container-fluid main_paginas px-4 px-sm-5 py-5">

    <?php if ($_SESSION['us_grupo'] == '1' || $_SESSION['us_grupo'] == '2') { ?>

      <section class="campo_tabela">
        <div class="row">

          <!-- <div class="col-xl-8 offset-xl-2"> -->
          <div class="col-xl-12">
            <div class="row nao_conforme_chat">
              <div class="col-12">

                <?php
                include 'conexao/conexao_sqlsrv.php';
                $id = $_GET['id'];
                $sql = "SELECT nc_id, nc_data_entrada, nc_lote, nc_nota, nc_quant, nc_item, nc_status, us_id, us_nome_completo, us_usuario, us_email, nc_descricao, nc_arquivo
                  FROM sys_tb_nao_conforme
                  INNER JOIN sys_tb_usuarios
                  ON sys_tb_nao_conforme.us_fk = sys_tb_usuarios.us_id
                  WHERE nc_id = '$id'
                  ";

                $stmt = sqlsrv_query($conn, $sql);
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

                  $id            = $row['nc_id'];
                  $data_entrada  = $row['nc_data_entrada'];
                  $lote          = $row['nc_lote'];
                  $nota          = $row['nc_nota'];
                  $quantidade    = $row['nc_quant'];
                  $item          = $row['nc_item'];
                  $descricao     = str_replace('<br />', '', $row['nc_descricao']);
                  $status        = $row['nc_status'];
                  $arquivo       = $row['nc_arquivo'];

                  $nome_completo = $row['us_nome_completo'];
                  $usuario       = $row['us_usuario'];
                  $email         = $row['us_email'];
                ?>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="alert alert-success mb-2">
                        <small class="d-block mb-2"><?= $nome_completo . ' - ' . date_format($data_entrada, 'd/m/Y - H:i'); ?></small>
                        <ul>
                          <li> <strong>Lote: </strong><?= $lote ?></li>
                          <li> <strong>Nota: </strong><?= $nota ?></li>
                          <li> <strong>Quantidade: </strong><?= $quantidade ?></li>
                          <li> <strong>Item: </strong><?= $item ?></li>
                        </ul>
                        <p class="mt-2"><?= $descricao ?></p>

                        <?php if (isset($arquivo)) { ?>
                          <div class="arquivo mt-2">
                            <a href="download.php?id=<?= $id ?>&msg=<?= $arquivo ?>"><i class="bi bi-paperclip"></i>Anexo</a>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>

                <?php }
                sqlsrv_free_stmt($stmt); ?>

                <?php
                include 'conexao/conexao_sqlsrv.php';
                $id = $_GET['id'];
                $sql_msg = "SELECT *
                            FROM sys_tb_nao_conforme_msg
                            INNER JOIN sys_tb_nao_conforme
                            ON sys_tb_nao_conforme.nc_id = sys_tb_nao_conforme_msg.nc_fk
                            INNER JOIN sys_tb_usuarios
                            ON sys_tb_usuarios.us_id = sys_tb_nao_conforme_msg.us_fk
                            WHERE nc_fk = '$id'
                            ORDER BY ncm_data_envio ASC
                            ";

                $stmt_msg = sqlsrv_query($conn, $sql_msg);
                while ($row_msg = sqlsrv_fetch_array($stmt_msg, SQLSRV_FETCH_ASSOC)) {

                  $id            = $row_msg['ncm_id'];
                  $id_fk         = $row_msg['nc_fk'];
                  $us_fk         = $row_msg['us_fk'];
                  $msg           = str_replace('<br />', '', $row_msg['ncm_msg']);
                  $arquivo       = $row_msg['ncm_arquivo'];
                  $data_envio    = $row_msg['ncm_data_envio'];

                  $nome_completo = $row_msg['us_nome_completo'];
                  $usuario       = $row_msg['us_usuario'];
                  $grupo         = $row_msg['us_grupo'];
                ?>

                  <div class="row <?php if ($grupo == 1 || $grupo == 2) {
                                    echo 'd-flex flex-row-reverse bd-highligh';
                                  } ?>">
                    <div class="col-md-6">
                      <div class="<?php if ($grupo == 1 || $grupo == 2) {
                                    echo 'chat1';
                                  } else {
                                    echo 'chat2';
                                  } ?>">
                        <small class="d-block <?php if ($grupo == 1 || $grupo == 2) {
                                                echo 'text-white-50';
                                              } else {
                                                echo 'text-muted';
                                              } ?> mb-2"><?= $nome_completo . ' - ' . date_format($data_envio, 'd/m/Y - H:i'); ?></small>
                        <p class="mt-2"><?= $msg ?></p>
                        <?php if (isset($arquivo)) { ?>
                          <div class="arquivo mt-2">
                            <a href="download.php?id=<?= $id_fk ?>&msg=<?= $arquivo ?>"><i class="bi bi-paperclip"></i>Anexo</a>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>

                <?php }
                sqlsrv_free_stmt($stmt_msg); ?>

              </div>
            </div>

            <div class="row mt-4">
              <div class="col">

                <?php if ($status == 'CONCLUÍDO') { ?>
                  <form method="post" action="" class="row g-3 needs-validation" enctype="multipart/form-data" novalidate>
                    <div class="col-lg-6 col-xxl-5 order-lg-1 order-2">
                      <label for="formFile" class="form-label">
                        <strong>Para um melhor atendimento é possível enviar imagens e documentos sobre a não conformidade.</strong>
                      </label>
                      <input class="form-control" type="file" name="arquivo" id="formFile" disabled>
                    </div>
                    <div class="col-lg-6 col-xxl-7 order-lg-2 order-1">
                      <label for="msg" class="form-label">Mensagem <span class="aste-red">*</span></label>
                      <textarea class="form-control campo_textarea" id="msg" name="msg" rows="3" maxlength="500" disabled></textarea>
                      <div class="invalid-feedback">Campo obrigatório</div>
                    </div>
                    <div class="col-md-12 text-end order-3">
                      <div class="row d-flex align-items-center">
                        <div class="col-sm-12 text-sm-start text-center border-0 order-2 order-sm-1 mt-3 mt-sm-0">
                          <div class="card text-white bg-success mb-3">
                            <div class="card-header text-center">Atendimento concluído!</div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                <?php } else { ?>
                  <form method="post" action="Controller/ControllerNaoConforme.php?funcao=cad_nao_conforme_msg" class="row g-3 needs-validation" enctype="multipart/form-data" novalidate>
                    <div class="col-lg-6 col-xxl-5 order-lg-1 order-2">
                      <label for="formFile" class="form-label">
                        <strong>Para um melhor atendimento é possível enviar imagens e documentos sobre a não conformidade.</strong>
                      </label>
                      <input class="form-control campo_form" type="file" name="arquivo" id="formFile">
                      <small>Apenas arquivos .jpg, .jpeg, .png, .pdf, .doc, .docx e .odt são permitidos</small>
                    </div>

                    <div class="col-lg-6 col-xxl-7 order-lg-2 order-1">
                      <input type="hidden" class="form-control" id="" name="id_nc" value="<?= $_GET['id'] ?>">
                      <input type="hidden" class="form-control" id="" name="id_us" value="<?= $_SESSION['us_id'] ?>">
                      <input type="hidden" class="form-control" id="" name="grupo" value="<?= $_SESSION['us_grupo'] ?>">
                      <input type="hidden" class="form-control" name="email" value="<?= $email ?>">
                      <input type="hidden" class="form-control" name="data_entrada" value="<?= date_format($data_entrada, 'Y-m-d') ?>">
                      <input type="hidden" class="form-control" id="" name="status" value="RESPONDIDO">
                      <label for="msg" class="form-label">Mensagem <span class="aste-red">*</span></label>
                      <textarea class="form-control campo_textarea" id="msg" name="msg" rows="2" maxlength="500" required></textarea>
                      <div class="invalid-feedback">Campo obrigatório</div>
                      <small class="mt-0">Máximo 500 caracteres</small>
                    </div>
                    <div class="col-md-12 border-top pt-3 order-3">
                      <div class="row d-flex align-items-center">
                        <div class="col-md-6 text-md-start text-center border-0 order-2 order-md-1 mt-3 mt-md-0">
                          <a href="Controller/ControllerNaoConforme.php?funcao=concluir_atendimento&id=<?= $_GET['id'] ?>" class="conc_nc">
                            <div class="botao_vasado_verde"> Concluir Atendimento</div>
                          </a>
                        </div>
                        <div class="col-md-6 text-md-end text-center order-1 order-md-2">
                          <!-- <small>( <span class="aste-red">*</span> ) Campos obrigatórios</small> -->
                          <button type="button" class="botao_cancelar_vasado me-2" onclick="location.href='nao_conforme.php'" data-bs-dismiss="modal">Cancelar</button>
                          <button type="submit" class="botao_vasado">Enviar</button>
                        </div>
                      </div>
                    </div>
                  </form>
                <?php } ?>
              </div>
            </div>

          </div>
        </div>
  </div>
</section>

<?php } else { ?>

  <section class="campo_tabela">
    <div class="row">

      <!-- <div class="col-xl-8 offset-xl-2"> -->
      <div class="col-xl-12">
        <div class="row nao_conforme_chat">
          <div class="col-12">

            <?php
            include 'conexao/conexao_sqlsrv.php';
            $id = $_GET['id'];
            $sql = "SELECT *
                  FROM sys_tb_nao_conforme
                  INNER JOIN sys_tb_usuarios
                  ON sys_tb_nao_conforme.us_fk = sys_tb_usuarios.us_id
                  WHERE nc_id = '$id'
                  ";

            $stmt = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

              $id            = $row['nc_id'];
              $data_entrada  = $row['nc_data_entrada'];
              $lote          = $row['nc_lote'];
              $nota          = $row['nc_nota'];
              $quantidade    = $row['nc_quant'];
              $item          = $row['nc_item'];
              $descricao     = str_replace('<br />', '', $row['nc_descricao']);
              $status        = $row['nc_status'];
              $arquivo       = $row['nc_arquivo'];

              $nome_completo = $row['us_nome_completo'];
              $usuario       = $row['us_usuario'];
            ?>

              <div class="row d-flex flex-row-reverse bd-highligh">
                <div class="col-md-6">
                  <div class="alert alert-success mb-2">
                    <small class="d-block mb-2"><?= $nome_completo . ' - ' . date_format($data_entrada, 'd/m/Y - H:i'); ?></small>
                    <ul>
                      <li> <strong>Lote: </strong><?= $lote ?></li>
                      <li> <strong>Nota: </strong><?= $nota ?></li>
                      <li> <strong>Quantidade: </strong><?= $quantidade ?></li>
                      <li> <strong>Item: </strong><?= $item ?></li>
                    </ul>
                    <p class="mt-2"><?= $descricao ?></p>

                    <?php if (isset($arquivo)) { ?>
                      <div class="arquivo mt-2">
                        <a href="download.php?id=<?= $id ?>&msg=<?= $arquivo ?>"><i class="bi bi-paperclip"></i>Anexo</a>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>

            <?php }
            sqlsrv_free_stmt($stmt); ?>


            <?php
            include 'conexao/conexao_sqlsrv.php';
            $id = $_GET['id'];
            $sql_msg = "SELECT *
                      FROM sys_tb_nao_conforme_msg
                      INNER JOIN sys_tb_nao_conforme
                      ON sys_tb_nao_conforme.nc_id = sys_tb_nao_conforme_msg.nc_fk
                      INNER JOIN sys_tb_usuarios
                      ON sys_tb_usuarios.us_id = sys_tb_nao_conforme_msg.us_fk
                      WHERE nc_fk = '$id'
                      ORDER BY ncm_data_envio ASC
                      ";

            $stmt_msg = sqlsrv_query($conn, $sql_msg);
            while ($row_msg = sqlsrv_fetch_array($stmt_msg, SQLSRV_FETCH_ASSOC)) {

              $id            = $row_msg['ncm_id'];
              $id_fk         = $row_msg['nc_fk'];
              $us_fk         = $row_msg['us_fk'];
              $msg           = str_replace('<br />', '', $row_msg['ncm_msg']);
              $arquivo       = $row_msg['ncm_arquivo'];
              $data_envio    = $row_msg['ncm_data_envio'];

              $nome_completo = $row_msg['us_nome_completo'];
              $usuario       = $row_msg['us_usuario'];
              $grupo         = $row_msg['us_grupo'];
            ?>

              <div class="row <?php if ($grupo == 3 || $grupo == 4) {
                                echo 'd-flex flex-row-reverse bd-highligh';
                              } ?>">
                <div class="col-md-6">
                  <div class="<?php if ($grupo == 1 || $grupo == 2) {
                                echo 'chat1';
                              } else {
                                echo 'chat2';
                              } ?>">
                    <small class="d-block <?php if ($grupo == 1 || $grupo == 2) {
                                            echo 'text-white-50';
                                          } else {
                                            echo 'text-muted';
                                          } ?> mb-2"><?= $nome_completo . ' - ' . date_format($data_envio, 'd/m/Y - H:i'); ?></small>
                    <p class="mt-2"><?= $msg ?></p>
                    <?php if (isset($arquivo)) { ?>
                      <div class="arquivo mt-2">
                        <a href="download.php?id=<?= $id_fk ?>&msg=<?= $arquivo ?>"><i class="bi bi-paperclip"></i>Anexo</a>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>

            <?php }
            sqlsrv_free_stmt($stmt_msg); ?>


          </div>
        </div>

        <div class="row mt-4">
          <div class="col">
            <?php if ($status == 'CONCLUÍDO') { ?>
              <form method="post" action="" class="row g-3 needs-validation" enctype="multipart/form-data" novalidate>
                <div class="col-lg-6 col-xxl-5 order-lg-1 order-2">
                  <label for="formFile" class="form-label">
                    <strong>Para um melhor atendimento é possível enviar imagens e documentos sobre a não conformidade.</strong>
                  </label>
                  <input class="form-control" type="file" name="arquivo" id="formFile" disabled>
                </div>
                <div class="col-lg-6 col-xxl-7 order-lg-2 order-1">
                  <label for="msg" class="form-label">Mensagem <span class="aste-red">*</span></label>
                  <textarea class="form-control campo_textarea" id="msg" name="msg" rows="3" maxlength="500" disabled></textarea>
                  <div class="invalid-feedback">Campo obrigatório</div>
                </div>
                <div class="col-md-12 text-end order-3">
                  <div class="row d-flex align-items-center">
                    <div class="col-sm-12 text-sm-start text-center border-0 order-2 order-sm-1 mt-3 mt-sm-0">
                      <div class="card text-white bg-success mb-3">
                        <div class="card-header text-center">Atendimento concluído!</div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            <?php } else { ?>
              <form method="post" action="Controller/ControllerNaoConforme.php?funcao=cad_nao_conforme_msg" class="row g-3 needs-validation" enctype="multipart/form-data" novalidate>
                <div class="col-lg-6 col-xxl-5 order-lg-1 order-2">
                  <label for="formFile" class="form-label">
                    <strong>Para um melhor atendimento é possível enviar imagens e documentos sobre a não conformidade.</strong>
                  </label>
                  <input class="form-control campo_form" type="file" name="arquivo" id="formFile">
                  <small>Apenas arquivos .jpg, .jpeg, .png, .pdf, .doc, .docx e .odt são permitidos</small>
                </div>
                <div class="col-lg-6 col-xxl-7 order-lg-2 order-1">
                  <input type="hidden" class="form-control" id="" name="id_nc" value="<?= $_GET['id'] ?>">
                  <input type="hidden" class="form-control" id="" name="id_us" value="<?= $_SESSION['us_id'] ?>">
                  <input type="hidden" class="form-control" id="" name="grupo" value="<?= $_SESSION['us_grupo'] ?>">
                  <input type="hidden" class="form-control" id="" name="status" value="ABERTO">
                  <label for="msg" class="form-label">Mensagem <span class="aste-red">*</span></label>
                  <textarea class="form-control campo_textarea" id="msg" name="msg" rows="2" maxlength="500" required></textarea>
                  <div class="invalid-feedback">Campo obrigatório</div>
                  <small class="mt-0">Máximo 500 caracteres</small>
                </div>
                <div class="col-md-12 border-top pt-3 order-3">
                  <div class="row d-flex align-items-center">
                    <div class="col-md-12 text-end order-1 order-md-2">
                      <!-- <small>( <span class="aste-red">*</span> ) Campos obrigatórios</small> -->
                      <button type="button" class="botao_cancelar_vasado me-2" onclick="location.href='nao_conforme.php'" data-bs-dismiss="modal">Cancelar</button>
                      <button type="submit" class="botao_vasado">Enviar</button>
                    </div>
                  </div>
                </div>
              </form>
            <?php } ?>
          </div>
        </div>

      </div>
    </div>
    </div>
  </section>


<?php } ?>

</section>


<?php include 'includes/footer.php'; ?>

<!-- VALIDA FORMULÁRIO -->
<script src="dist/js/valida_form.js"></script>

<!-- CHAT ABRE COM BOTTOM ZERO -->
<script>
  $('.nao_conforme_chat').scrollTop($('.nao_conforme_chat')[0].scrollHeight);
</script>