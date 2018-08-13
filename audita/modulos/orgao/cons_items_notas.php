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
$sql = "SELECT d.id_item_contratado, e.nm_produto, a.qt_produto_requisicao, f.nm_unidade_medida, d.vl_item_contratado,
						'' AS qt_recebida, '' AS vl_total, 1 AS motiv
						FROM items_requisicao AS a
						JOIN requisicoes AS b ON (a.id_requisicao=b.id_requisicao AND a.id_empenho=b.id_empenho)
						JOIN empenhos AS c ON (a.id_empenho=c.id_empenho AND b.id_unidade=c.id_unidade)
						JOIN items_contratados AS d ON (a.id_item_contratado = d.id_item_contratado AND a.id_empenho=d.id_empenho)
						JOIN produtos AS e USING (id_produto)
						JOIN tipo_unidade_medida AS f USING (id_unidade_medida)
						WHERE b.id_requisicao =".$_GET['id_requisicao']." AND c.id_empenho = ".$_GET['id_empenho']." ORDER BY $sort $ascDescFlg";
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
