<?php
/*
 * carrega a classe a ser instanciada quando chamada
 */
function __autoload($classe){
	$pontos = array("./", "../", "../../");
    foreach($pontos AS $ponto){
		if(file_exists("{$ponto}class/{$classe}.class.php")){
			//echo "{$ponto}class/{$classe}.class.php\n";
			include_once "{$ponto}class/{$classe}.class.php";
		}
	}
}

//echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
$dados_requisicao  = formata::decodeJSON($_POST['dados_requisicao']);
//echo "<pre>"; print_r($dados_requisicao); echo "</pre>"; exit;
$dados_item  = formata::decodeJSON($_POST['dados_itens']);
//echo "<pre>"; print_r($dados_item); echo "</pre>"; exit;

/**
 *  Criação do Hash do link de email
 */
$hash = formata::password_hash($_POST['id_requisicao'], 'md5') . formata::password_hash($_POST['id_requisicao'], 'crypt');

//Atualiza na tabela requisição que a nota foi recebida pela auditoria
$erro = null;
$conn = connection::init();

if($_POST['btn_print'] != "Reenviar Email"){
	$sql = "UPDATE notas_fiscais SET id_status_nota = 4 WHERE id_requisicao = ".$_POST['id_requisicao']." AND id_notafiscal = ".$_POST['id_notafiscal'];
	$conn->query($sql);
	if($conn->get_status()==false) $erro = $conn->get_msg();
}

$conn->query("INSERT INTO fornecedor_email (id_fornecedor, ds_codigo, ds_empenho, ds_email) VALUES ('".$dados_requisicao['id_fornecedor']."', '$hash', '".$dados_requisicao['ds_empenho']."', 'TERMO DE APROVAÇÃO DA NOTA FISCAL')");

$conn = connection::close();


/**
 *  Enviar o Email
 *
 */
// Instancia o objeto $mail a partir da Classe PHPMailer
$mail = new email();

/*
 *  Busca o host para o link
 */
$texto_hash = $mail->getHostLink()."verifica.php?h=".$hash;

// Recupera os dados do formulário
$mensagem  = "<table border=\"0\" width=\"80%\" align='center' style=\"
					font-family         : arial, sans-serif, verdana;
					font-size			: 14px;
					border-collapse		: collapse;
					border		        : 1px solid #ADD8E6;\">
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">CNPJ:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$dados_requisicao['ds_cnpj']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">CNPJ Unid. Orç.:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$dados_requisicao['ds_cnpj_unidade_orcamentaria']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Fornecedor:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$dados_requisicao['nm_fornecedor']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Requisição:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$dados_requisicao['ds_requisicao']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Data Requisição:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$dados_requisicao['dt_requisicao']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Nº Nota Fiscal:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$dados_requisicao['nr_notafiscal']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Data Nota Fiscal:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$dados_requisicao['dt_notafiscal']."</td>
				</tr>
			</table>
			<br>
			<table border=\"0\" width=\"80%\" align=\"center\"
				style = \"font-family: arial, sans-serif, verdana;
							font-size: 14px;
							border-collapse : collapse;\">
				<tr style=\"background: #ADD8E6;\">
					 <th width='6%'>Item</th>
					<th width='30%'>Produto</th>
					<th width='6%'>Qt</th>
					<th>Unidade</th>
					<th width='12%'>Valor Unitário</th>
					<th width='10%'>Qt Recebida</th>
					<th width='10%'>Valor</th>
				</tr>
			";

			foreach($dados_item AS $item){

			$mensagem .= "<tr style=\"background: #E6E6FA;\">
							<td align='center'>".$item['id_item_contratado']."</td>
							<td align='center'>".$item['nm_produto']."</td>
							<td align='center'>".$item['qt_produto_requisicao']."</td>
							<td align='center'>".$item['nm_unidade_medida']."</td>
							<td align='center'>".str_replace('.', ',', $item['vl_item_contratado'])."</td>
							<td align='center'>".$item['qt_produto_recebido']."</td>
							<td align='center'>".str_replace('.', ',', $item['vl_produto_recebido'])."</td>
						 </tr>";
			}

			$mensagem .= "<tr  style=\"background: #ADD8E6;\"><th colspan='6' align='left'>TOTAL</th><th>".str_replace('.', ',', $dados_requisicao['vl_notafiscal'])."</th></tr>";
		$mensagem .= "</table>

		<p align='center'>PARA VALIDAÇÃO DO EMAIL <a href='$texto_hash' target='_blank'>CLIQUE AQUI</a></p>
		";

// Monta a mensagem que será enviada
$corpo = "$mensagem";
$corpoSimples = "$mensagem";

// Informo o Host, FromName, From, subject e para quem o e-mail será enviado
//$mail->Host = 'correio.cbm.sc.gov.br';
//$mail->FromName = $nome;
//$mail->From = $email;
$mail->Subject = 'NOTA RECEBIDA PELO BENEFICIARIO';
//$mail->AddAddress("phproger@gmail.com");
//$mail->AddCC("rogerawk@cbm.sc.gov.br");
$mail->AddAddress($dados_requisicao['ds_email1']);
$mail->AddCC($dados_requisicao['ds_email2']);

//if(EMAIL_ATIVACAO){ $mail->AddBCC(EMAIL_PRESTACAO); }

// Informa que a mensagem deve ser enviada em HTML
$mail->IsHTML(true);

// Informa o corpo da mensagem
$mail->Body = $corpo;

// Se o e-mail destino não suportar HTML ele envia o texto simples
$mail->AltBody = $corpoSimples;

// Anexa o arquivo
//$mail->AddAttachment($arquivo_caminho, $arquivo_nome);

$a = $mail->Send();
?>
