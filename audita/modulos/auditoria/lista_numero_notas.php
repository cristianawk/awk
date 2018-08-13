<?php
/*
 * carrega a classe a ser instanciada quando chamada
 */
function __autoload($classe){
	$pontos = array("./", "../", "../../");
    foreach($pontos AS $ponto){
		if(file_exists("{$ponto}class/{$classe}.class.php")){
			//echo "{$ponto}class/{$classe}.class.php\n";
			include_once "{$ponto}class/{$classe}.class.php";
		}
	}
}

$where = null;
$option = "<option value=''>---------------</option>\n";
if(@$_POST['id_fornecedor']){ $where .= " AND id_fornecedor = ".$_POST['id_fornecedor']; }
//echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
$conn = connection::init();
$conn->query("SELECT id_notafiscal, nr_notafiscal FROM notas_fiscais WHERE ch_notafiscal = 'R' $where");
//echo "<br> sql ".$conn;
$notas = $conn->get_tupla();
connection::close();
foreach($notas AS $nota){ $option .= "<option value='".$nota['id_notafiscal']."'>".$nota['nr_notafiscal']."</option>\n"; }

echo $option;

exit;
?>
