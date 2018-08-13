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

echo "<pre>"; print_r($_POST); echo "</pre>"; //exit;
$dados_fornecedor = formata::decodeJSON($_POST['dados_fornecedor'], true);
//echo "<pre>"; print_r($dados_fornecedor); echo "</pre>"; exit;

$dados_unidade = formata::decodeJSON($_POST['dados_unidade']);
//echo "<pre>"; print_r($dados_unidade); echo "</pre>"; exit;

/**
 *  Busca os dados do fornecedor
 */
foreach($dados_fornecedor AS $dados){

	if($dados['id_fornecedor'] == $_POST['id_fornecedor']){
		$id_fornecedor = $dados['id_fornecedor'];
		$ds_email1 = $dados['ds_email1'];
		$ds_email2 = $dados['ds_email2'];
		$nm_fornecedor = $dados['nm_fornecedor'];
		$ds_cnpj = $dados['ds_cnpj'];
		break;
	}

}

/**
 *  Busca dados da Unidade
 */
foreach($dados_unidade AS $unidade){

	if($unidade['id_unidade'] == $_POST['id_unidade']){
		$nm_unidade = $unidade['nm_unidade'];
		break;
	}

}

/**
 *  Criação do Hash do link de email
 */
$hash = formata::password_hash($ds_cnpj, 'md5') . formata::password_hash($ds_cnpj, 'crypt');
/*
 *  Inicia  a conexão e insere o dado
 */
$conn = connection::init();
$conn->query("INSERT INTO fornecedor_email (id_fornecedor, ds_codigo, ds_empenho, ds_email) VALUES ('$id_fornecedor', '$hash', '".$_POST['ds_empenho']."', 'NOVO EMPENHO')");
connection::close();
// Instancia o objeto $mail a partir da Classe PHPMailer
$mail = new email();
setlocale(LC_MONETARY,"pt_BR", "ptb");
//echo $hash;
/*
 *  Busca o host para o link
 */
$texto_hash = $mail->getHostLink()."verifica.php?h=".$hash;
//setlocale(LC_MONETARY, 'it_IT');
// Recupera os dados do formulário
$mensagem  = "<table border=\"0\" width=\"80%\" align='center' style=\"
					font-family         : arial, sans-serif, verdana;
					font-size			: 14px;
					border-collapse		: collapse;
					border		        : 1px solid #ADD8E6;\">
				<tr>
					<th colspan=\"2\" style=\"border: 1px solid #ADD8E6;\">NOVO EMPENHO ".$_POST['ds_empenho']."</th>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">FORNECEDOR:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$nm_fornecedor."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">CNPJ:</td>
					<td style=\"border: 1px solid #ADD8E6;\">".$ds_cnpj."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">UNIDADE BENEFICIADA:</td>
					<td style=\"border: 1px solid #ADD8E6;\">".$nm_unidade."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">NUMERO EMPENHO:</td>
					<td style=\"border: 1px solid #ADD8E6;\">".$_POST['ds_empenho']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">DATA DO EMPENHO:</td>
					<td style=\"border: 1px solid #ADD8E6;\">".$_POST['dt_empenho']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">VALOR DO EMPENHO:</td>
					<td style=\"border: 1px solid #ADD8E6;\">".$_POST['vl_empenho']."</td>
				</tr>
			</table>

			<p align='center'>PARA VALIDAÇÃO DO EMAIL <a href='$texto_hash' target='_blank'>CLIQUE AQUI</a></p>

			";

//echo $mensagem; exit;
// Monta a mensagem que será enviada
$corpo = "$mensagem";
$corpoSimples = "$mensagem";

// Informo o Host, FromName, From, subject e para quem o e-mail será enviado
//$mail->Host = 'correio.cbm.sc.gov.br';
//$mail->FromName = $nome;
//$mail->From = $email;
$mail->Subject = 'NOVO EMPENHO';
//$mail->AddAddress("phproger@gmail.com");
//$mail->AddCC("rogerawk@cbm.sc.gov.br");
$mail->AddAddress($ds_email1);
if($ds_email2 != "") $mail->AddCC($ds_email2);

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
