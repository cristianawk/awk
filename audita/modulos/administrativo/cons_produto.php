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

//$msg = print_r($_POST);
$dados = null;

$sql = "SELECT * FROM produtos WHERE cd_produto = '".$_POST['id']."'";
//echo "<br>sql: ".$sql;
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
		$xml.= "<{$id}>{$ds}</{$id}>\n";
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
