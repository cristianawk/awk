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
//print_r($_GET);
/*
 *  Cuida da ordençaõ da tabela
 *  @param	: $_REQUEST['sortColumn'] -> Nome do Campo
 *  @param	: $_REQUEST['ascDescFlg'] -> Tipo de ordenação ASC || DESC
 */
if(isset($_REQUEST['sortColumn'])){ $sort = $_REQUEST['sortColumn']; } else { $sort = 'id_item_contratado'; }
if(isset($_REQUEST['ascDescFlg'])){ $ascDescFlg = $_REQUEST['ascDescFlg']; } else { $ascDescFlg = 'ASC'; }

$itens = array();
$conn = connection::init();
$sql = "SELECT id_item_contratado, id_produto, id_unidade_medida, vl_item_contratado, qt_item_contratado
		FROM items_contratados WHERE id_empenho = ".$_GET['id']." ORDER BY $sort $ascDescFlg";
//echo $sql; exit;
$conn->query($sql);
while($d = $conn->fetch_row()) $itens[] = $d;
//echo "<pre>"; print_r($itens); echo "</pre>"; exit;
connection::close();

header('Content-type: application/json');
?>
{
	rows : <?=formata::encodeJSON($itens)?>
}
