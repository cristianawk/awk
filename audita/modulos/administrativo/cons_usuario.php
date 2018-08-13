<?php
//echo "arquivo cons_usuario.php";
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

//echo "<pre>"; print_r($_GET); "</pre>";

if ($_POST['id']==""){
	$sql = "SELECT * FROM vw_usuarios WHERE nm_usuario = '".strtoupper($_POST['nm'])."'";
}else{
	$sql = "SELECT * FROM vw_usuarios WHERE ds_cpf_usuario = '".$_POST['id']."'";
}


//$sql = "SELECT * FROM vw_usuarios WHERE ds_cpf_usuario = '".$_POST['id']."'";
//echo $sql;
$conn = connection::init();
$conn->query($sql);
$dados = $conn->fetch_row();
connection::close();

if($dados){
	header("Content-type: application/xml");
	$xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
	$xml.= "<root>\n";
	$xml.= "<lib>1</lib>\n";
	foreach($dados AS $id => $ds){
		if($id != 'ps_senha'){
			$xml.= "<{$id}>{$ds}</{$id}>\n";
		}
		if($id == ''){
			$xml.= "<teste>\n";
		}
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
