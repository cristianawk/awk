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
//print_r($_GET); exit;

/*
 *  Cuida da ordençaõ da tabela
 *  @param	: $_REQUEST['sortColumn'] -> Nome do Campo
 *  @param	: $_REQUEST['ascDescFlg'] -> Tipo de ordenação ASC || DESC
 */
if(isset($_REQUEST['sortColumn'])){ $sort = $_REQUEST['sortColumn']; } else { $sort = 'id_item_contratado'; }
if(isset($_REQUEST['ascDescFlg'])){ $ascDescFlg = $_REQUEST['ascDescFlg']; } else { $ascDescFlg = 'ASC'; }

$itens = array();
$conn = connection::init();
//$sql = "SELECT id_item_contratado, id_produto, id_unidade_medida, vl_item_contratado, qt_item_contratado FROM items_contratados WHERE id_empenho = ".$_GET['id']." ORDER BY $sort $ascDescFlg";
$sql = "SELECT a.id_item_contratado, a.id_produto, a.id_unidade_medida, a.vl_item_contratado, a.qt_item_contratado,
		SUM(b.qt_produto_requisicao) AS qt_produto
		FROM items_contratados AS a
		LEFT JOIN items_requisicao AS b ON (b.id_item_contratado=a.id_item_contratado AND b.id_empenho=a.id_empenho)
		WHERE a.id_empenho = ".$_GET['id']."
		GROUP BY a.id_item_contratado, a.id_produto, a.id_unidade_medida, a.vl_item_contratado, a.qt_item_contratado
		ORDER BY $sort $ascDescFlg";
//echo $sql; exit;
$conn->query($sql);
$num_row = $conn->num_rows();
while($d = $conn->fetch_row()){
	if($d['qt_produto'] == ""){
		$d['qt_produto'] = "0";
		$perm = 'SIM';
	}elseif($d['qt_produto'] == $d['qt_item_contratado']){
		$perm = 'QT+';
	}elseif($d['qt_produto'] < $d['qt_item_contratado']){
		$perm = 'QT-';
	}elseif($d['qt_produto'] > $d['qt_item_contratado']){
		$perm = 'NÃO';
	}
	$itens[] = array($d['id_item_contratado'],$d['id_produto'],$d['id_unidade_medida'],$d['qt_item_contratado'],$d['vl_item_contratado'],$perm, $d['qt_produto']);
}
//echo "<pre>"; print_r($itens); echo "</pre>"; exit;
connection::close();

for($i = $_GET['nr']; $i > $num_row; $i--){
	$itens[] = array('','','','','','SIM','0');
}


header('Content-type: application/json');
?>
{
	rows : <?=formata::encodeJSON($itens)?>
}
