<?php
//print_r($_POST); exit;
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

$sql = "SELECT a.id_rotina, b.id_modulos, ch_consultar, ch_inserir, ch_alterar, ch_excluir
		FROM perfilamentos AS a, rotinas b WHERE a.id_rotina=b.id_rotina AND id_perfil ='".$_POST['id']."'";
//echo $sql;
$conn = connection::init();
$conn->query($sql);
$dados = $conn->get_tupla();
connection::close();

//print_r($dados); exit;

if($dados){
	header("Content-type: application/xml");
	$xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
	$xml.= "<root>\n";
	$xml.= "<lib>1</lib>\n";
	foreach($dados AS $dado){
		$xml.= "<registro>\n";
		foreach($dado AS $k => $v){
			$xml.= "<{$k}>{$v}</{$k}>\n";
		}
		$xml.="</registro>\n";
	}
	$xml.="</root>\n";
}else{
	header("Content-type: application/xml");
	$xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
	$xml.= "<root>\n";
	$xml.= "<lib>0</lib>\n";
	$xml.= "</root>\n";
}

echo $xml;

exit;

?>
