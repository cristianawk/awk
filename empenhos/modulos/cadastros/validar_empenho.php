<?php
require "../../lib/loader.php";

//echo "<pre>"; print_r($_POST); echo "</pre>";

$empenho = "'".strtoupper($_POST['empenho'])."'";

$sql = "SELECT UPPER(ds_empenho) AS ds_emepenho FROM ".TBL_EMPENHO." WHERE UPPER(ds_empenho) = $empenho AND ch_visualizar = 'S'";
//echo $sql; exit;
$global_conn->query($sql);
$num = $global_conn->num_rows();
if(($num != 0)||($num != "")){
    $tupla = $global_conn->fetch_row();
    $tupla['ds_emepenho'];

    $flg = 1;
    $msg = "J� existe um empenho com a descri��o ".$tupla['ds_emepenho']." na base do Sistema de Controle de Prazos.\nCaso deseje visualizar as informa��es desse empenho, aperte o bot�o sim, caso contr�rio, aperte o bot�o n�o.";

}else{
    $flg = 0;
    $msg = "Nenhum empenho encontrado.";

}




    header("Content-type: application/xml");
	$xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
	$xml.= "<root>";
	$xml.= "<flg>$flg</flg>";
	$xml.= "<mesg>$msg</mesg>";
	$xml.="</root>";

	echo $xml;

	exit;
?>
