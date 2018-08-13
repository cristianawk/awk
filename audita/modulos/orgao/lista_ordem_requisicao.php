<?php header("Content-Type: text/html; charset=ISO-8859-1",true);
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
//echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
$dados = null;
$where = null;
if($_POST['dt_inicial'] != "" && $_POST['dt_final'] != ""){

	$dt_inicial = formata::formataValor('dt_', $_POST['dt_inicial']);
	$dt_final = formata::formataValor('dt_', $_POST['dt_final']);

    $where .= " AND dt_requisicao BETWEEN {$dt_inicial} AND {$dt_final} ";
}

if($_POST['id_fornecedor']){
    $where .= " AND c.id_fornecedor =".$_POST['id_fornecedor']." ";
}


		$sql = "SELECT a.id_requisicao, a.ds_requisicao,
				to_char(a.dt_requisicao, 'DD/MM/YYYY') AS data_requisicao, a.dt_requisicao, c.nm_fornecedor,
				b.ds_empenho, c.ds_cnpj, to_char(b.dt_empenho , 'DD/MM/YYYY') AS dt_empenho,
				SUM(d.qt_produto_requisicao) AS qt_produto
				FROM requisicoes AS a
				JOIN empenhos AS b ON (a.id_empenho=b.id_empenho AND a.id_unidade=b.id_unidade)
				JOIN fornecedores AS c ON (c.id_fornecedor=b.id_fornecedor)
				JOIN items_requisicao AS d ON (a.id_requisicao=d.id_requisicao AND a.id_empenho=d.id_empenho)
				WHERE a.id_unidade = ".$_POST['id_unidade']." {$where}
				GROUP BY a.id_requisicao, a.ds_requisicao, a.dt_requisicao, c.nm_fornecedor , b.ds_empenho, c.ds_cnpj, b.dt_empenho
				ORDER BY a.dt_requisicao, a.ds_requisicao, nm_fornecedor";
		//echo $sql; exit;
        $conn = connection::init();
		$conn->query($sql);
		$dados = $conn->get_tupla();
        

//echo "<pre>"; print_r($dados); echo "</pre>"; exit;
?>
<hr>
<table border="0" width="80%" align="center" class="orgTable">
<tr class="cab">
	<th width="10%">Requisição</th>
	<th width="35%">Fornecedor</th>
	<th width="11%">Data Requisição</th>
	<th>Quantidade de Items para Fornecer</th>
	<th width="20%" class="center">status</th>
	<th width="3%">&nbsp;</th>
</tr>
<?php if($dados){
    foreach($dados AS $dado){ 
		$sqlleitura = "SELECT * FROM nota_status WHERE id_requisicao = ".$dado['id_requisicao'];

		$qtde = pg_query($sqlleitura);
		
		if (pg_num_rows($qtde) == 0) {
			$leit_st = "Aguardando envio...";
		} else {
			$leit_st_d = pg_fetch_array($qtde);
			$data_ar = explode('-', $leit_st_d['data']);
			$dt_lido = $data_ar[2] . "/" . $data_ar[1] . "/" . $data_ar[0];
			if ($leit_st_d['status'] == 'lido') {
				$leit_st = "Arquivo lido no dia " . $dt_lido . ", às ".$leit_st_d['hora']." horas";
			} elseif ($leit_st_d['status'] == 'enviado') {
				$leit_st = "Arquivo enviado no dia " . $dt_lido . ", às ".$leit_st_d['hora']." horas";
			}
		}	
	?>
    <tr class="lin">
        <td class="cen"><?php echo $dado['ds_requisicao']?></td>
        <td class="lef"><?php echo $dado['nm_fornecedor']?></td>
        <td class="cen"><?php echo $dado['data_requisicao']?></td>
        <td class="cen"><?php echo $dado['qt_produto']?></td>
        <td class="lef"><?php echo $leit_st?></td>
        <td class="sel"><a href="#" onclick="loadRequisicao('<?php echo $dado['id_requisicao']?>')" >
		<img src="./imagens/combo.gif"></a></td>
    </tr>
<?php } } else { ?>
<tr class="erro">
    <td colspan="6">Nenhuma Requisição Encontrada</td>
</tr>
<?php }
connection::close();
?>
</table>
