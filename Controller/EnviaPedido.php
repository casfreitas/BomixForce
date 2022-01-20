<?php
session_start();
include '../conexao/conexao_sqlsrv.php';

$pedido        = $_POST['pedido'];
$cliente       = $_SESSION['us_nome_completo'];
$email_cliente = $_SESSION['us_email'];

//ENVIA EMAIL PARA O USUÁRIO CADASTRADO
$email_remetente = "carlos.silveira.bmx@gmail.com"; //EMAIL CADASTRADO NO WEBMAIL DO XAMPP

//CONFIGURAÇÕES
$email_destino_comercial = "comercial@bomix.com.br";
$email_destinatario = "$email_cliente"; //EMAIL QUE RECEBERA A MENSAGEM
$email_reply = "carlos.silveira.bmx@gmail.com";
$email_assunto = "Bomix Force - Pedido solicitado"; //ASSUNTO

//CONTEÚDO DO EMAIL
$email_conteudo = "<h3 style='font-family: Arial, Helvetica, sans-serif; font-weight: normal;line-height: 25px;'>A sua solicitação de duplicação de pedido foi recepcionada e encaminhada ao gestor da sua conta. \n Entraremos em contato para confirmar o seu pedido. </h3> \n";
$email_conteudo .= "<h2 style='font-family: Arial, Helvetica, sans-serif;'>Bomix Force</h2>";

$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=UTF-8';

//CABEÇALHO DO EMAIL
$headers[] = 'To: ' . $email_destinatario;
$headers[] = 'From: ' . $email_remetente;

//ENVIA O EMAIL PARA O CLIENTE
mail($email_destinatario, $email_assunto, nl2br($email_conteudo), implode("\r\n", $headers));


$email_conteudo_comercial .= "<h2><strong>PEDIDO:  $pedido</strong></h2>";
$email_conteudo_comercial .= "O usuário $cliente solicitou a duplicação do pedido. \n";
$email_conteudo_comercial .= "<h2><strong>DADOS DO PEDIDO:</strong></h2>";

$sql = "SELECT v.PedidoVenda_ID, i.Pedido_FK, v.OrdemCompra, v.Status, v.Emissao, v.Cidade, v.UF, v.Cliente, i.Produto, i.Quantidade, i.Personalizacao, i.ValorUnitario
        FROM BomixBi.dbo.Fat_TB_PedidoVenda v
        INNER JOIN BomixBi.dbo.Fat_TB_PedidoVendaItem i
        ON v.PedidoVenda_ID = i.Pedido_FK
        WHERE v.PedidoVenda_ID = '$pedido'";

$stmt = sqlsrv_query($conn, $sql);
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

  $pedido      = $row['PedidoVenda_ID'];
  $cliente      = $row['Cliente'];

  $produto     = $row['Produto'];
  $quantidade  = $row['Quantidade'];
  $personali   = $row['Personalizacao'];
  $valor_uni   = $row['ValorUnitario'];

  $email_conteudo_comercial .= "<strong>Produto:</strong> $produto \n";
  $email_conteudo_comercial .= "<strong>Quantidade:</strong> $quantidade \n";
  $email_conteudo_comercial .= "<strong>Personalização:</strong> $personali \n";
  $email_conteudo_comercial .= "<strong>Valor unitário: R$</strong> $valor_uni \n";
  $email_conteudo_comercial .= "<br> \n";
}
sqlsrv_free_stmt($stmt);

$headers_comercial[] = 'MIME-Version: 1.0';
$headers_comercial[] = 'Content-type: text/html; charset=UTF-8';

//CABEÇALHO DO EMAIL
$headers_comercial[] = 'To: ' . $email_destino_comercial;
$headers_comercial[] = 'From: ' . $email_remetente;

//ENVIA O EMAIL PARA O COMERCIAL
mail($email_destino_comercial, $email_assunto, nl2br($email_conteudo_comercial), implode("\r\n", $headers_comercial));

//====================================================

//ENVIA MENSAGEM
session_start();
$_SESSION["msg"] = "Sua solicitação foi enviada com sucesso! Fique atento para a confirmação do pedido.";

//VOLTA A PÁGINA ANTERIOR
//header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
header(sprintf('location: ../pedidos.php'));
