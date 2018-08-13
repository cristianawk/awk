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
if(@$_GET['idr'] != ""){
		/*
		 * Busca os dados com a quantidade de items ja existentes na requicisão
		 */
		$sql = "SELECT a.id_item_contratado, a.id_produto, 
		(CASE WHEN (SELECT (a.qt_item_contratado - SUM(c.qt_produto_requisicao))
		FROM requisicoes AS b LEFT JOIN items_requisicao AS c ON (b.id_empenho=c.id_empenho AND b.id_requisicao=c.id_requisicao)
		WHERE a.id_empenho=b.id_empenho AND a.id_item_contratado=c.id_item_contratado) IS NULL THEN a.qt_item_contratado
		ELSE  (SELECT (a.qt_item_contratado - SUM(c.qt_produto_requisicao))
		FROM requisicoes AS b LEFT JOIN items_requisicao AS c ON (b.id_empenho=c.id_empenho AND b.id_requisicao=c.id_requisicao)
		WHERE a.id_empenho=b.id_empenho AND a.id_item_contratado=c.id_item_contratado) END) AS qt_item_contratado,
		a.id_unidade_medida, d.qt_produto_requisicao
		FROM items_contratados AS a JOIN items_requisicao AS d ON (a.id_empenho=d.id_empenho AND a.id_item_contratado=d.id_item_contratado) 
		WHERE a.id_empenho = ".$_GET['id']." AND d.id_requisicao = ".$_GET['idr']." ORDER BY $sort $ascDescFlg";
	
}else{
		$sql = "SELECT a.id_item_contratado, a.id_produto, (CASE WHEN (SELECT (a.qt_item_contratado - SUM(c.qt_produto_requisicao))
		FROM requisicoes AS b LEFT JOIN items_requisicao AS c ON (b.id_empenho=c.id_empenho AND b.id_requisicao=c.id_requisicao)
		WHERE a.id_empenho=b.id_empenho AND a.id_item_contratado=c.id_item_contratado) IS NULL THEN a.qt_item_contratado
		ELSE  (SELECT (a.qt_item_contratado - SUM(c.qt_produto_requisicao))
		FROM requisicoes AS b LEFT JOIN items_requisicao AS c ON (b.id_empenho=c.id_empenho AND b.id_requisicao=c.id_requisicao)
		WHERE a.id_empenho=b.id_empenho AND a.id_item_contratado=c.id_item_contratado) END) AS qt_item_contratado,
		a.id_unidade_medida
		FROM items_contratados AS a WHERE id_empenho = ".$_GET['id']." ORDER BY $sort $ascDescFlg";
}
//echo $sql; exit;
$conn->query($sql);
while($d = $conn->fetch_row()) $itens[] = $d;
//echo "<pre>"; print_r($itens); echo "</pre>"; exit;
connection::close();

header('Content-type: application/json');
?>
{
	rows : <?php echo formata::encodeJSON($itens)?>
}
