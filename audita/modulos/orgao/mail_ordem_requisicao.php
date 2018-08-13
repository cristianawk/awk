<?php
/* ATENÇÃO
 * ESSE ARQUIVO NÃO ESTA ENVIANDO EMAIL
 * TENTE O ARQUIVO "gerar_ordem_requisicao.php"
 *
 *
 *
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
$dados_empenho = formata::decodeJSON($_POST['dados_empenho']);
//echo "<pre>"; print_r($dados_empenho); echo "</pre>"; //exit;

$dados_requisicao = formata::decodeJSON($_POST['dados_requisicao']);
//echo "<pre>"; print_r($dados_empenho); echo "</pre>"; //exit;

/**
 *  Criação do Hash do link de email
 */
$hash = formata::password_hash($dados_empenho['ds_cnpj'], 'md5') . formata::password_hash($dados_empenho['ds_cnpj'], 'crypt');

/*
 *  Inicia  a conexão e insere o dado
 */
$conn = connection::init();
$conn->query("INSERT INTO fornecedor_email (id_fornecedor, ds_codigo, ds_empenho, ds_email) VALUES ('".$dados_empenho['id_fornecedor']."', '$hash', '".$dados_empenho['ds_empenho']."', 'ORDEM DA REQUISIÇÃO')");
connection::close();
/*
 *  Busca o host para o link
 */
$texto_hash = $mail->getHostLink()."verifica.php?h=".$hash;


// Instancia o objeto $mail a partir da Classe PHPMailer
$mail = new email();

// Recupera os dados do formulário
$mensagem  = "<table border=\"0\" width=\"80%\" align='center' style=\"
					font-family         : arial, sans-serif, verdana;
					font-size			: 14px;
					border-collapse		: collapse;
					border		        : 1px solid #ADD8E6;\">
				<tr>
					<th colspan=\"2\" style=\"border: 1px solid #ADD8E6;\">ORDEM DA REQUISIÇÃO ".$dados_empenho['ds_requisicao']."</th>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">FORNECEDOR:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$dados_empenho['nm_fornecedor']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">CNPJ:</td>
					<td style=\"border: 1px solid #ADD8E6;\">".$dados_empenho['ds_cnpj']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">NUMERO EMPENHO:</td>
					<td style=\"border: 1px solid #ADD8E6;\">".$dados_empenho['ds_empenho']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">DATA DO EMPENHO:</td>
					<td style=\"border: 1px solid #ADD8E6;\">".$dados_empenho['dt_empenho']."</td>
				</tr>
			</table>
			<br>
			<table border=\"0\" width=\"80%\" align=\"center\"
				style = \"font-family: arial, sans-serif, verdana;
							font-size: 14px;
							border-collapse : collapse;\">
				<tr style=\"background: #ADD8E6;\">
					<th>Item</th>
					<th>Produto</th>
					<th>Quantidade</th>
					<th>Unidade</th>
				</tr>
			";

			foreach($dados_requisicao AS $requisicao){

			$mensagem .= "<tr style=\"background: #E6E6FA;\">
							<td>".$requisicao['id_item_contratado']."</td>
							<td>".$requisicao['nm_produto']."</td>
							<td>".$requisicao['qt_produto']."</td>
							<td>".$requisicao['nm_unidade_medida']."</td>
						 </tr>";
			}

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
$mail->Subject = 'ORDEM DE REQUISIÇÃO';
//$mail->AddAddress("phproger@gmail.com");
//$mail->AddCC("rogerawk@cbm.sc.gov.br");
$mail->AddAddress($dados_empenho['ds_email1']);
$mail->AddCC($dados_empenho['ds_email2']);

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

// Tenta enviar o e-mail e analisa o resultado
if ($a){
	echo 'E-mail enviado com sucesso';
}else{
    echo 'Erro:' . $mail->ErrorInfo;
}


?>
