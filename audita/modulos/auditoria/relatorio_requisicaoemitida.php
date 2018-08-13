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
	$condicoes[] = " a.id_unidade IN (".implode(',', $_POST['id_unidade']).")";
}
/*
 *  Se selecionar o fornecedor
 */
if($_POST['id_fornecedor']){ $condicoes[] = " c.id_fornecedor = ".$_POST['id_fornecedor']." "; }
if($_POST['ds_requisicao']){ $condicoes[] = " a.ds_requisicao = '".$_POST['ds_requisicao']."' "; }
if($condicoes != "") $where = " WHERE ".implode(" AND ", $condicoes);

$requisicoes = null;
//Classe de conexão com banco de dados
$conn = connection::init();
$sql = "SELECT a.ds_requisicao, to_char(a.dt_requisicao, 'DD/MM/YYYY') AS dt_requisicao, a.nr_itens_requisicao,
		b.nr_notafiscal, to_char(b.dt_notafiscal, 'DD/MM/YYYY') AS dt_notafiscal, c.nm_fornecedor, b.vl_notafiscal
		FROM requisicoes AS a
		LEFT JOIN notas_fiscais AS b ON (a.id_requisicao=b.id_requisicao)
		LEFT JOIN fornecedores AS c ON (c.id_fornecedor=b.id_fornecedor)
		$where
		ORDER BY nm_fornecedor, a.dt_requisicao, ds_requisicao";
//echo $sql; exit;
$conn->query($sql);
$requisicoes = $conn->get_tupla();

?>
<br><hr><br>
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="10">REQUISIÇÕES EMITIDAS</th></th></tr>
<tr class="cab">
	<th width="">REQUISIÇÃO</th>
	<th width="">DATA REQUISIÇÃO</th>
	<th width="">Nº DE ITENS</th>
	<th>NOTA FISCAL</th>
	<th>DATA NOTA FISCAL</th>
	<th>FORNECEDOR</th>
	<th>VALOR</th>
</tr>
<? if($requisicoes){
	foreach($requisicoes AS $requisicao){ ?>
	<tr class="lin">
		<td class="cen"><?php echo $requisicao['ds_requisicao']?></td>
		<td><?php echo $requisicao['dt_requisicao']?></td>
		<td class="cen"><?php echo $requisicao['nr_itens_requisicao']?></td>
		<td><?php echo $requisicao['nr_notafiscal']?></td>
		<td><?php echo $requisicao['dt_notafiscal']?></td>
		<td class="cen"><?php echo $requisicao['nm_fornecedor']?></td>
		<td class="cen"><?php echo $requisicao['vl_notafiscal']?></td>
	</tr>
<?php }
 } else { ?>
 <tr><th colspan="6" class="erro">Nenhuma Requisição Encontrado</th></tr>
<?php } ?>
</table>

