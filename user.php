<?php include 'includes/header.php'; ?>

<?php
//ACESSO RESTRITO AO SITE CASO NÃO SEJA LOGADO
if ((!isset($_SESSION['us_id'])) and (!isset($_SESSION['us_usuario']))) {
  $_SESSION['msg'] = "<div class=\"alert alert-warning mt-3\" role=\"alert\"><p>Acesso restrito!</p></div>";
  header("Location: index.php");
}

//ACESSO RESTRITO AO GRUPO 'USUÁRIOS'
if ($_SESSION['us_grupo'] === '4') {
  $_SESSION['msg'] = "<div class=\"alert alert-warning mt-3\" role=\"alert\"><p>Acesso restrito!</p></div>";
  header("Location: sair.php");
}
?>

<section class="px-2 px-sm-5">
  <div class="container-fluid">
    <h5 class="titulo_pagina">Usuários</h5>
  </div>
  <div class="container-fluid main_paginas px-4 px-sm-5 py-5">

    <?php
    // SÓ O ADMINISTRADOR MASTER TEM ACESSO A TODOS OS CADASTRO DE USUÁRIO 
    if ($_SESSION['us_grupo'] == 1) { ?>

      <div class="dropdown dropstart">
        <div class="bt_cad_padao" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" style="margin-bottom: 20px; margin-top: -30px; margin-left: auto;">
          <img src="dist/images/bt_cad_user.svg" alt="">
        </div>
        <ul class="dropdown-menu drop_user" aria-labelledby="dropdownMenuButton1">
          <?php
          // APENAS O ADMINISTRADOR MASTER TEM ACESSO
          if ($_SESSION['us_nivel'] == 1) { ?>
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#cadAdmin">ADMINISTRADOR</a></li>
          <?php } ?>
          <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#cadComercial">COMERCIAL</a></li>
          <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#cadCliente">CLIENTE</a></li>
          <?php
          // APENAS O ADMINISTRADOR MASTER TEM ACESSO
          if ($_SESSION['us_nivel'] == 1) { ?>
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#cadUserCliente">USUÁRIO CLIENTE</a></li>
          <?php } ?>
        </ul>
      </div>

    <?php } ?>

    <?php
    // BOTÃO PARA O CLIENTE CADASTRAR SEUS USUÁRIOS 
    if ($_SESSION['us_grupo'] == 3) { ?>

      <div class="bt_cad_padao pe-auto" data-bs-toggle="modal" data-bs-target="#cadUserCliente" style="margin-bottom: 20px; margin-top: -30px; margin-left: auto;">
        <img src="dist/images/bt_cad_user.svg" alt="">
      </div>

    <?php } ?>

    <section class="campo_tabela">

      <div class="table-responsive pb-4">
        <table id="tabela" class="table tabela table-striped table-hover display">
          <thead>
            <tr>
              <th scope="col">Nome</th>
              <th scope="col">Usuário</th>
              <th scope="col">Grupo</th>
              <th scope="col">Cliente</th>
              <th scope="col">Email</th>
              <th scope="col">Último Acesso</th>
              <th scope="col">Status</th>

              <?php
              // AÇÕES DO ADMINISTRADOR
              if ($_SESSION['us_grupo'] == '1') { ?>
                <th scope="col" width="80px">Ações</th>
              <?php } ?>

              <?php
              // AÇÕES DO COMERCIAL
              if ($_SESSION['us_grupo'] == '2') { ?>
                <th scope="col" width="40px">Ações</th>
              <?php } ?>

              <?php
              // AÇÕES DO CLIENTE 
              if ($_SESSION['us_grupo'] == '3') { ?>
                <th scope="col" width="80px">Ações</th>
              <?php } ?>

            </tr>
          </thead>
          <tbody>

            <?php
            include 'conexao/conexao_sqlsrv.php';
            //CONSULTA DO ADMINISTRADOR MASTER
            if ($_SESSION['us_nivel'] == '1') {
              $usuario_logado = $_SESSION['us_id'];
              $sql = "SELECT u.us_id, u.us_identificador, u.us_cliente, u.us_last_login, u.us_nome_completo, u.us_usuario, u.us_email, u.us_nivel, u.us_status, u.us_grupo, u.us_data_entrada, e.us_fk, e.ue_setor, e.ue_cargo, e.ue_recno, e.ue_cliente, e.ue_cnpj, e.ue_permissao
                      FROM sys_tb_usuarios u
                      LEFT JOIN sys_tb_usuario_empresa e
                      ON e.us_fk = u.us_id
                      WHERE u.us_nivel != 1
                      AND u.us_id != '$usuario_logado'
                      ";
            }

            //CONSULTA DO ADMINISTRADOR
            if ($_SESSION['us_nivel'] <> '1') {
              $usuario_logado = $_SESSION['us_id'];
              $sql = "SELECT u.us_id, u.us_identificador, u.us_cliente, u.us_last_login, u.us_nome_completo, u.us_usuario, u.us_email, u.us_nivel, u.us_status, u.us_grupo, u.us_data_entrada, e.us_fk, e.ue_setor, e.ue_cargo, e.ue_recno, e.ue_cliente, e.ue_cnpj, e.ue_permissao
                      FROM sys_tb_usuarios u
                      LEFT JOIN sys_tb_usuario_empresa e
                      ON e.us_fk = u.us_id
                      WHERE u.us_grupo != 1
                      AND u.us_id != '$usuario_logado'
                      ";
            }

            //CONSULTA DO COMERCIAL
            if ($_SESSION['us_grupo'] == '2') {
              $usuario_logado = $_SESSION['us_id'];
              $sql = "SELECT u.us_id, u.us_identificador, u.us_cliente, u.us_last_login, u.us_nome_completo, u.us_usuario, u.us_email, u.us_nivel, u.us_status, u.us_grupo, u.us_data_entrada, e.us_fk, e.ue_setor, e.ue_cargo, e.ue_recno, e.ue_cliente, e.ue_cnpj, e.ue_permissao
                      FROM sys_tb_usuarios u
                      LEFT JOIN sys_tb_usuario_empresa e
                      ON e.us_fk = u.us_id
                      WHERE u.us_nivel != 1
                      AND us_grupo != 1 
                      AND us_grupo != 2 
                      --AND u.us_id != '$usuario_logado'
                      ";
            }

            //CONSULTA DO CLIENTE
            if ($_SESSION['us_grupo'] == '3') {
              $usuario_logado = $_SESSION['us_id'];
              $nome_completo = $_SESSION['us_nome_completo'];
              $sql = "SELECT u.us_id, u.us_identificador, u.us_cliente, u.us_last_login, u.us_nome_completo, u.us_usuario, u.us_email, u.us_nivel, u.us_status, u.us_grupo, u.us_data_entrada, e.us_fk, e.ue_setor, e.ue_cargo, e.ue_recno, e.ue_cliente, e.ue_cnpj, e.ue_permissao
                      FROM sys_tb_usuarios u
                      LEFT JOIN sys_tb_usuario_empresa e
                      ON e.us_fk = u.us_id
                      WHERE u.us_nivel != 1
                      AND u.us_id != '$usuario_logado'
                      AND u.us_cliente = '$nome_completo' OR u.us_nome_completo = '$nome_completo' 
                      AND u.us_grupo = 4                   
                      ";
            }

            //CONFIGURAÇÃO DO TIPO DE USUÁRIO
            $papel = array('1' => 'ADMINISTRADOR', '2' => 'COMERCIAL', '3' => 'CLIENTE', '4' => 'USUÁRIO');

            //CONFIGURAÇÃO DO STATUS
            $sta = array('1' => 'ATIVO', '0' => 'INATIVO');

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

              $id_emp        = $row_user['us_fk'];
              $setor         = $row_user['ue_setor'];
              $cargo         = $row_user['ue_cargo'];
              $recno         = $row_user['ue_recno'];
              $cnpj          = $row_user['ue_cnpj'];
              $acesso        = $row_user['ue_permissao'];

              //TRATA VALORES DO CHECKBOX
              @$valor_acesso = explode(",", $acesso);
              @$acess_doc = $valor_acesso[0];
              @$acess_fin = $valor_acesso[1];
              @$acess_ped = $valor_acesso[2];

              if (@$valor_acesso[0] == 1 || @$valor_acesso[1] == 1 || @$valor_acesso[3] == 1) {
                $acess_doc = 1;
              }
              if (@$valor_acesso[0] == 2 || @$valor_acesso[1] == 2 || @$valor_acesso[3] == 2) {
                $acess_fin = 2;
              }
              if (@$valor_acesso[0] == 3 || @$valor_acesso[1] == 3 || @$valor_acesso[3] == 3) {
                $acess_ped = 3;
              }

              //COR DO GRUPO
              $cor_grupo = $grupo;
              if ($grupo == '1') {
                $cor_grupo = "bg-teal";
              }
              if ($grupo == '2') {
                $cor_grupo = "bg-blue";
              }
              if ($grupo == '3') {
                $cor_grupo = "bg-yellow text-dark";
              }
              if ($grupo == '4') {
                $cor_grupo = "bg-purple";
              }

              //COR DO STATUS
              $cor_status = $status;
              if ($status == '1') {
                $cor_status = "bg-green";
              } else {
                $cor_status = "bg-red";
              }
            ?>

              <tr>
                <td style="max-width: 200px; min-width: 200px"><?= $nome_completo ?></th>
                <td nowrap="nowrap"><?= $usuario ?></td>
                <td nowrap="nowrap"><span class="badge <?= $cor_grupo ?> p-2"><?= $papel[$grupo] ?></span></td>
                <td nowrap="nowrap"><?= $cliente ?></td>
                <td><?= $email ?></td>
                <td nowrap="nowrap"><?php if (isset($ultimo_login)) {
                                      echo date_format($ultimo_login, 'Y/m/d H:i:s');
                                    } else {
                                      echo 'Sem acesso';
                                    } ?></td>
                <td nowrap="nowrap"><span class="badge <?= $cor_status ?> p-2"><?= $sta[$status] ?></span></td>

                <?php
                // BOTÕES DO ADMINISTRADOR 
                if ($_SESSION['us_grupo'] == '1' || $_SESSION['us_grupo'] == '3') {
                ?>

                  <td>
                    <div class="row d-flex align-items-center justify-content-end bt_tabela">
                      <?php
                      // BOTÕES ALTERAR SENHA
                      if ($_SESSION['us_nivel'] == '1') { ?>
                        <div class="col-4 px-1 text-center">
                          <a href="Controller/ControllerUsuarios.php?us_id=<?= $id ?>" data-bs-toggle="modal" data-bs-target="#cadSenha" data-id="<?= $id ?>" data-nome_completo="<?= $nome_completo ?>">
                            <i class="bi bi-key-fill fs-3"></i>
                          </a>
                        </div>
                      <?php } ?>

                      <div class="<?php if ($_SESSION['us_grupo'] == '1' || $_SESSION['us_grupo'] == '3') {
                                    echo 'col-4 px-1';
                                  } else {
                                    echo 'col-12';
                                  } ?> p-0 text-center">
                        <a href="#" data-bs-toggle="modal" data-bs-target="
                  <?php
                  if ($grupo == '1') {
                    echo '#edtAdmin';
                  }
                  if ($grupo == '2') {
                    echo '#edtComer';
                  }
                  if ($grupo == '3') {
                    echo '#edtClie';
                  }
                  if ($grupo == '4') {
                    echo '#edtUserClie';
                  }
                  ?>" data-id="<?= $id ?>" data-cnpj_cliente="<?= $cnpj_cliente ?>" data-cliente="<?= $cliente ?>" data-nome_completo="<?= $nome_completo ?>" data-nome_cliente="<?= $nome_completo ?>" data-usuario="<?= $usuario ?>" data-email="<?= $email ?>" data-status="<?= $status ?>" data-grupo="<?= $grupo ?>" data-setor="<?= $setor ?>" data-cargo="<?= $cargo ?>" data-recno="<?= $recno ?>" data-cnpj="<?= $cnpj ?>" data-acess_doc="<?= $acess_doc ?>" data-acess_fin="<?= $acess_fin ?>" data-acess_ped="<?= $acess_ped ?>"><i class="bi bi-justify fs-4"></i>
                        </a>
                      </div>

                      <?php
                      // APENAS ADMINISTRADOR MASTER E O CLIENTE PODEM VER O BOTÃO EXCLUIR
                      if ($_SESSION['us_grupo'] == '1' || $_SESSION['us_grupo'] == '3') { ?>
                        <div class="col-4 px-1 text-center">
                          <?php
                          // SE O USUARIO A DELETAR FOR UM CLIENTE, RECEBE UM ALERTA DIERENCIADO
                          if ($grupo == '3') { ?>
                            <a href="Controller/ControllerUsuarios.php?us_id=<?= $id ?>&cnpj=<?= $usuario ?>" class="del-btn-cliente">
                              <i class="bi bi-trash-fill fs-5"></i>
                            </a>
                          <?php } else { ?>
                            <a href="Controller/ControllerUsuarios.php?us_id=<?= $id ?>" class="del-btn">
                              <i class="bi bi-trash-fill fs-5"></i>
                            </a>
                          <?php } ?>
                        </div>
                      <?php } ?>

                    </div>
                  </td>

                <?php } ?>

                <?php
                // BOTÕES DO ADMINISTRADOR 
                if ($_SESSION['us_grupo'] == '2') {
                ?>

                  <td>
                    <div class="row d-flex align-items-center bt_tabela">
                      <div class="col-12 p-0 text-center">
                        <a href="#" data-bs-toggle="modal" data-bs-target="
                  <?php
                  if ($grupo == '1') {
                    echo '#edtAdmin';
                  }
                  if ($grupo == '2') {
                    echo '#edtComer';
                  }
                  if ($grupo == '3') {
                    echo '#edtClie';
                  }
                  if ($grupo == '4') {
                    echo '#edtUserClie';
                  }
                  ?>" data-id="<?= $id ?>" data-cnpj_cliente="<?= $cnpj_cliente ?>" data-cliente="<?= $cliente ?>" data-nome_completo="<?= $nome_completo ?>" data-nome_cliente="<?= $nome_completo ?>" data-usuario="<?= $usuario ?>" data-email="<?= $email ?>" data-status="<?= $status ?>" data-grupo="<?= $grupo ?>" data-setor="<?= $setor ?>" data-cargo="<?= $cargo ?>" data-recno="<?= $recno ?>" data-cnpj="<?= $cnpj ?>" data-acess_doc="<?= $acess_doc ?>" data-acess_fin="<?= $acess_fin ?>" data-acess_ped="<?= $acess_ped ?>"><i class="bi bi-justify fs-4"></i>
                        </a>
                      </div>
                    </div>
                  </td>

                <?php } ?>

              </tr>

            <?php }
            sqlsrv_free_stmt($stmt); ?>

          </tbody>
        </table>
      </div>

    </section>

  </div>
</section>


<!-- TABLE -->
<script src="dist/js/table/jquery-3.5.1.js"></script>
<script type="text/javascript" src="dist/js/table/datatables.min.js"></script>
<script>
  var table$ = jQuery.noConflict();
  table$(document).ready(function() {
    table$('#tabela').DataTable({
      // "order": [[ 1, 'asc' ]],
      "lengthMenu": [
        [10, 15, 20, 25, 30, 50, 100, -1],
        [10, 15, 20, 25, 30, 50, 100, "Todos"]
      ],
      "language": {
        "sProcessing": "Procurando...",
        "lengthMenu": "Mostrar _MENU_ registros",
        "zeroRecords": "Nenhum registro encontrado",
        "search": "Procurar: ",
        "info": "Mostrar _START_ até _END_ de _TOTAL_ registros",
        "infoEmpty": "Nenhum registro encontrad",
        "infoFiltered": "(filtrado de _MAX_ registros totais)",
        "paginate": {
          "first": "Primeiro",
          "last": "Último",
          "next": "Próximo",
          "previous": "Anterior"
        },
      }
    });

  });
</script>






<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>

<!-- JQUERY EDITAR MODAL -->
<script src="dist/js/table/jquery-3.5.1.js"></script>

<!--***************************************
 ************* CADASTRO *******************
 ***************************************-->

<?php
// APENAS ADMINISTRADOR MASTER PODE CADASTRAR UM ADMINISTRADOR
if ($_SESSION['us_nivel'] == '1') { ?>
  <?php include 'includes/modal/user/cad_admin.php'; ?>

  <script>
    // BOTAO LOAD
    document.querySelector('.LoadCadAdmin').addEventListener('click', function() {
      var _this = this;
      _this.classList.add('loading');
      setTimeout(function() {
        _this.classList.remove('loading');
      }, 100000);
    });
  </script>

  <!-- PREENCHE OS CAMPOS DOS CADASTROS DE USUARIOS 'ADMIN' E 'COMERCIAL' -->
  <script>
    $(document).ready(function() {
      $("input[id='nome_completo']").blur(function() {
        var $identificador = $("input[id='identificador']");
        var $usuario = $("input[id='usuario']");
        var $email = $("input[id='email']");
        var nome_completo = $(this).val();

        $.getJSON('Controller/procura_user.php', {
            nome_completo
          },
          function(retorno) {
            $identificador.val(retorno.identificador);
            $usuario.val(retorno.usuario);
            $email.val(retorno.email);
          }
        );
      });
    });
  </script>
<?php } ?>

<?php
// ACESSO AO MODEL DE CADASTRO DO COMERCIAL BOMIX
if ($_SESSION['us_grupo'] == '1') { ?>
  <?php include 'includes/modal/user/cad_comercial.php'; ?>

  <script>
    // BOTAO LOAD
    document.querySelector('.LoadCadComercial').addEventListener('click', function() {
      var _this = this;
      _this.classList.add('loading');
      setTimeout(function() {
        _this.classList.remove('loading');
      }, 100000);
    });
  </script>

  <!-- PREENCHE OS CAMPOS DOS CADASTROS DE USUARIOS 'ADMIN' E 'COMERCIAL' -->
  <script>
    $(document).ready(function() {
      $("input[id='nome_completo']").blur(function() {
        var $identificador = $("input[id='identificador']");
        var $usuario = $("input[id='usuario']");
        var $email = $("input[id='email']");
        var nome_completo = $(this).val();

        $.getJSON('Controller/procura_user.php', {
            nome_completo
          },
          function(retorno) {
            $identificador.val(retorno.identificador);
            $usuario.val(retorno.usuario);
            $email.val(retorno.email);
          }
        );
      });
    });
  </script>

<?php } ?>

<?php
// ACESSO AO MODEL DE CADASTRO DO CLIENTE
if ($_SESSION['us_grupo'] == '1') { ?>
  <?php include 'includes/modal/user/cad_cliente.php'; ?>

  <!-- PREENCHE OS CAMPOS DOS CADASTROS DE USUARIO 'CLIENTE' -->
  <script>
    $(document).ready(function() {
      $("input[id='nome_cliente']").blur(function() {
        var $usuario = $("input[id='usuario']");
        var $email = $("input[id='email']");
        var nome_cliente = $(this).val();

        $.getJSON('Controller/procura_cliente.php', {
            nome_cliente
          },
          function(retorno) {
            $usuario.val(retorno.usuario);
            $email.val(retorno.email);
          }
        );
      });
    });
  </script>
<?php } ?>


<script>
  // BOTAO LOAD
  document.querySelector('.LoadCadCliente').addEventListener('click', function() {
    var _this = this;
    _this.classList.add('loading');
    setTimeout(function() {
      _this.classList.remove('loading');
    }, 100000);
  });
</script>



<?php
// ACESSO AO MODEL DE CADASTRO DO USUÁRIO DO  CLIENTE
if ($_SESSION['us_grupo'] == '3' || $_SESSION['us_nivel'] == '1') { ?>
  <?php include 'includes/modal/user/cad_cliente_user.php'; ?>
<?php } ?>



<!--***************************************
 ************* EDIÇÃO *********************
 ***************************************-->

<?php
// APENAS ADMINISTRADOR MASTER PODE EDITAR UM ADMINISTRADOR
if ($_SESSION['us_nivel'] == '1') { ?>
  <?php include 'includes/modal/user/edit_admin.php'; ?>
<?php } ?>

<?php
// ACESSO AO MODEL DE EDITAR DO COMERCIAL BOMIX
if ($_SESSION['us_grupo'] == '1') { ?>
  <?php include 'includes/modal/user/edit_comercial.php'; ?>
<?php } ?>

<?php
// ACESSO AO MODEL DE EDITAR DO CLIENTE
if ($_SESSION['us_grupo'] == '1' || $_SESSION['us_grupo'] == '2') { ?>
  <?php include 'includes/modal/user/edit_cliente.php'; ?>
<?php } ?>

<?php
// ACESSO AO MODEL DE EDIÇÃO DO USUÁRIO DO  CLIENTE
if ($_SESSION['us_grupo'] == '2' || $_SESSION['us_grupo'] == '3' || $_SESSION['us_grupo'] == '1') { ?>
  <?php include 'includes/modal/user/edit_cliente_user.php'; ?>
<?php } ?>


<!--***************************************
 *************** SENHA ********************
 ***************************************-->
<?php
// MODAL EDITAR SENHA
if ($_SESSION['us_nivel'] == '1') {
  include 'includes/modal/user/edit_senha.php';
}
?>
<!--************************************-->

<!-- BOTAO LOAD -->
<script src="dist/js/btLoad/botaoLoad.js"></script>

<!-- VALIDA FORMULÁRIO -->
<script src="dist/js/valida_form.js"></script>