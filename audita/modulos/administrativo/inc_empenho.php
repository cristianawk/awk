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
				  
		if($valor != ""){ //vl_empenho='R$ 15350.00'
			if(formata::validaColuna($key, array('btn_incluir', 'btn_limpar', 'hdn_acao', 'btn_cons', 'nr_items_encontrados', 'deletar_item'))){

				if(($_POST['id_empenho'] != "")&&($_POST['hdn_acao'] == 'alt')){

					$id_empenho = $_POST['id_empenho'];
					if($key != "id_empenho") $arrColunas[] = "".$key."=".formata::formataValor($key, $valor);
					//$arrValores['vl_empenho'] = substr($arrValores['vl_empenho'],3,20);
					//echo $key." - ".$valor."\n";
				}else{

					$arrColunas[] = $key;
					$arrValores[] = formata::formataValor($key, $valor);
					//echo $key." - ".$valor."\n";
				}
			}
		}
	}

	//echo "<pre>"; print_r($arrValores); echo "</pre>"; exit;

	if(($_POST['id_empenho'] != "")&&($_POST['hdn_acao'] == 'alt')){
		$colunas = implode(", ", $arrColunas);//echo $colunas;
		$sql = "UPDATE empenhos SET $colunas WHERE id_empenho =".$id_empenho;
		$msg = "O registro foi alterado com sucesso!";
	}else{
		$colunas = implode(", ", $arrColunas);//echo $colunas;
		$valores = implode(", ", $arrValores);//echo $valores;
		$sql = "INSERT INTO empenhos ($colunas) VALUES ($valores) ";
		$msg = "O novo registro foi inserido com sucesso!";
	}

	//echo $sql; exit;
	//$msg = $sql;
	//$flg = 0;

	$conn = connection::init();
	$conn->query($sql);
	if($conn->get_status()==false){
		//echo ($conn->get_msg());
		$msg = $sql."Aconteceu algum erro na inserção/alteração dos dados!!";
		//$msg = $key." - ".$valor."\n";
		$flg = 0;

	}else{
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

	exit;



?>
