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

//print_r($_POST);exit;

$id_empenho = $_POST['id'];
$newRowsAddeds = formata::decodeJSON($_POST['newRowsAdded']);
$modifiedRows = formata::decodeJSON($_POST['modifiedRows']);
$deletedRows = formata::decodeJSON($_POST['deletedRows']);

//print_r($newRowsAddeds);
//print_r($modifiedRows);
//print_r($deletedRows);
//exit;

$sqls = array();

// LINHAS ALTERADAS
if(count($modifiedRows) > 0){

	foreach($modifiedRows AS $modifiedRow){

		foreach($modifiedRow AS $key => $value){ $$key = formata::formataValor($key, $value); }

		if($id_item_contratado != "NULL"){

			if($vl_item_vigencia != "NULL" && $dt_vigencia != "NULL"){

				$sqls[] = "UPDATE items_contratados SET vl_item_contratado = $vl_item_vigencia, dt_vigencia = $dt_vigencia
					WHERE id_empenho = $id_empenho AND id_item_contratado = $id_item_contratado";

				$sqls[] = "INSERT INTO historico_valores (id_item_contratado, vl_unitario, dt_vigencia) VALUES ($id_item_contratado, $vl_item_vigencia, $dt_vigencia)";

			}

		}

	}



}

//print_r($sqls); exit;

if(count($sqls) > 0){

	$conn = connection::init();
	$conn->query("BEGIN");

	foreach($sqls AS $sql){
		$conn->query($sql);
	}


	if($conn->get_status()==false){
		echo ($conn->get_msg());
		$msg = "Aconteceu algum erro na inserção/alteração dos dados!!";
		$flg = 0;
		$conn->query("ROLLBACK");

	}else{
		$conn->query("COMMIT");
		$msg = "O registro foi inserido/alterado com sucesso!!";
		$flg = 1;
	}

	connection::close();

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
