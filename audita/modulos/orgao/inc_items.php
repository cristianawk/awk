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

foreach($_POST AS $key => $value){

	if($key != 'modifiedRows'){

		$$key = formata::formataValor($key, $value);

	}

}
//Transforma o objeto JSON em um array
$modifiedRows = formata::decodeJSON($_POST['modifiedRows']);

//Pega o numero de linhas modificadas
$num_modifiedRows = count($modifiedRows);

//print_r($modifiedRows); exit;

$sqls = array();

$sqls[] = "UPDATE empenhos SET ch_bloqueio = 'S' WHERE id_empenho = $id_empenho";

$sqls[] = "INSERT INTO requisicoes (id_empenho, nr_requisicao, ds_requisicao, dt_requisicao, hr_requisicao, nr_itens_requisicao, id_unidade)
			VALUES ($id_empenho, $nr_requisicao, $ds_requisicao, $dt_requisicao, $hr_requisicao, $num_modifiedRows, $id_unidade)";

// LINHAS ALTERADAS
if(count($modifiedRows) > 0){

	foreach($modifiedRows AS $modifiedRow){

		foreach($modifiedRow AS $key => $value){ $$key = formata::formataValor($key, $value); }

		if($id_item_contratado != "NULL"){

			$sqls[] = "INSERT INTO items_requisicao (id_requisicao, qt_produto_requisicao, id_item_contratado, id_empenho)
				VALUES ((SELECT LAST_VALUE FROM next_requisicoes), $qt_produto_requisicao, $id_item_contratado, $id_empenho)";


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
		//echo ($conn->get_msg());
		$msg = "Aconteceu algum erro na inserção dos dados!!";
		$flg = 0;
		$conn->query("ROLLBACK");

	}else{
		$conn->query("COMMIT");
		$msg = "O registro foi inserido com sucesso!!";
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
