<?php

include_once '../conexao/conexao_pdo.php';

$nome_completo = filter_input(INPUT_GET, 'nome_completo', FILTER_SANITIZE_STRING);
if (!empty($nome_completo)) {

    $limit = 1;
    $result_user = "SELECT Usuario_ID, Totvs, Email, Login, Matricula_FK, Funcionario, Setor, CentroCusto_FK, CentroCusto, Senha
                    FROM Bomixbi.dbo.Sys_TB_Usuario (nolock)
                    WHERE Funcionario =:NOME
                     ";

    $resultado_user = $conn->prepare($result_user);
    $resultado_user->bindParam(':NOME', $nome_completo, PDO::PARAM_STR);
    $resultado_user->execute();

    $array_valores = array();

    if ($resultado_user->rowCount() != 0) {
        $row_user = $resultado_user->fetch(PDO::FETCH_ASSOC);
        $array_valores['identificador'] = $row_user['Totvs'];
        $array_valores['usuario'] = $row_user['Login'];
        $array_valores['email']   = $row_user['Email'];
    } else {
        $array_valores['identificador'] = '';
        $array_valores['usuario'] = '';
        $array_valores['email'] = '';
        // $array_valores['usuario'] = 'Usuário não encontrado';
        // $array_valores['email'] = 'Email não encontrado';
    }
    echo json_encode($array_valores);
}
