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

//echo "<pre>"; print_r($_POST); echo "</pre>";

$dados = null;
$sql = "SELECT id_empenho, UPPER(ds_empenho) AS ds_empenho, ch_contrato, ch_requisicao, id_unidade,
		to_char(dt_empenho, 'DD/MM/YYYY') AS dt_empenho, vl_empenho, id_fornecedor, nr_items_contratados, nr_contrato,
		to_char(dt_inicio, 'DD/MM/YYYY') AS dt_inicio, to_char(dt_final, 'DD/MM/YYYY') AS dt_final, ds_contrato,
		ds_cnpj_unidade_orcamentaria, ch_bloqueio, COUNT(id_item_contratado) AS nr_items_encontrados,
        to_char(dt_contrato, 'DD/MM/YYYY') AS dt_contrato
		FROM empenhos LEFT JOIN items_contratados USING (id_empenho)
        WHERE UPPER(ds_empenho) = '".strtoupper($_POST['id'])."'
        GROUP BY empenhos.id_empenho, ds_empenho, ch_contrato, ch_requisicao, id_unidade,
		 dt_empenho, vl_empenho, id_fornecedor, nr_items_contratados, nr_contrato,
		dt_inicio, dt_final, ds_contrato,
		ds_cnpj_unidade_orcamentaria, ch_bloqueio, dt_contrato";
//echo $sql; exit;
$conn = connection::init();
$conn->query($sql);
$dados = $conn->fetch_row();
connection::close();
setlocale(LC_MONETARY,"pt_BR", "ptb");

	/**
	 *  Substitui o ponto pela virgula -> Rever e melhorar
	 */
	//if($dados) $dados['vl_empenho'] = str_replace(".", ",", $dados['vl_empenho']);
	if($dados) $dados['vl_empenho'] = money_format('%n',$dados['vl_empenho']);

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
