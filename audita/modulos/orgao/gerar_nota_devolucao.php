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
// Array
// (
//     [id_usuario] => 2
//     [nm_usuario] => CRISTIAN MARQUES SANTOS
//     [nm_unidade] => DITI
//     [dados_requisicao] => {\"id_requisicao\":\"51\",\"ds_requisicao\":\"2012.4456\",\"dt_requisicao\":\"26\\/04\\/2012\",\"id_empenho\":\"35\",\"ds_empenho\":\"2012NE000012\",\"ds_cnpj_unidade_orcamentaria\":\"14.186.135\\/0001-06\",\"id_fornecedor\":\"2260\",\"nm_fornecedor\":\"AWK TECNOLOGIA EM SISTEMAS LTDA\",\"ds_cnpj\":\"08.732.540\\/0001-71\",\"ds_email1\":\"cristian@awktec.com\"}
//     [dados_item] => [{\"id_item_contratado\":\"64\",\"nm_produto\":\"HORA DE CONSULTORIA\",\"qt_produto_requisicao\":\"8\",\"nm_unidade_medida\":\"UNIDADE\",\"vl_item_contratado\":\"80.00\"}]
//     [dados_questao] => [{\"id_questao\":\"1\",\"ds_questao\":\"Razão Social Incorreta\"},{\"id_questao\":\"2\",\"ds_questao\":\"RS da empresa diferente do empenho\"},{\"id_questao\":\"3\",\"ds_questao\":\"Data da NF anterior ao empenho\"},{\"id_questao\":\"4\",\"ds_questao\":\"Data do recebimento anterior a N.F.\"},{\"id_questao\":\"5\",\"ds_questao\":\"Descricao do produto ou servico incorreta\"},{\"id_questao\":\"6\",\"ds_questao\":\"Unidade do produto incorreta\"},{\"id_questao\":\"7\",\"ds_questao\":\"Calculo da NF incorreto\"},{\"id_questao\":\"8\",\"ds_questao\":\"NF com rasura\"},{\"id_questao\":\"9\",\"ds_questao\":\"NF em 2a Via\"},{\"id_questao\":\"10\",\"ds_questao\":\"Falta carimbo do recebimento\"},{\"id_questao\":\"11\",\"ds_questao\":\"Recebimento anterior a NF\"},{\"id_questao\":\"12\",\"ds_questao\":\"Falta Ass.\\/nome\\/Mat. No receb.\"},{\"id_questao\":\"13\",\"ds_questao\":\"CNPJ incorreto\"},{\"id_questao\":\"14\",\"ds_questao\":\"Falta no. Empenho\\/contrato\\/proc.\"},{\"id_questao\":\"15\",\"ds_questao\":\"Carimbo ilegivel\"},{\"id_questao\":\"16\",\"ds_questao\":\"Valor da NF superior ao emp.\"},{\"id_questao\":\"17\",\"ds_questao\":\"Soma das requis. Difere da NF\"},{\"id_questao\":\"18\",\"ds_questao\":\"Requis. Anteriores ao empenho\"}]
//     [nr_notafiscal] => 10
//     [dt_notafiscal] => 01/06/2012
//     [qt_produto_recebido_64] => 3
//     [vl_produto_recebido_64] => 240,00
//     [id_motivo_64] => 2
//     [vl_notafiscal] => 240,00
//     [id_questao] => Array
//         (
//             [0] => 6
//         )
// 
//     [ds_pendencias] => 
// )


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

$ds_pendencias = null;

//echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
$dados_requisicao  = formata::decodeJSON($_POST['dados_requisicao']);
//echo "<pre>"; print_r($dados_requisicao); echo "</pre>"; exit;


	foreach($dados_requisicao AS $key => $valor){
		$$key = formata::formataValor($key, $valor);
	}

	foreach($_POST AS $key => $valor){

		if($valor != ""){

			if(formata::validaColuna($key, array('btn_incluir', 'btn_limpar', 'hdn_acao', 'btn_cons', 'dados_item', 'id_questao', 'dados_questao'))){

				if(strpos($key, 'produto_recebido') === false){
                    $$key = formata::formataValor($key, $valor);
					//echo $key." - ".$valor."\n";
                }
			}
		}
	}
//exit;
	//Verifica se a variavel é nula
	if(is_null($ds_pendencias)){
		$ds_pendencias = 'NULL';
	}

$dados_item  = formata::decodeJSON($_POST['dados_item']);
//echo "<pre>"; print_r($dados_item); echo "</pre>"; exit;
$dados_questao  = formata::decodeJSON($_POST['dados_questao']);
//echo "<pre>"; print_r($dados_questao); echo "</pre>"; exit;


//Variavel que guarda os erros
$erros = null;
$conn = connection::init();
$conn->query("BEGIN");

// if (($_POST[id_motivo_64]==2)or($_POST[id_motivo_64]==3)){$sql = "ALTER TABLE notas_fiscais ....";}else{insert
$sql = "INSERT INTO notas_fiscais (nr_notafiscal, dt_notafiscal, id_requisicao, vl_notafiscal, id_usuario, ch_notafiscal, ds_pendencias, id_fornecedor, id_status_nota) VALUES ($nr_notafiscal, $dt_notafiscal, $id_requisicao, $vl_notafiscal, $id_usuario, 'D', $ds_pendencias, $id_fornecedor, '3')";
//echo "<br>sql1: ".$sql; exit;
$conn->query($sql);
if($conn->get_status()==false) $erros[] = $conn->get_msg();

foreach($dados_item AS $item){
    $id_item_contratado = $item['id_item_contratado'];
    $qt_produto_recebido = formata::formataValor("qt_", $_POST['qt_produto_recebido_'.$id_item_contratado]);
    $vl_produto_recebido = formata::formataValor("vl_", $_POST['vl_produto_recebido_'.$id_item_contratado]);
    $id_motivo = formata::formataValor("id_", $_POST['id_motivo_'.$id_item_contratado]);
    
	$sql = "SELECT b.id_item_contratado AS id FROM notas_fiscais AS a INNER JOIN items_recebidos AS b
	USING (id_notafiscal) WHERE a.nr_notafiscal = $nr_notafiscal AND a.id_fornecedor = $id_fornecedor 
	AND b.id_item_contratado = $id_item_contratado 
	LIMIT 1";
	//echo $sql; exit;
	$conn->query($sql);
	if($conn->get_status()==false) $erros[] = $conn->get_msg();
	$t = $conn->fetch_row();
	if($t['id'] && $ds_pendencias == 'NULL') $erros['items'][] = $t['id'];
	//echo "<pre>"; print_r($erros); echo "</pre>"; exit;
	//echo $N; exit;

	if(!$erros){
    
    	$sql = "INSERT INTO items_recebidos (id_notafiscal, id_requisicao, id_item_contratado, qt_produto_recebido, vl_produto_recebido, id_motivo)
                    VALUES ((SELECT LAST_VALUE FROM next_notasfiscais), $id_requisicao, $id_item_contratado, $qt_produto_recebido, $vl_produto_recebido, $id_motivo)";
    	//echo "<br>sql2: ".$sql; exit;
    	$conn->query($sql);
    	if($conn->get_status()==false) $erros[] = $conn->get_msg();
    }
}

foreach($_POST['id_questao'] AS $id_questao){
    $sql = "INSERT INTO questoes_notas_fiscais (id_notafiscal, id_questao)
                    VALUES ((SELECT LAST_VALUE FROM next_notasfiscais), $id_questao)";
    //echo "<br>sql3: ".$sql; exit;
    $conn->query($sql);
    if($conn->get_status()==false) $erros[] = $conn->get_msg();
}

if($erros){ 

	if(count($erros['items']) > 0){
	?>
		<link href="../../css/audita.css" rel="stylesheet" type="text/css" />
		<br>
		<pre class="erro" align="center">Ocorreu algum erro no momento da Devolução da Auditoria.<br>Items da Nota Fiscal já estão registradas no sistema para essa mesma empresa.<br>Preencha o campo "Outras exigências constatadas" para finalizar a devolução.</pre>
		<p align="center">
		   <input type="button" name="btn_fechar" class="botao" Value="VOLTAR" onclick="javascript:history.back()">
		</p>	

	<?	
	}else{

	?>

		<link href="../../css/audita.css" rel="stylesheet" type="text/css" />
		<br>
		<pre class="erro" align="center">Ocorreu algum erro no momento da Devolução da Auditoria.<br>Verifique se este numero de Nota Fiscal já não está registrada no sistema para essa mesma empresa.<br>O sistema só aceita o mesmo número de nota fiscal, mas de empresas diferentes.</pre>
		<p align="center">
		   <input type="button" name="btn_fechar" class="botao" Value="VOLTAR" onclick="javascript:history.back()">
		</p>
	<?
	}
	//echo "<pre>"; print_r($erros); echo "</pre>"; exit;
	$conn->query("ROLLBACK");
	exit;

} else {
	$conn->query("COMMIT");
}

//exit;
//Cabecalho para ser visualizado na tabela


$cabecalho = array("Item", "Produto/Serviço", "Qt", "Unidade", "Valor Unitário", "Qt Recebida", "Valor");
/*
 *  INICIO DO PDF
 */
$arq = "devolucao-".$id_requisicao."-".date("Ymd").".pdf";


/*
 *  REVER
 */
$dados_requisicao  = formata::decodeJSON($_POST['dados_requisicao']);
/**
 *  Criação do Hash do link de email
 */
$hash = formata::password_hash($dados_requisicao['ds_cnpj'], 'md5') . formata::password_hash($dados_requisicao['ds_cnpj'], 'crypt');
$conn->query("INSERT INTO fornecedor_email (id_fornecedor, ds_codigo, ds_empenho, ds_email) VALUES ('".$dados_requisicao['id_fornecedor']."', '$hash', '".$dados_requisicao['ds_empenho']."', 'TERMO DE DEVOLUÇÃO DA NOTA DA AUDITORIA PARA O FORNECEDOR')");
connection::close();


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
// Função que cria a tabela
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
        $this->Cell($w[6],6,str_replace(".", ",", $_POST['vl_produto_recebido_'.$row['id_item_contratado']]),'LR',0,'C',$fill);
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
    $this->Cell(18,7,str_replace(".", ",", $_POST['vl_notafiscal']),'LTR',0,'C',true);
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
$pdf->MultiCell(0, 0, "TERMO DE DEVOLUÇÃO PARA FORNECEDOR", 0, 'C');

$pdf->SetFont('Arial','',12);
$pdf->Ln(10);
$pdf->MultiCell(0, 0, "Devolvemos, em anexo, Nota Fiscal abaixo descrita por apresentar as seguintes inconformidade(s):", 0, 'L');
$pdf->Ln(5);
// Colors, line width and bold font
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(0.2);

$pdf->MultiCell(0,6, ajustaTexto($_POST['id_questao'], $dados_questao, $_POST['ds_pendencias']), 1, 1, 'L');

$pdf->SetX(25);
$pdf->Ln(10);
$pdf->MultiCell(0, 0, "CNPJ: ".$dados_requisicao['ds_cnpj'], 0, 'L');

$pdf->Ln(10);
$pdf->MultiCell(0, 0, "CNPJ Unidade Orç.: ".$dados_requisicao['ds_cnpj_unidade_orcamentaria'], 0, 'L');

$pdf->Ln(10);
$pdf->MultiCell(0, 0, "Fornecedor: ".$dados_requisicao['nm_fornecedor'], 0, 'L');

$pdf->Ln(10);
$pdf->MultiCell(0, 0, "Requisição: ".$dados_requisicao['ds_requisicao'], 0, 'L');

$pdf->Ln(10);
$pdf->MultiCell(0, 0, "Data Requisição: ".$dados_requisicao['dt_requisicao'], 0, 'L');

$pdf->Ln(10);
$pdf->MultiCell(0, 0, "Número da Nota Fiscal: ".$_POST['nr_notafiscal'], 0, 'L');

$pdf->Ln(10);
$pdf->MultiCell(0, 0, "Data da Nota Fiscal: ".$_POST['dt_notafiscal'], 0, 'L');

//Posição da tabela
$pdf->SetX(20);
$pdf->Ln(10);
/*
 *  Cria a Tabela de itens requisitados
 */
$pdf->Table($cabecalho,$dados_item);

$pdf->Ln(10);

$pdf->SetFont('Arial','',12);

$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(0.2);

//$pdf->MultiCell(0,6, "Declaro ter recebido a nota fiscal especificada no presente documento.", 1, 1, 'L');


/*
 *  Exibe a saída do PDF
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
							<td align='center'>".$item['vl_item_contratado']."</td>
							<td align='center'>".$_POST['qt_produto_recebido_'.$item['id_item_contratado']]."</td>
							<td align='center'>".$_POST['vl_produto_recebido_'.$item['id_item_contratado']]."</td>
						 </tr>";
			}

			$mensagem .= "<tr  style=\"background: #ADD8E6;\"><th colspan='6' align='left'>TOTAL</th><th>".$_POST['vl_notafiscal']."</th></tr>";
		$mensagem .= "</table>
		<p align='center'>PARA VALIDAÇÃO DESSE EMAIL <a href='$texto_hash' target='_blank'>CLIQUE AQUI</a></p>
		";

// Monta a mensagem que será enviada
$corpo = "$mensagem";
$corpoSimples = "$mensagem";

// Informo o Host, FromName, From, subject e para quem o e-mail será enviado
//$mail->Host = 'correio.cbm.sc.gov.br';
//$mail->FromName = $nome;
//$mail->From = $email;
$mail->Subject = 'TERMO DE DEVOLUÇÃO DA NOTA DA AUDITORIA PARA O FORNECEDOR';
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
