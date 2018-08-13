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
//echo "<pre>"; print_r($_POST); echo "</pre>"; //exit;
$conn = connection::init();
$conn->query("SELECT a.id_empenho, a.ds_empenho,
			b.id_requisicao, b.nr_requisicao, b.ds_requisicao, b.dt_requisicao,
			to_char(b.dt_requisicao, 'DD/MM/YYYY') AS data_requisicao,
			c.id_fornecedor, c.nm_fornecedor
			FROM empenhos AS a JOIN requisicoes AS b USING (id_empenho)
			JOIN fornecedores AS c USING (id_fornecedor)
			WHERE a.id_unidade = ".$_POST['id_unidade']." AND id_fornecedor =".$_POST['id_fornecedor']."
            ORDER BY dt_requisicao, ds_requisicao");
//echo "<br> sql: ".$conn;
while($tupla = $conn->fetch_row()) $empenhos[] = $tupla;
connection::close();
//echo "<pre>"; print_r($empenhos); echo "</pre>"; exit;
?>
<hr>
<table border="0" width="60%" align="center" id="tabLista">
<tr><th>Requisi&ccedil;&atilde;o</th><th colspan="2">Data Requisi&ccedil;&atilde;o</th></tr>
<?php foreach($empenhos AS $empenho){ ?>
<tr>
	<td><?php echo $empenho['ds_requisicao']?></td>
	<td><?php echo $empenho['data_requisicao']?></td>
	<th width="3%"><a href="#" onclick="javascript:novaNota('<?php echo $empenho['id_requisicao']?>', '<?php echo $empenho['id_empenho']?>')" title="Nota fiscal do fornecedor <?php echo $empenho['nm_fornecedor']?> da requisi&ccedil;&atilde;o <?php echo $empenho['ds_requisicao']?>"><img src="./imagens/combo.gif"></a></th>
</tr>
<?php }return false; ?>
</table>
