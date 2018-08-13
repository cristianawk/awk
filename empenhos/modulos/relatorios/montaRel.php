<?php
require "../../lib/loader.php";

//echo "<pre>"; print_r($_POST); echo "</pre>";
$colunas = JSONDecoder($_POST['colunas']);
$valores = JSONDecoder($_POST['valores']);
$ext = $_POST['ext'];
$arquivo = date("YmdHis").".".$ext;

//echo "<pre>"; print_r($colunas); echo "</pre>";
//echo "<pre>"; print_r($valores); echo "</pre>";
//exit;

switch($ext){

	case 'txt':

		$txt = arrumaTexto($colunas, $valores, 'txt');

		header('Content-type: application/txt; charset=ISO-8859-1');
		header('Content-Disposition: attachment; filename="'.$arquivo.'"');
		header('Content-type: application/force-download');


		echo $txt;

		break;


	case 'xls':

		$xls = arrumaTexto($colunas, $valores, 'xls');

		header('Content-type: application/ms-excel; charset=ISO-8859-1');
		header('Content-Disposition: attachment; filename="'.$arquivo.'"');
		header('Content-type: application/force-download');


		echo $xls;

		break;

	case 'doc':

		$doc = arrumaTexto($colunas, $valores, 'doc');

		header('Content-type: application/ms-word; charset=ISO-8859-1');
		header('Content-Disposition: attachment; filename="'.$arquivo.'"');
		header('Content-type: application/force-download');


		echo $doc;

		break;

	case 'pdf':

		$pd = arrumaPDF($colunas, $valores, $arquivo);

		header('Content-type: application/pdf; charset=ISO-8859-1');
		header('Content-Disposition: attachment; filename="'.$arquivo.'"');
		header('Content-type: application/force-download');


		//echo $pd;

		break;


}

/*
 *  Trabalha com Texto
 */

function arrumaTexto($colunas, $valores, $e){

	if($e == 'txt'){
		$pad_length = 25;
		$delimitador = "|";
	}else{
		$pad_length = 30;
		$delimitador = ";";
	}

	$linha = null;
	// monta o cabeçalho do relatório
	foreach($colunas AS $coluna){
		$linha .= str_pad($coluna, $pad_length, ' ', STR_PAD_RIGHT).$delimitador;
	}
	$linha.= "\r\n";
	//$linha.= str_repeat('=', 400);
	//$linha.= "\r\n";

	// monta a linha de dados
	foreach($valores AS $valor){
		foreach($colunas AS $key => $coluna){
			$linha .= str_pad($valor[$key], $pad_length, ' ', STR_PAD_RIGHT).$delimitador;
		}
		$linha .= "\r\n";
	}

	// escreve uma linha de separação
	//$linha.= str_repeat('=', 400);
	//$linha.= "\r\n";

	return $linha;
}

function arrumaPDF($colunas, $valores, $arq){

	$l = 12;

    $pdf = new FPDF('P', 'pt', 'A4'); // instancia a classe FPDF
    $pdf->AddPage(); // adiciona uma página

    // define a fonte
    $pdf->SetFont('Arial','B', 5);

    // define cor de preenchimento,
    // cor de texto e espessura da linha
    $pdf->SetFillColor(173,216,230);
    $pdf->SetTextColor(0);
    $pdf->SetLineWidth(0.2);

    $pdf->SetXY(12, $l);

    // monta o cabeçalho do relatório
	foreach($colunas AS $coluna){
		$pdf->Cell( 47, 12, $coluna,    1, 0, 'C', true);
	}
    // quebra de linha
    //$pdf->Ln();

    // define cor de fundo, do
    // texto e fonte dos dados
    $pdf->SetFillColor(204,224,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial', '', 5);

	foreach($valores AS $valor){
		$l = $l + 12;
		// quebra de linha
		$pdf->SetXY(12, $l);
		foreach($colunas AS $key => $coluna){
			$pdf->Cell( 47, 12, $valor[$key],    1, 0, 'L', true);
		}
	}

    // salva o PDF em arquivo
    $pdf->Output($arq, 'D');

	$pdf->Close();

}

?>
