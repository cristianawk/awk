<?php
/*
 * carrega a classe a ser instanciada quando chamada
 */
function __autoload($classe){
	$pontos = array("./", "../", "../../");
    foreach($pontos AS $ponto){
		if(file_exists("{$ponto}class/{$classe}.class.php")){
			//echo "class/{$classe}.class.php\n";
			include_once "{$ponto}class/{$classe}.class.php";
		}
	}
}


	//echo "<pre>"; print_r($_POST); echo "</pre>"; exit;

	$msg = null;
	$flg = null;

	$colunas = null;
	$valores = null;
	$arrColunas = null;
	$arrValores = null;

	foreach($_POST AS $key => $valor){

		if($valor != ""){

			if(formata::validaColuna($key, array('btn_incluir', 'btn_cons', 'btn_limpar', 'hdn_acao'))){

				if(($_POST['id_unidade'] != "")&&($_POST['hdn_acao'] == 'alt')){

					$id_unidade = $_POST['id_unidade'];
					if($key != "id_unidade") $arrColunas[] = "".$key."=".formata::formataValor($key, $valor);

				}else{

					$arrColunas[] = $key;
					$arrValores[] = formata::formataValor($key, $valor);
					//echo $key." - ".$valor."\n";
				}
			}
		}
	}

	//echo "<pre>"; print_r($arrValores); echo "</pre>"; exit;

	if(($_POST['id_unidade'] != "")&&($_POST['hdn_acao'] == 'alt')){
		$colunas = implode(", ", $arrColunas);//echo $colunas;
		$sql = "UPDATE vw_beneficiarios SET $colunas WHERE id_unidade =".$id_unidade;
		$msg = "O registro foi alterado com sucesso!";
	}else{
		$colunas = implode(", ", $arrColunas);//echo $colunas;
		$valores     = implode(", ", $arrValores);//echo $valores;
		$sql = "INSERT INTO vw_beneficiarios ($colunas) VALUES ($valores) ";
		$msg = "O novo registro foi inserido com sucesso!";
	}

	//echo $sql; exit;
	//$msg = $sql;
	//$flg = 0;

	$conn = connection::init();
	//echo "AQUI";
	$conn->query($sql);
	if($conn->get_status()==false){
		echo ($conn->get_msg());
		$msg = "Aconteceu algum erro na inserção/alteração dos dados!!";
		$flg = 0;

	}else{
		$msg = "O registro foi inserido/alterado com suscesso!!";
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
