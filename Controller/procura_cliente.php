<?php

include_once '../conexao/conexao_pdo.php';

$nome_cliente = filter_input(INPUT_GET, 'nome_cliente', FILTER_SANITIZE_STRING);
if (!empty($nome_cliente)) {

    $limit = 1;
    $result_cliente = "SELECT Recno, RazaoSocial, NomeFantasia, CNPJ, Email, Status
                    FROM Bomixbi.dbo.Fat_TB_Cliente
                    WHERE Status = 'ATIVO' and CNPJ <> '' AND NomeFantasia =:NOME
                     ";

    $resultado_cliente = $conn->prepare($result_cliente);
    $resultado_cliente->bindParam(':NOME', $nome_cliente, PDO::PARAM_STR);
    $resultado_cliente->execute();

    $array_val_cliente = array();

    if ($resultado_cliente->rowCount() != 0) {
        $row_cliente = $resultado_cliente->fetch(PDO::FETCH_ASSOC);
        $array_val_cliente['usuario'] = $row_cliente['CNPJ'];
        $array_val_cliente['email']   = $row_cliente['Email'];
    } else {
        $array_val_cliente['usuario'] = '';
        $array_val_cliente['email'] = '';
        // $array_valores['usuario'] = 'Usuário não encontrado';
        // $array_valores['email'] = 'Email não encontrado';
    }
    echo json_encode($array_val_cliente);
}

