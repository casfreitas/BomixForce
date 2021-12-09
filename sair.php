<?php
session_start();
ob_start();

unset(
  $_SESSION['us_id'],
  $_SESSION['us_recno'],
  $_SESSION['us_identificador'],
  $_SESSION['us_cliente'],
  $_SESSION['us_senha'],
  $_SESSION['us_last_login'],
  $_SESSION['us_nome_completo'],
  $_SESSION['us_usuario'],
  $_SESSION['us_email'],
  $_SESSION['us_nivel'],
  $_SESSION['us_status'],
  $_SESSION['us_grupo'],
  $_SESSION['us_data_entrada']
);

header("Location: index.php");
