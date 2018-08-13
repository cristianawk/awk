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

$page = isset($_POST['page']) ? $_POST['page'] : 1;
$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'ICONT.id_item_contratado';
$sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'asc';
$query = isset($_POST['query']) ? $_POST['query'] : false;
$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;


//print_r($_GET); exit;

/*
 *  Cuida da ordençaõ da tabela
 *  @param	: $_REQUEST['sortColumn'] -> Nome do Campo
 *  @param	: $_REQUEST['ascDescFlg'] -> Tipo de ordenação ASC || DESC
 */
//if(isset($_REQUEST['sortColumn'])){ $sort = $_REQUEST['sortColumn']; } else { $sort = 'id_item_contratado'; }
//if(isset($_REQUEST['ascDescFlg'])){ $ascDescFlg = $_REQUEST['ascDescFlg']; } else { $ascDescFlg = 'ASC'; }

$itens = array();
$conn = connection::init();

$sort = "ORDER BY $sortname $sortorder";

$where = "";
if ($query) $where = "AND lower($qtype) LIKE lower('%$query%')";

//$sql = "SELECT id_item_contratado, id_produto, id_unidade_medida, vl_item_contratado, qt_item_contratado FROM items_contratados WHERE id_empenho = ".$_GET['id']." ORDER BY $sort $ascDescFlg";
$sql = "SELECT 
		ICONT.id_empenho, ICONT.id_item_contratado, ICONT.id_produto, tira_acentos(PROD.NM_PRODUTO) as NM_PRODUTO, ICONT.id_unidade_medida, UNMED.NM_UNIDADE_MEDIDA, ICONT.vl_item_contratado, ICONT.qt_item_contratado,
		SUM(IREQ.qt_produto_requisicao) AS qt_produto, dt_vigencia
	FROM 
		items_contratados ICONT
		LEFT JOIN items_requisicao IREQ ON (IREQ.id_item_contratado = ICONT.id_item_contratado AND IREQ.id_empenho = ICONT.id_empenho)
		INNER JOIN PRODUTOS PROD ON (PROD.ID_PRODUTO = ICONT.ID_PRODUTO)
		LEFT JOIN TIPO_UNIDADE_MEDIDA UNMED ON (UNMED.ID_UNIDADE_MEDIDA = ICONT.id_unidade_medida)
	WHERE 
		ICONT.id_empenho = ".$_GET['id']." $where
	GROUP BY 
		ICONT.id_empenho, ICONT.id_item_contratado, ICONT.id_produto, PROD.NM_PRODUTO, ICONT.id_unidade_medida, UNMED.NM_UNIDADE_MEDIDA, ICONT.vl_item_contratado, ICONT.qt_item_contratado, dt_vigencia
	$sort $limit";
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

	if($d['dt_vigencia'] != ""){
		$data_inverter = explode("-",$d['dt_vigencia']);
		$d['dt_vigencia'] = $data_inverter[2].'/'. $data_inverter[1].'/'. $data_inverter[0];
	}
	$itens[] = array($d['id_empenho'],$d['id_item_contratado'],$d['id_produto'],$d['nm_produto'],$d['id_unidade_medida'],$d['nm_unidade_medida'],$d['qt_item_contratado'],$d['vl_item_contratado'],$perm, $d['qt_produto'], $d['dt_vigencia']);
}
//echo "<pre>"; print_r($itens); echo "</pre>"; exit;
connection::close();



$total = count($itens);
$itens = array_slice($itens,($page-1)*$rp,$rp);


header('Content-type: application/json');
$jsonData = array('page'=>$page,'total'=>$total,'rows'=>array());
foreach($itens AS $row){
	//If cell's elements have named keys, they must match column names
	//Only cell's with named keys and matching columns are order independent.
	$entry = array('id_empenho'=>$row['0'],
		'cell'=>array(
			'id_item_contratado'=>$row['1'],
			'id_produto'=>$row['2'],
			'nm_produto'=>$row['3'],
			'id_unidade_medida'=>$row['4'],
			'nm_unidade_medida'=>$row['5'],
			'qt_item_contratado'=>$row['6'],
			'vl_item_contratado'=>$row['7'],
			'permissao'=>$row['8'],
			'qt_produto'=>$row['9'],
			'dt_vigencia'=>$row['10']
		),
	);
	$jsonData['rows'][] = $entry;
}
echo json_encode($jsonData)
?>

