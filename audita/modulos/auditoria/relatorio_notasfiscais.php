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

/*
 *  Se selecionar o fornecedor
 */
if($_POST['id_fornecedor']){ $condicoes[] = " c.id_fornecedor = ".$_POST['id_fornecedor']." "; }
if($condicoes != "") $where = " WHERE ".implode(" AND ", $condicoes);

$requisicoes = null;
//Classe de conexão com banco de dados
$conn = connection::init();
$sql = "SELECT b.nr_notafiscal, to_char(b.dt_notafiscal, 'DD/MM/YYYY') AS dt_notafiscal, c.nm_fornecedor, b.vl_notafiscal
		FROM notas_fiscais AS b
		JOIN fornecedores AS c ON (c.id_fornecedor=b.id_fornecedor)
		$where
		ORDER BY nm_fornecedor, dt_notafiscal";
//echo $sql; exit;
$conn->query($sql);
$requisicoes = $conn->get_tupla();

?>
<br><hr><br>
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="10">NOTAS FISCAIS POR EMPRESA</th></th></tr>
<tr class="cab">
	<th>NOTA FISCAL</th>
	<th>DATA NOTA FISCAL</th>
	<th>FORNECEDOR</th>
	<th>VALOR</th>
</tr>
<?php if($requisicoes){
	foreach($requisicoes AS $requisicao){ ?>
	<tr class="lin">
		<td class="cen"><?php echo $requisicao['nr_notafiscal']?></td>
		<td class="cen"><?php echo $requisicao['dt_notafiscal']?></td>
		<td><?php echo $requisicao['nm_fornecedor']?></td>
		<td class="cen"><?php echo $requisicao['vl_notafiscal']?></td>
	</tr>
<?php }
 } else { ?>
 <tr><th colspan="6" class="erro">Nenhuma Nota Fiscal Encontrado</th></tr>
<?php } ?>
</table>

