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

//echo "<br>primeiro echo:<br><pre>"; print_r($_POST); echo "</pre>"; //exit;
$dados_requisicao  = formata::decodeJSON($_POST['dados_requisicao']);
//echo "<pre>"; print_r($dados_requisicao); echo "</pre>"; exit;

	foreach($dados_requisicao AS $key => $valor){
		$$key = formata::formataValor($key, $valor);
	}

	foreach($_POST AS $key => $valor){

		if($valor != ""){

			if(formata::validaColuna($key, array('btn_incluir', 'btn_limpar', 'hdn_acao', 'btn_cons', 'dados_item', 'dados_questao'))){

				if(strpos($key, 'produto_recebido') == false){				
//if(strpos($key, 'produto_recebido') === false){
                    $$key = formata::formataValor($key, $valor);
					//echo $key." - ".$valor."\n";
                }
			}
		}
	}

$dados_item  = formata::decodeJSON($_POST['dados_item']);

//echo "<br>segundo echo:<br><pre>"; print_r($dados_item); echo "</pre>"; exit;

//Variavel que guarda os erros
$erros = null;

$conn = connection::init();
$conn->query("BEGIN");

$sql = "SELECT 1 AS n FROM notas_fiscais WHERE nr_notafiscal = $nr_notafiscal AND id_fornecedor = $id_fornecedor LIMIT 1";
//echo $sql;
$conn->query($sql);
//if($conn->get_status()==false) $erros[] = $conn->get_msg();
$t = $conn->fetch_row();
if($t['n']) $erros[] = $t['n'];
//echo $N; exit;


$sql = "INSERT INTO notas_fiscais (nr_notafiscal, dt_notafiscal, id_requisicao, vl_notafiscal, id_usuario, id_fornecedor) VALUES ($nr_notafiscal, $dt_notafiscal, $id_requisicao, $vl_notafiscal, $id_usuario, $id_fornecedor)";
//echo "<br>sql 1: ".$sql;// exit;
$conn->query($sql);
if($conn->get_status()==false) $erros[] = $conn->get_msg();

foreach($dados_item AS $item){
    $id_item_contratado = $item['id_item_contratado'];
    $qt_produto_recebido = formata::formataValor("qt_", $_POST['qt_produto_recebido_'.$id_item_contratado]);
    $vl_produto_recebido = formata::formataValor("vl_", $_POST['vl_produto_recebido_'.$id_item_contratado]);
    $id_motivo = formata::formataValor("id_", $_POST['id_motivo_'.$id_item_contratado]);
    $sql = "INSERT INTO items_recebidos (id_notafiscal, id_requisicao, id_item_contratado, qt_produto_recebido, vl_produto_recebido, id_motivo)
                    VALUES ((SELECT LAST_VALUE FROM next_notasfiscais), $id_requisicao, $id_item_contratado, $qt_produto_recebido, $vl_produto_recebido, $id_motivo)";
    //echo "<br>sql 2: ".$sql;// exit;
    $conn->query($sql);
    if($conn->get_status()==false) $erros[] = $conn->get_msg();
}

if($erros){ ?>

		<link href="../../css/audita.css" rel="stylesheet" type="text/css" />
		<br>
		<pre class="erro" align="center">Ocorreu algum erro no momento da Aceita&ccedil;&atilde;o da Auditoria.
						  <br>Verifique se este numero de Nota Fiscal j&aacute; n&atilde;o est&aacute; registrada no sistema para essa mesma empresa.
						   <br>O sistema s&oacute; aceita o mesmo n&uacute;mero de nota fiscal, mas de empresas diferentes.</pre>
		<p align="center">
		   <input type="button" name="btn_fechar" class="botao" Value="VOLTAR" onclick="javascript:history.back()">
		</p>
	<?php
	//echo "<pre>"; print_r($erros); echo "</pre>"; exit;
	$conn->query("ROLLBACK");
	exit;

} else {
	$conn->query("COMMIT");
}


//exit;
//Cabecalho para ser visualizado na tabela
$cabecalho = array("Item", "Produto/Servi&ccedil;o", "Qt", "Unidade", "Valor Unit&aacute;rio", "Qt Recebida", "Valor");

/*
 *  INICIO DO PDF
 */
$arq = "aceite-".$id_requisicao."-".date("Ymd").".pdf";

/*
 *  REVER
 */
$dados_requisicao  = formata::decodeJSON($_POST['dados_requisicao']);

/**
 *  Cria\E7\E3o do Hash do link de email
 */
$hash = formata::password_hash($dados_requisicao['ds_cnpj'], 'md5') . formata::password_hash($dados_requisicao['ds_cnpj'], 'crypt');
$conn->query("INSERT INTO fornecedor_email (id_fornecedor, ds_codigo, ds_empenho, ds_email) VALUES ('".$dados_requisicao['id_fornecedor']."', '$hash', '".$dados_requisicao['ds_empenho']."', 'TERMO DE ACEITE PARA AUDITORIA')");
//echo "<br>sql 3: ".$; exit;
//echo "<pre>"; print_r($conn); echo "</pre>"; //exit;
connection::close();



/*
 *  Inicio do PDF
 */
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
    //$this->Cell(0,10,'P\E1gina '.$this->PageNo(),0,0,'C');
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
// Fun\E7\E3o que cria a tabela
function Table($header, $data){
    // Colors, line width and bold font
    $this->SetFillColor(173,216,230);
    $this->SetTextColor(0);
    $this->SetDrawColor(0,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B', 9);
    // Header
    $w = array(15, 50, 15, 35, 35, 28, 18);
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
        $this->Cell($w[2],6,$row['qt_produto_requisicao'],'LR',0,'C',$fill);
        $this->Cell($w[3],6,$row['nm_unidade_medida'],'LR',0,'L',$fill);
        $this->Cell($w[4],6,str_replace(".", ",", $row['vl_item_contratado']),'LR',0,'C',$fill);
        $this->Cell($w[5],6,$_POST['qt_produto_recebido_'.$row['id_item_contratado']],'LR',0,'C',$fill);
        $this->Cell($w[6],6,$_POST['vl_produto_recebido_'.$row['id_item_contratado']],'LR',0,'C',$fill);
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
    $this->Cell(array_sum($w)-18,7,"TOTAL",'LTR',0,'L',true);
    $this->Cell(18,7,$_POST['vl_notafiscal'],'LTR',0,'C',true);
    $this->Ln();
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}
}


// Instanciation of inherited class
$pdf = new PDF();
$pdf->AddPage();

$pdf->SetFont('Arial','B',12);
$pdf->SetY(50);
$pdf->MultiCell(0, 0, "TERMO DE ACEITE PARA AUDITORIA", 0, 'C');

$pdf->SetFont('Arial','',12);

$pdf->SetXY(20, 70);
$pdf->MultiCell(0, 0, "CNPJ: ".$dados_requisicao['ds_cnpj'], 0, 'L');

$pdf->SetXY(20, 80);
$pdf->MultiCell(0, 0, "CNPJ Unidade Or&ccedil;.: ".$dados_requisicao['ds_cnpj_unidade_orcamentaria'], 0, 'L');

$pdf->SetXY(20, 90);
$pdf->MultiCell(0, 0, "Fornecedor: ".$dados_requisicao['nm_fornecedor'], 0, 'L');

$pdf->SetXY(20, 100);
$pdf->MultiCell(0, 0, "Requisi&ccedil;&atilde;o: ".$dados_requisicao['ds_requisicao'], 0, 'L');

$pdf->SetXY(20, 110);
$pdf->MultiCell(0, 0, "Data Requisi&ccedil;&atilde;o: ".$dados_requisicao['dt_requisicao'], 0, 'L');

$pdf->SetXY(20, 120);
$pdf->MultiCell(0, 0, "N&uacute;mero da Nota Fiscal: ".$_POST['nr_notafiscal'], 0, 'L');

$pdf->SetXY(20, 130);
$pdf->MultiCell(0, 0, "Data da Nota Fiscal: ".$_POST['dt_notafiscal'], 0, 'L');

//Posi\E7\E3o da tabela
$pdf->SetXY(10, 140);
/*
 *  Cria a Tabela de itens requisitados
 */
$pdf->Table($cabecalho,$dados_item);

$pdf->Ln(10);

$pdf->SetFont('Arial','',12);
//Monta o texto do documento
$pdf->MultiCell(0, 6, "             Declaro que verifiquei e aprovei os valores constantes da Nota Fiscal, conforme dados acima apresentados. O recebimento dos servi&ccedil;os ou material foram atestados. conforme declara&ccedil;&atilde;o assinada e datada no verso da presente Nota Fiscal.", 0, 'J');


/*
 *  Exibe a sa\EDda do PDF
 */
$pdf->Output($arq, 'D');
$pdf->Close();

//exit;

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


// Recupera os dados do formul\E1rio
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
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">CNPJ Unid. Or\E7.:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$dados_requisicao['ds_cnpj_unidade_orcamentaria']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Fornecedor:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$dados_requisicao['nm_fornecedor']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Requisi\E7\E3o:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$dados_requisicao['ds_requisicao']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Data Requisi\E7\E3o:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$dados_requisicao['dt_requisicao']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">N\BA Nota Fiscal:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$_POST['nr_notafiscal']."</td>
				</tr>
				<tr>
					<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Data Nota Fiscal:</td>
					<td style=\"'border: 1px solid #ADD8E6;\">".$_POST['dt_notafiscal']."</td>
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
					<th width='12%'>Valor Unit&aacute;rio</th>
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
							<td align='center'>".$_POST['qt_produto_recebido_'.$item['id_item_contratado']]."</td>
							<td align='center'>".$_POST['vl_produto_recebido_'.$item['id_item_contratado']]."</td>
						 </tr>";
			}

			$mensagem .= "<tr  style=\"background: #ADD8E6;\"><th colspan='6' align='left'>TOTAL</th><th>".$_POST['vl_notafiscal']."</th></tr>";
		$mensagem .= "</table>
		<p align='center'>PARA VALIDA&Ccedil;&Atilde;O DESSE EMAIL <a href='$texto_hash' target='_blank'>CLIQUE AQUI</a></p>
		";

// Monta a mensagem que ser\E1 enviada
$corpo = "$mensagem";
$corpoSimples = "$mensagem";

// Informo o Host, FromName, From, subject e para quem o e-mail ser\E1 enviado
//$mail->Host = 'correio.cbm.sc.gov.br';
//$mail->FromName = $nome;
//$mail->From = $email;
$mail->Subject = 'ACEITE DO FORNECEDOR PARA AUDITORIA';
//$mail->AddAddress("phproger@gmail.com");
//$mail->AddCC("rogerawk@cbm.sc.gov.br");
$mail->AddAddress($dados_requisicao['ds_email1']);
$mail->AddCC($dados_requisicao['ds_email2']);

//if(EMAIL_ATIVACAO){ $mail->AddBCC(EMAIL_PRESTACAO); }

// Informa que a mensagem deve ser enviada em HTML
$mail->IsHTML(true);

// Informa o corpo da mensagem
$mail->Body = $corpo;

// Se o e-mail destino n\E3o suportar HTML ele envia o texto simples
$mail->AltBody = $corpoSimples;

// Anexa o arquivo
//$mail->AddAttachment($arquivo_caminho, $arquivo_nome);

$a = $mail->Send();


exit;


?>
