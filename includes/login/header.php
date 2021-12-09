<?php
session_start();
ob_start(); //Limpa o buff de saida
include_once 'conexao/conexao_pdo.php'
?>
<!doctype html>
<html lang="pt-br">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="dist/images/favicon.png" type="image/x-icon" />
  <!-- BOOTSTRAP CSS -->
  <link href="dist/css/bootstrap/bootstrap.min.css" rel="stylesheet">
  <!-- BOOTSTRAP ICONS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <!-- RESET -->
  <link href="dist/css/reset.css" rel="stylesheet">
  <!-- STYLE -->
  <link href="dist/css/style.css" rel="stylesheet">

  <title>Bomix Force</title>

</head>

<body class="telaLogin">