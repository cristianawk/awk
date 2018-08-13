<?php
/*
 * carrega a classe a ser instanciada quando chamada
 */
function __autoload($classe){
    if(file_exists("../../class/{$classe}.class.php")){
		//echo "class/{$classe}.class.php\n";
        include_once "../../class/{$classe}.class.php";
    }else{
		//echo "{$classe}\n";
	}
}

	//echo "<pre>"; print_r($_POST); echo "</pre>"; //exit;
	
	//if($dados) $dados['vl_empenho'] = formata::formataValor($dados['vl_empenho']);

	//if($_POST['vl_empenho']) = substr($_POST['vl_empenho'],3,12);

	$msg = null;
	$flg = null;

	$colunas = null;
	$valores = null;
	$arrColunas = null;
	$arrValores = null;

	foreach($_POST AS $key => $valor){

		if($valor != ""){
			//if(formata::validaColuna($key, array('btn_incluir', 'btn_limpar', 'hdn_acao'))){
			if(formata::validaColuna($key, array('btn_incluir', 'btn_limpar', 'hdn_acao', 'btn_cons', 'fakeupload', 'realupload', 'imp', 'ver'))){

				if(($_POST['id_fornecedor'] != "")&&($_POST['hdn_acao'] == 'alt')){

					$id_fornecedor = $_POST['id_fornecedor'];
					if($key != "id_fornecedor") $arrColunas[] = "".$key."=".formata::formataValor($key, $valor);

				}else{

					$arrColunas[] = $key;
					$arrValores[] = formata::formataValor($key, $valor);
					//echo $key." - ".$valor."\n";
				}
			}
		}
	}

	if(($_POST['id_fornecedor'] != "")&&($_POST['hdn_acao'] == 'alt')){
		$colunas = implode(", ", $arrColunas);//echo $colunas;
		$sql = "UPDATE vw_fornecedores SET $colunas WHERE id_fornecedor =".$id_fornecedor;
		//echo "<br>sql: ".$sql;
		$msg = "O registro foi alterado com sucesso!";
	}else{
		$colunas = implode(", ", $arrColunas);//echo $colunas;
		$valores = implode(", ", $arrValores);//echo $valores;
		$sql = "INSERT INTO vw_fornecedores ($colunas) VALUES ($valores) ";
		$msg = "O novo registro foi inserido com sucesso!";
	}

	$conn = connection::init();
	$conn->query($sql);
	if($conn->get_status()==false){
		//echo ($conn->get_msg());
		$msg = "Aconteceu algum erro na inserção/alteração dos dados!!";
		$flg = 0;

	}else{
		//$msg = "O registro foi inserido/alterado com suscesso!!";
		$flg = 1;
	}

	connection::close();
	
	header("Content-type: application/xml");
	$xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
	$xml.= "<root>";
	$xml.= "<flg>$flg</flg>";
	$xml.= "<mesg>$msg</mesg>";
	$xml.="</root>";

	echo $xml;
	

/* 
// Instancia o objeto $mail a partir da Classe PHPMailer
$mail = new email();
setlocale(LC_MONETARY,"pt_BR", "ptb");
$texto_hash = $mail->getHostLink()."index.php";
//$texto_hash = $mail->getHostLink()."fornpass.php?e=1";

$mensagem  = "<table border=\"0\" width=\"80%\" align='center' style=\"font-family : arial, sans-serif, verdana; font-size : 14px; border-collapse : collapse; border: 1px solid #ADD8E6;\">
				<tr>
					<th colspan=\"2\" style=\"border: 1px solid #ADD8E6;\">Novo Fornecedor: ".$_POST['nm_fornecedor']."</th>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">CPF/CNPJ:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$_POST['ds_cnpj']."</td>
				</tr>
			</table>
			<p align='center'>PARA VALIDAÇÃO DO EMAIL E ALTERAÇÃO DE SUA SENHA <a href='$texto_hash' target='_blank'>CLIQUE AQUI</a></p>
			";

// Monta a mensagem que será enviada
$corpo = "$mensagem";
$corpoSimples = "$mensagem";
$mail->Subject = 'NOVO FORNECEDOR';
//$mail->AddAddress("cristian@awktec.com");
//$mail->AddCC("cristianawk@cbm.sc.gov.br");
$mail->AddAddress($_POST['ds_email']);
//if($ds_email2 != "") $mail->AddCC($ds_email2);
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

	 */
	exit;



?>
