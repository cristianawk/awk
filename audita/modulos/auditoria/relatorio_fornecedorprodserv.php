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
	$condicoes[] = " e.id_unidade IN (".implode(',', $_POST['id_unidade']).")";
}
if(@$_POST['id_produto']){
	$condicoes[] = " p.id_produto = ".$_POST['id_produto'];
}

if($condicoes != "") $where = " WHERE ".implode(" AND ", $condicoes);

$requisicoes = null;
//Classe de conexão com banco de dados
$conn = connection::init();


$sql = "select  distinct ds_cnpj, nm_fornecedor
	from produtos p
	inner join items_contratados ic on (p.id_produto = ic.id_produto)
	inner join empenhos e on (ic.id_empenho = e.id_empenho)
	inner join fornecedores f on (e.id_fornecedor = f.id_fornecedor)
	$where";

//echo $sql; exit;
$conn->query($sql);
$requisicoes = $conn->get_tupla();

?>
<br><hr><br>
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="10">Fornecedores por Produto/Servi&ccedil;o</th></th></tr>
<tr class="cab">
	<th>CNPJ</th>
	<th>Nome Fornecedor</th>
</tr>
<?php if($requisicoes){
	foreach($requisicoes AS $requisicao){ ?>
	<tr class="lin">
		<td class="cen"><?php echo $requisicao['ds_cnpj']?></td>
		<td class="cen"><?php echo $requisicao['nm_fornecedor']?></td>
	</tr>
<?php }
 } else { ?>
 <tr><th colspan="6" class="erro">Nenhum Fornecedor foi Encontrado</th></tr>
<?php } ?>
</table>

