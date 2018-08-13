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

	$id_perfil = $_POST['id_perfil'];

	$msg = null;
	$flg = null;
	$sqls = array();

	//Inicaio da transação SQL
	$sqls[] = "BEGIN";

	//Deleta todos os registros referentes ao perfil
	$sqls[] = "DELETE FROM perfilamentos WHERE id_perfil = {$id_perfil}";

	foreach($_POST['chk_perfil'] AS $id_rotina => $arr){

		$colunas = null;
		$valores = null;
		$arrColunas = null;
		$arrValores = null;

		$arrColunas[] = "id_perfil, id_rotina";
		$arrValores[] = "{$id_perfil}, {$id_rotina}";

		foreach($arr AS $ch => $vl){
			$arrColunas[] = $ch;
			$arrValores[] = "'{$vl}'";
		}

		$colunas  = implode(", ", $arrColunas);//echo $colunas;
		$valores  = implode(", ", $arrValores);//echo $valores;
		$sqls[] = "INSERT INTO perfilamentos ($colunas) VALUES ($valores) ";

	}

	//echo "<pre>"; print_r($sqls); echo "</pre>"; exit;

	$c = connection::init();
	//echo "AQUI";
	foreach($sqls AS $sql){
		$c->query($sql);
	}

	if($c->get_status()==false){
		echo ($conn->get_msg());
		$c->query("ROLLBACK");
		$msg = "Aconteceu algum erro na inserção/alteração dos dados!!";
		$flg = 0;

	}else{
		$c->query("COMMIT");
		$msg = "Os registros foram inseridos/alterados com sucesso!!";
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
