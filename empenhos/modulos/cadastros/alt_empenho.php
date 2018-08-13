<?php
require "../../lib/loader.php";
//echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
if((is_numeric($_POST['id_empenho']))&&($_POST['ds_empenho']!="")){

	$sql = "UPDATE ".TBL_EMPENHO." SET ch_visualizar = 'N' WHERE id_empenho =".$_POST['id_empenho'];


	//echo $sql; exit;
	//$msg = $sql;
	//$flg = 0;


	$global_conn->query($sql);
	if($global_conn->get_status()==false){
		//echo ($global_conn->get_msg());
		$msg = "Aconteceu algum erro na exclusão dos dados do empenho ".$_POST['ds_empenho']."!";
		$flg = 0;

	}else{
		$msg = "Os dados do empenho ".$_POST['ds_empenho']." foram excluidos!";
		$flg = 1;
	}

}else{

		$msg = "Empenho inválido!";
		$flg = 0;

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
