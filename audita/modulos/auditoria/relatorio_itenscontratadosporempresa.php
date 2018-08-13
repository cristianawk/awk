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
    $where .= " AND f.id_fornecedor =".$_POST['id_fornecedor']." ";
}

	$sql = "select distinct e.id_empenho,e.ds_empenho,p.nm_produto,to_char(e.dt_empenho, 'DD/MM/YYYY') AS data
		from fornecedores f
		inner join empenhos e on (f.id_fornecedor = e.id_fornecedor)
		inner join requisicoes r on (e.id_empenho = r.id_empenho)
		inner join items_contratados ic on (e.id_empenho = ic.id_empenho)
		inner join produtos p on (ic.id_produto = p.id_produto)
		$where
		order by e.ds_empenho,p.nm_produto";

		//echo $sql; exit;
        	$conn = connection::init();
		$conn->query($sql);
		$dados = $conn->get_tupla();
        connection::close();

//echo "<pre>"; print_r($dados); echo "</pre>"; exit;
?>
<hr>
<table border="0" width="80%" align="center" class="orgTable">
<tr class="cab">
	<th width="15%">Empenho</th>
	<th width="35%">Nome Produto</th>
	<th width="15%">Data</th>
	<th width="3%">&nbsp;</th>
</tr>
<?php if($dados){
    foreach($dados AS $dado){ ?>
    <tr class="lin">
        <td class="cen"><?php echo $dado['ds_empenho']?></td>
        <td class="lef"><?php echo $dado['nm_produto']?></td>
        <td class="cen"><?php echo $dado['data']?></td>
        <td class="sel"><a href="#" onclick="loadEmpenhos('<?php echo $dado['id_empenho']?>','<?php echo $dado['ds_empenho']?>')" ><img src="./imagens/combo.gif"></a></td>
    </tr>
<?php } } else { ?>
<tr class="erro">
    <td colspan="6">Nenhuma Requisição Encontrada</td>
</tr>
<?php } ?>
</table>
