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

function ajustaTexto($questoes_selecionados, $questoes_todas, $descricao = ""){

    $texto = null;
    $aux = null;

    foreach($questoes_selecionados AS $id_qs){
        //echo $id_qs."<br>";
        foreach($questoes_todas AS $tupla_questao){

            if($id_qs == $tupla_questao['id_questao']){
                $aux[] = $tupla_questao['ds_questao'];
            }
        }

    }

    if($aux) $texto = implode(", ", $aux).". ";

    if($descricao != "") $texto .= $descricao;

    return $texto;

}

//exit;
$dados_requisicao  = formata::decodeJSON($_POST['dados_requisicao']);
//echo "<pre>"; print_r($dados_requisicao); echo "</pre>"; exit;
$dados_item  = formata::decodeJSON($_POST['dados_item']);
//echo "<pre>"; print_r($dados_item); echo "</pre>"; exit;
$dados_questao  = formata::decodeJSON($_POST['dados_questao']);
//echo "<pre>"; print_r($dados_questao); echo "</pre>"; exit;

/**
 *  Cria��o do Hash do link de email
 */
$hash = formata::password_hash($dados_requisicao['ds_cnpj'], 'md5') . formata::password_hash($dados_requisicao['ds_cnpj'], 'crypt');

//Variavel que guarda os erros
$erros = null;
$conn = connection::init();
$conn->query("BEGIN");

//Insere um registro de analise da nota fiscal
$sql = "INSERT INTO nota_fiscal_analise (id_fornecedor, nr_notafiscal) VALUES ('".$dados_requisicao['id_fornecedor']."', '".$dados_requisicao['nr_notafiscal']."');";
//echo $sql; exit;
$conn->query($sql);
if($conn->get_status()==false) $erros[] = $conn->get_msg();

//Muda o status da requisicao para devolvido pelo beneficiario
$sql = "UPDATE notas_fiscais SET id_status_nota = 6 WHERE id_requisicao = ".$_POST['id_requisicao']." AND id_notafiscal = ".$_POST['id_notafiscal'];
//echo $sql; exit;
$conn->query($sql);
if($conn->get_status()==false) $erros[] = $conn->get_msg();

if(is_array($_POST['id_questao'])){
	foreach($_POST['id_questao'] AS $id_questao){
		$sql = "INSERT INTO questoes_notas_fiscais (id_notafiscal, id_questao)
                    VALUES (".$dados_requisicao['id_notafiscal'].", $id_questao)";
		//echo $sql; exit;
		$conn->query($sql);
		if($conn->get_status()==false) $erros[] = $conn->get_msg();
	}
}
if($erros){ ?>

		<link href="../../css/audita.css" rel="stylesheet" type="text/css" />
		<br>
		<pre class="erro" align="center">Ocorreu algum erro no momento da Devolu��o da Nota Fiscal para o Benefici�rio.<br>Verifique se essa nota ja n�o foi analisada.</pre>
		<p align="center">
		   <input type="button" name="btn_fechar" class="botao" Value="VOLTAR" onclick="javascript:history.back()">
		</p>
	<?php
	echo "<pre>"; print_r($erros); echo "</pre>"; exit;
	$conn->query("ROLLBACK");
	exit;

} else {
	$conn->query("INSERT INTO fornecedor_email (id_fornecedor, ds_codigo, ds_empenho, ds_email) VALUES ('".$dados_requisicao['id_fornecedor']."', '$hash', '".$dados_requisicao['ds_empenho']."', 'TERMO DE APROVA��O DA NOTA FISCAL')");
	$conn->query("COMMIT");
}

connection::close();

//exit;
//Cabecalho para ser visualizado na tabela
$cabecalho = array("Item", "Produto/Servi�o", "Qt", "Unidade", "Valor Unit�rio", "Valor");/*
 *  INICIO DO PDF
 */
$arq = "devol-fornecedor-".$_POST['id_requisicao']."-".date("Ymd").".pdf";


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
    //$this->Cell(0,10,'P�gina '.$this->PageNo(),0,0,'C');
    $this->SetFont('Arial','',9);
	$this->SetY(-65);
	$this->Cell(0, 10, "-------------------------------------------------------", 0, 0,'C');
	$this->SetY(-61);
	$this->Cell(0, 10, $_POST['nm_usuario'], 0, 0,'C');
	$this->SetY(-58);
	$this->Cell(0, 10, $_POST['nm_unidade'], 0, 0,'C');	
	$this->SetY(-55);
	$this->Cell(0, 10, date('d/m/Y H:m:s'), 0, 0,'C');
}
// Fun��o que cria a tabela
function Table($header, $data, $vl_notafiscal){
    // Colors, line width and bold font
    $this->SetFillColor(173,216,230);
    $this->SetTextColor(0);
    $this->SetDrawColor(0,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B', 9);
    // Header
    $w = array(15, 60, 15, 35, 28, 28);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);

    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(227,254,251);
    $this->SetTextColor(0);
    $this->SetFont('Arial', '', 8);
    // Data
    $fill = false;
    foreach($data as $row){
        $this->SetX(10);
        $this->Cell($w[0],6,$row['id_item_contratado'],'LR',0,'C',$fill);
        $this->Cell($w[1],6,$row['nm_produto'],'LR',0,'L',$fill);
        $this->Cell($w[2],6,$row['qt_produto_recebido'],'LR',0,'C',$fill);
        $this->Cell($w[3],6,$row['nm_unidade_medida'],'LR',0,'L',$fill);
        $this->Cell($w[4],6,str_replace(".", ",", $row['vl_item_contratado']),'LR',0,'C',$fill);
        $this->Cell($w[5],6,str_replace(".", ",", $row['vl_produto_recebido']),'LR',0,'C',$fill);
        $this->Ln();
        $this->SetX(10);
        $fill = !$fill;
    }
    // Colors, line width and bold font
    $this->SetFillColor(173,216,230);
    $this->SetTextColor(0);
    $this->SetDrawColor(0,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B', 9);
    // Header
    $this->Cell(array_sum($w)-28,7,"TOTAL",'LTR',0,'L',true);
    $this->Cell(28,7,str_replace(".", ",", $vl_notafiscal),'LTR',0,'C',true);
    $this->Ln();
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}
}


// Instanciation of inherited class
$pdf = new PDF();
$pdf->AddPage();

$pdf->SetFont('Arial','B',12);
$pdf->SetY(45);
$pdf->MultiCell(0, 0, "TERMO DE DEVOLU��O DA NOTA FISCAL PARA O BENEFICI�RIO", 0, 'C');

$pdf->SetFont('Arial','',12);

$pdf->SetXY(20, 60);
$pdf->MultiCell(0, 0, "BENEFICI�RIO: ".$dados_requisicao['nm_unidade'], 0, 'L');

$pdf->SetXY(20, 70);
$pdf->MultiCell(0, 0, "CNPJ: ".$dados_requisicao['ds_cnpj'], 0, 'L');

$pdf->SetXY(20, 80);
$pdf->MultiCell(0, 0, "CNPJ Unidade Or�.: ".$dados_requisicao['ds_cnpj_unidade_orcamentaria'], 0, 'L');

$pdf->SetXY(20, 90);
$pdf->MultiCell(0, 0, "Fornecedor: ".$dados_requisicao['nm_fornecedor'], 0, 'L');

$pdf->SetXY(20, 100);
$pdf->MultiCell(0, 0, "Requisi��o: ".$dados_requisicao['ds_requisicao'], 0, 'L');

$pdf->SetXY(20, 110);
$pdf->MultiCell(0, 0, "Data Requisi��o: ".$dados_requisicao['dt_requisicao'], 0, 'L');

$pdf->SetXY(20, 120);
$pdf->MultiCell(0, 0, "N�mero da Nota Fiscal: ".$dados_requisicao['nr_notafiscal'], 0, 'L');

$pdf->SetXY(20, 130);
$pdf->MultiCell(0, 0, "Data da Nota Fiscal: ".$dados_requisicao['dt_notafiscal'], 0, 'L');

//Posi��o da tabela
$pdf->SetXY(10, 140);
/*
 *  Cria a Tabela de itens requisitados
 */
$pdf->Table($cabecalho,$dados_item, $dados_requisicao['vl_notafiscal']);

$pdf->Ln(10);

$pdf->SetFont('Arial','',12);
//Monta o texto do documento
$pdf->MultiCell(0, 6, "MOTIVOS DA DEVOLU��O:".ajustaTexto(@$_POST['id_questao'], $dados_questao, $_POST['ds_pendencias']), 1, 1, 'J');


/*
 *  Exibe a sa�da do PDF
 */
$pdf->Output($arq, 'D');
$pdf->Close();

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


// Recupera os dados do formul�rio
$mensagem  = "<table border=\"0\" width=\"80%\" align='center' style=\"
					font-family         : arial, sans-serif, verdana;
					font-size			: 14px;
					border-collapse		: collapse;
					border		        : 1px solid #ADD8E6;\">
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">BENEFICI�RIO:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$dados_requisicao['nm_unidade']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">CNPJ:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$dados_requisicao['ds_cnpj']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">CNPJ Unid. Or�.:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$dados_requisicao['ds_cnpj_unidade_orcamentaria']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Fornecedor:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$dados_requisicao['nm_fornecedor']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Requisi��o:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$dados_requisicao['ds_requisicao']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Data Requisi��o:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$dados_requisicao['dt_requisicao']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">N� Nota Fiscal:</td>
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
					<th width='12%'>Valor Unit�rio</th>
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
							<td align='center'>".$item['vl_item_contratado']."</td>
							<td align='center'>".$item['qt_produto_recebido']."</td>
							<td align='center'>".$item['vl_produto_recebido']."</td>
						 </tr>";
			}

			$mensagem .= "<tr  style=\"background: #ADD8E6;\"><th colspan='6' align='left'>TOTAL</th><th>".$dados_requisicao['vl_notafiscal']."</th></tr>";
		$mensagem .= "</table>
		<p align='center'>PARA VALIDA��O DESSE EMAIL <a href='$texto_hash' target='_blank'>CLIQUE AQUI</a></p>
		";

// Monta a mensagem que ser� enviada
$corpo = "$mensagem";
$corpoSimples = "$mensagem";

// Informo o Host, FromName, From, subject e para quem o e-mail ser� enviado
//$mail->Host = 'correio.cbm.sc.gov.br';
//$mail->FromName = $nome;
//$mail->From = $email;
$mail->Subject = 'DEVOLU��O DA NOTA FISCAL PARA O BENEFICI�RIO';
//$mail->AddAddress("phproger@gmail.com");
//$mail->AddCC("rogerawk@cbm.sc.gov.br");
$mail->AddAddress($dados_requisicao['ds_email1']);
$mail->AddCC($dados_requisicao['ds_email2']);

//if(EMAIL_ATIVACAO){ $mail->AddBCC(EMAIL_PRESTACAO); }

// Informa que a mensagem deve ser enviada em HTML
$mail->IsHTML(true);

// Informa o corpo da mensagem
$mail->Body = $corpo;

// Se o e-mail destino n�o suportar HTML ele envia o texto simples
$mail->AltBody = $corpoSimples;

// Anexa o arquivo
//$mail->AddAttachment($arquivo_caminho, $arquivo_nome);

$a = $mail->Send();

exit;
?>
