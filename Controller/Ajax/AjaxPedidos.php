<?php
require_once('../../conexao/conexao_sqlsrv.php');

$pedidoProd = $_POST['pedido'];

// $query = "SELECT *
//           FROM BomixBi.dbo.Fat_TB_PedidoVendaItem
//           WHERE Pedido_FK = '$pedidoProd'
//             ";

$query = "Exec [BomixForce].[dbo].[Bomix_GetPedidoVendaItem] '$pedidoProd'";

$execute = sqlsrv_query($conn, $query);

while ($result = sqlsrv_fetch_array($execute, SQLSRV_FETCH_ASSOC)) {
    //$pedidoProd     = $result['Pedido_FK'];
    $produtoProd    = $result['Produto'];
    $quantidadeProd = $result['Quantidade'];
    $personaliProd  = $result['Personalizacao'];
    $valorUniProd   = $result['ValorUnitario'];

    $response = '<tr>
                    <th style="padding: 10px !important;">' . $produtoProd . '</th>
                    <td style="padding: 10px !important;">' . $quantidadeProd . '</td>
                    <td style="padding: 10px !important;">' . $personaliProd . '</td>
                    <td style="padding: 10px !important;">R$ ' . $valorUniProd . '</td>
                </tr>';

    echo $response;
}
