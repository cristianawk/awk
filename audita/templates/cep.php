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

	$sql = "SELECT a.nr_cep_logradouro, a.nm_logradouro, b.nm_bairro, c.nm_cidade, d.id_estado FROM logradouros AS a
			JOIN bairros AS b ON (a.id_bairro=b.id_bairro)
			JOIN cidades AS c ON (b.id_cidade=c.id_cidade)
			JOIN estados AS d ON (d.id_estado = c.id_estado)
			WHERE a.nr_cep_logradouro = '".str_replace("-","",$_POST['cep'])."'";

	//echo $sql;
	$conn = connection::init();
	$conn->query($sql);
	$dados = $conn->fetch_row();
	connection::close();

	//echo "<pre>"; print_r($dados); echo "</pre>"; exit;
	/**
	 *  Quebra a String pelo "-" e coloca a primeira parte que vem com o nome completo
	 * 	A segunda parte vem a numeração da rua
	 */
	$aux = explode("-", $dados['nm_logradouro']);
	$dados['nm_logradouro'] = $aux[0];

	if($conn->num_rows()>0){
		header("Content-type: application/xml");
		$xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
		$xml.= "<root>";
		$xml.= "<lib>1</lib>";
		$xml.= "<naba>tem dados</naba>";
		foreach($dados AS $id => $ds){
			$xml.= "<{$id}>{$ds}</{$id}>";
		}
		$xml.="</root>";
	}else{
		header("Content-type: application/xml");
		$xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
		$xml.= "<root>";
		$xml.= "<lib>0</lib>";
		$xml.= "<naba>não tem dados</naba>";
		$xml.= "</root>";
	}

	echo $xml;

	exit;
?>
