<?php
error_reporting(0);
/*
 * carrega a classe a ser instanciada quando chamada
 */
function __autoload($classe){
	$pontos = array("./", "../", "../../");
    foreach($pontos AS $ponto){
        if($classe == "FPDF") $diretorio = "fpdf/fpdf.php"; else $diretorio = "{$classe}.class.php";
		if(file_exists("{$ponto}class/{$diretorio}")){
			//echo "{$ponto}class/{$diretorio}\n";
			include_once "{$ponto}class/{$diretorio}";
		}
	}
}

//echo "<pre>"; print_r($_POST); echo "</pre>"; exit;

$dados_requisicao = formata::decodeJSON($_POST['dados_requisicao']);
$dados_empenho  = formata::decodeJSON($_POST['dados_empenho']);
//echo "<pre>"; print_r($dados_requisicao); echo "</pre>";
//echo "<pre>"; print_r($dados_empenho); echo "</pre>";
//exit;
$CNPJ_UO = array('14.186.135/0001-06' => "CBM", '14.186.135/0001-06' => "FUMCBM");
		
//echo "<pre>"; print_r($CNPJ_UO); echo "</pre>"; exit;
//echo $CNPJ_UO[$_POST['ds_cnpj_unidade_orcamentaria']]; exit;
/**
 *  Criação do Hash do link de email
 */
$hash = formata::password_hash($dados_empenho['ds_cnpj'], 'md5') . formata::password_hash($dados_empenho['ds_cnpj'], 'crypt');


$conn = connection::init();
$sql = "INSERT INTO ordem_requisicao (id_requisicao, id_empenho, id_fornecedor, id_usuario)
        VALUES (".$dados_empenho['id_requisicao'].",".$dados_empenho['id_empenho'].",".$dados_empenho['id_fornecedor'].",".$_POST['id_usuario'].")";
//echo $sql; exit;
$conn->query($sql);

$conn->query("INSERT INTO fornecedor_email (id_fornecedor, ds_codigo, ds_empenho, ds_email) VALUES ('".$dados_empenho['id_fornecedor']."', '$hash', '".$dados_empenho['ds_empenho']."', 'ORDEM DA REQUISIÇÃO')");


connection::close();
//Cabecalho para ser visualizado na tabela
$cabecalho = array("Item", "Produto/Serviço", "Qt", "Unidade");

/*
 *  INICIO DO PDF
 */
$arq = "ordem-".$dados_empenho['ds_requisicao']."-".date("Ymd").".pdf";


class PDF extends FPDF
{
// Page header
function Header()
{
    // Logo
    //$this->Image('logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    $this->SetY(18);
    $this->MultiCell(0, 0, "ESTADO DE SANTA CATARINA", 0, 'C');

	$this->SetY(28);
	$this->MultiCell(0, 0, "CORPO DE BOMBEIROS MILITAR", 0, 'C');
    // Line break
    $this->Ln(20);
}

// Page footer
function Footer()
{
     // Position at 1.5 cm from bottom
    //$this->SetY(-15);
    // Arial italic 8
    //$this->SetFont('Arial','I',8);
    // Page number
    //$this->Cell(0,10,'Página '.$this->PageNo(),0,0,'C');
    $CNPJ_UO = array('14.186.135/0001-06' => "CBM", '14.186.135/0001-06' => "FUMCBM");
	$this->SetFont('Arial','',9);
	$this->SetY(-175);
	$this->Cell(0, 10, "-------------------------------------------------------", 0, 0,'C');
	$this->SetY(-171);
	$this->Cell(0, 10, $_POST['nm_usuario'], 0, 0,'C');
	$this->SetY(-168);
	$this->Cell(0, 10, $_POST['nm_unidade'], 0, 0,'C');
	$this->SetY(-165);
	$this->Cell(0, 10, $_POST['ds_cnpj_unidade_orcamentaria']." - ".$CNPJ_UO[$_POST['ds_cnpj_unidade_orcamentaria']], 0, 0,'C');
	$this->SetY(-162);
	$this->Cell(0, 10, date('d/m/Y H:m:s'), 0, 0,'C');
}
// Função que cria a tabela
function Table($header, $data){
    // Colors, line width and bold font
    $this->SetFillColor(173,216,230);
    $this->SetTextColor(0);
    $this->SetDrawColor(0,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Header
    $w = array(30, 70, 30, 35);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(227,254,251);
    $this->SetTextColor(0);
    $this->SetFont('Arial', '', 10);
    // Data
    $fill = false;
    foreach($data as $row){
        $this->SetX(25);
        $this->Cell($w[0],6,$row['id_item_contratado'],'LR',0,'C',$fill);
        $this->Cell($w[1],6,$row['nm_produto'],'LR',0,'L',$fill);
        $this->Cell($w[2],6,$row['qt_produto'],'LR',0,'C',$fill);
        $this->Cell($w[3],6,$row['nm_unidade_medida'],'LR',0,'L',$fill);
        $this->Ln();
        $this->SetX(25);
        $fill = !$fill;
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}
}

if($_POST['hdn_acao'] == 1){
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AddPage();

$pdf->SetFont('Arial','B',12);
$pdf->SetY(50);
$pdf->MultiCell(0, 0, "ORDEM DE REQUISIÇÃO ".$dados_empenho['ds_requisicao'], 0, 'C');

$pdf->SetFont('Arial','',12);
$pdf->SetXY(10, 60);
//Monta o texto do documento
$pdf->MultiCell(0, 10, "             Solicitamos que o(a) ".$dados_empenho['nm_fornecedor']." CNPJ ".$dados_empenho['ds_cnpj'].", providencie o fornecimento do(s) produto(s)/serviço(s), referentes ao empenho ".$dados_empenho['ds_empenho']." de ".$dados_empenho['dt_empenho']." rigorosamente em conformidade com o especificado a seguir:", 0, 'J');

//Posição da tabela
$pdf->SetXY(25, 100);
/*
 *  Cria a Tabela de itens requisitados
 */
$pdf->Table($cabecalho,$dados_requisicao);

/*
 *  Exibe a saída do PDF
 */
$pdf->Output($arq, 'D');
$pdf->Close();
}
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
			";

			/*$mensagem .= " </table>
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
				</tr>";
			

			foreach($dados_requisicao AS $requisicao){

			$mensagem .= "<tr style=\"background: #E6E6FA;\">
							<td>".$requisicao['id_item_contratado']."</td>
							<td>".$requisicao['nm_produto']."</td>
							<td>".$requisicao['qt_produto']."</td>
							<td>".$requisicao['nm_unidade_medida']."</td>
						 </tr>";
			}*/

		$mensagem .= "</table>

		<p align='center'>".$_POST['nm_unidade']."</p>
		<p align='center'>".$_POST['ds_cnpj_unidade_orcamentaria']." - ".$CNPJ_UO[$_POST['ds_cnpj_unidade_orcamentaria']]."</p>
		<p align='center'>".$_POST['nm_usuario']."</p>
		<p align='center'>".date('d/m/Y H:m:s')."</p>
		<br>
		<p align='center'>PARA VALIDAÇÃO DESSE EMAIL <a href='$texto_hash' target='_blank'>CLIQUE AQUI</a></p>
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
exit;
?>
