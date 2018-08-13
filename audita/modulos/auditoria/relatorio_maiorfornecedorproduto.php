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
$where = null;
$condicoes = null;

if(@$_POST['id_unidade'][0] != ""){
	$condicoes[] = " b.id_unidade IN (".implode(',', $_POST['id_unidade']).")";
}

/*
 *  Se selecionar o fornecedor
 */
if($_POST['id_fornecedor']){ $condicoes[] = " id_fornecedor = ".$_POST['id_fornecedor']." "; }
if($condicoes != "") $where = " WHERE ".implode(" AND ", $condicoes);

$fornecedores = null;
//Classe de conexão com banco de dados
$conn = connection::init();
$sql = "SELECT nm_fornecedor, ds_cnpj, nm_produto, SUM(c.vl_item_contratado) AS total
		FROM fornecedores AS a LEFT JOIN empenhos AS b ON(a.id_fornecedor = b.id_fornecedor)
		JOIN items_contratados AS c ON (b.id_empenho = c.id_empenho)
		LEFT JOIN  produtos AS d USING(id_produto)
		$where
		GROUP BY nm_fornecedor, ds_cnpj, nm_produto
		ORDER BY total DESC";
//echo $sql; exit;
$conn->query($sql);
$fornecedores = $conn->get_tupla();

?>
<br><hr><br>
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="10">MAIORES FORNECEDORES POR PRODUTO</th></th></tr>
<tr class="cab">
	<th width="">NOME</th>
	<th width="">CNPJ</th>
	<th width="">PRODUTO/SERVICO</th>
	<th>VALOR ENVOLVIDO</th>
</tr>
<?php if($fornecedores){
	foreach($fornecedores AS $fornecedor){ ?>
	<tr class="lin">
		<td class=""><?php echo $fornecedor['nm_fornecedor']?></td>
		<td><?php echo $fornecedor['ds_cnpj']?></td>
		<td class=""><?php echo $fornecedor['nm_produto']?></td>
		<th><?php echo str_replace(".", ",", $fornecedor['total'])?></th>
	</tr>
<?php }
 } else { ?>
 <tr><th colspan="6" class="erro">Nenhuma Registro Encontrado</th></tr>
<?php } ?>
</table>

