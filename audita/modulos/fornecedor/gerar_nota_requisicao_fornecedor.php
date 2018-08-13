<?php
//echo "<br>ta chegando aqui";
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


$conn = connection::init();

$sql = "SELECT nm_produto, nm_unidade_medida, SUM(qt_produto_requisicao) AS qt_produto, a.id_item_contratado
        FROM items_requisicao AS A
		JOIN items_contratados AS b ON (a.id_item_contratado=b.id_item_contratado AND a.id_empenho=b.id_empenho)
		JOIN produtos USING (id_produto)
		JOIN tipo_unidade_medida USING (id_unidade_medida)
		WHERE id_requisicao = ".$_POST['id_requisicao']."
		GROUP BY nm_produto, nm_unidade_medida, a.id_item_contratado";

$conn->query($sql);
$dados_requisicao = $conn->get_tupla();


$sql = "SELECT a.id_requisicao, a.ds_requisicao,
		to_char(a.dt_requisicao, 'DD/MM/YYYY') AS dt_requisicao, c.nm_fornecedor, c.id_fornecedor,
		b.ds_empenho, b.id_empenho, b.ds_cnpj_unidade_orcamentaria ,c.ds_cnpj, to_char(b.dt_empenho , 'DD/MM/YYYY') AS dt_empenho,
		SUM(d.qt_produto_requisicao) AS qt_produto, ds_email1, ds_email2
		FROM requisicoes AS a
		JOIN empenhos AS b ON (a.id_empenho=b.id_empenho AND a.id_unidade=b.id_unidade)
		JOIN fornecedores AS c ON (c.id_fornecedor=b.id_fornecedor)
		JOIN items_requisicao AS d ON (a.id_requisicao=d.id_requisicao AND a.id_empenho=d.id_empenho)
		WHERE a.id_requisicao = ".$_POST['id_requisicao']."
		GROUP BY a.id_requisicao, a.ds_requisicao, a.dt_requisicao, c.nm_fornecedor , c.id_fornecedor,
        b.ds_empenho, b.id_empenho, b.ds_cnpj_unidade_orcamentaria, c.ds_cnpj, b.dt_empenho, ds_email1, ds_email2
		ORDER BY nm_fornecedor, id_requisicao";

$conn->query($sql);
$dados_empenho = $conn->fetch_row();


$sqlleitura = "SELECT * FROM nota_status WHERE id_requisicao = ".$_POST['id_requisicao'];

$qtde = pg_query($sqlleitura);

if (pg_num_rows($qtde) == 0) {
	$sqllido = "INSERT INTO nota_status (id_requisicao, status, data, hora) VALUES (" . $_POST['id_requisicao'] . ", 'lido', '" . date("Y-m-d") . "', '" . date("H:i:s") . "');";
	$sqllidoinsert = pg_query($sqllido);

}

if (pg_num_rows($qtde) == 1) {
	$nota_st = pg_fetch_array($qtde);

	if ($nota_st['status'] == 'enviado') { 
		$sqllido = "UPDATE nota_status SET status = 'lido', data = '" . date("Y-m-d") . "', hora = '" . date("H:i:s") . "' WHERE id_requisicao = " . $_POST['id_requisicao'] . ";";
		$sqllidoupdate = pg_query($sqllido);
	}
}
connection::close();

//$dados_requisicao = formata::decodeJSON($_POST['dados_requisicao']);
//$dados_empenho  = formata::decodeJSON($_POST['dados_empenho']);

//Cabecalho para ser visualizado na tabela
$cabecalho = array("Item", "Qt", "Unidade", "Produto/Serviço");

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
    $this->SetY(13);
    $this->MultiCell(0, 0, "ESTADO DE SANTA CATARINA", 0, 'C');
    $this->SetY(20);
    $this->MultiCell(0, 4, "CORPO DE BOMBEIROS MILITAR", 0, 'C');
    // Line break
    //$this->Ln(20);
}


// Função que cria a tabela
function Table($header, $data){
    // Colors, line width and bold font
    $this->SetFillColor(173,216,230);
    $this->SetTextColor(0);
    $this->SetDrawColor(0,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B',10);	
    // Header
    $w = array(10, 10, 20, 150);
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
        $this->SetX(10);
        $this->Cell(10,5,$row['id_item_contratado'],'LR',0,'C',$fill);
        //$this->Cell($w[1],11,$row['nm_produto'],'LR',0,'L','J');
		//$pfd->Write('15','O meu texto Longo');
		$this->Cell(10,5,$row['qt_produto'],'LR',0,'C',$fill);
        $this->Cell(20,5,$row['nm_unidade_medida'],'LR',0,'C',$fill);
        $this->Cell(150,5,$row['nm_produto'],'LR',0,'J',$fill);
		$this->Ln();
        $this->SetX(10);
        $fill = !$fill;
    }
    // Closing line
    $this->Cell(array_sum($w),1,'','T');
}

// Page footer
function Footer()
{
     // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Página '.$this->PageNo(),0,0,'C');
    $CNPJ_UO = array('14.186.135/0001-06' => "CBM", '14.186.135/0001-06' => "FUMCBM");
	$this->SetFont('Arial','',9);
	$this->SetY(-20);
	//$this->Cell(0, 10, "-------------------------------------------------------", 0, 0,'C');
	//$this->SetY(-271);
	$this->Cell(0, 7, $_POST['un_orcamentaria'].' - Data da requisição: '.date('d/m/Y').'  às '.date(' H:m:s'), 0, 0,'L');
	$this->SetY(-15);
	$this->Cell(0, 7, 'Bombeiro Militar requisitante: '.$_POST['nm_usuario'].' - Unidade Benificiaria: '.$_POST['nm_unidade'], 0, 0,'L');
	//$this->SetY(-12);
	//$this->Cell(0, 7, 'Unidade Benificiaria: '.$_POST['nm_unidade'], 0, 0,'L');
	//$this->SetY(-7);
	//$this->Cell(0, 7, $_POST['un_orcamentaria'], 0, 0,'L');
}

}

//if($_POST['hdn_acao'] == 1){
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AddPage();
//$pdf->SetMargins(0,0,0,-40);

$pdf->SetFont('Arial','B',12);
$pdf->SetY(45);
$pdf->MultiCell(0, 0, "ORDEM DE REQUISIÇÃO ".$dados_empenho['ds_requisicao'], 0, 'C');

$pdf->SetFont('Arial','',12);
$pdf->SetXY(10, 55);
//Monta o texto do documento
$pdf->MultiCell(0, 6, "             A Organização Bombeiro Militar de(a) ".$_POST['nm_unidade']." solicita que o(a) ".$dados_empenho['nm_fornecedor']." CNPJ ".$dados_empenho['ds_cnpj'].", providencie o fornecimento do(s) produto(s)/serviço(s), referentes ao empenho ".$dados_empenho['ds_empenho']." de ".$dados_empenho['dt_empenho']." rigorosamente em conformidade com o especificado a seguir:", 0, 'J');

//Posição da tabela
$pdf->SetXY(10, 90);

/*
 *  Cria a Tabela de itens requisitados
 */
$pdf->Table($cabecalho,$dados_requisicao);

/*
 *  Exibe a saída do PDF
 */
//$pdf->SetAutoPageBreak(On,50);
$pdf->Output($arq, 'D');


//$pdf->Close();
//exit;
?>
<script type="text/javascript">
//function(){ parent.globalWin.hide()} 
</script>
