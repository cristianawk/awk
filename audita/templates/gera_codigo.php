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

/*
 *  Gera um código randomico
 */ 

	echo "<pre>"; print_r($_POST); echo "</pre>"; //exit;
    $random = $_POST['random'];
    $varchar = $_POST['varchar'];

	//$sql = "SELECT to_char(round(CAST (random()*$random AS INTEGER),0), '$varchar') AS codigo";
	//$sql = "SELECT to_char(round(CAST (random()*$random AS INTEGER),0), '$varchar') AS codigo";
	$sql = "SELECT trim(to_char(LAST_VALUE+1, '0000')) AS codigo";
	//echo $sql;
	$conn = connection::init();
	$conn->query($sql);
	$dados = $conn->fetch_row();
	connection::close();

	//echo "<pre>"; print_r($dados); echo "</pre>"; exit;

	if($dados){
		header("Content-type: application/xml");
		$xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
		$xml.= "<root>";
		foreach($dados AS $id => $cd){
			$xml.= "<{$id}>{$cd}</{$id}>";
		}
		$xml.="</root>";
	}else{
		header("Content-type: application/xml");
		$xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
		$xml.= "<root>";
		$xml.= "</root>";
	}

	echo $xml;

	exit;
?>
