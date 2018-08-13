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
//echo 'Relatorio Requisicao de Fornecimento não Atendida';
$where = null;
$condicoes = null;

if(@$_POST['id_unidade'][0] != ""){
	$condicoes[] = " e.id_unidade IN (".implode(',', $_POST['id_unidade']).")";
}

/*
 *  Se selecionar o fornecedor
 */
if($_POST['id_fornecedor']){ $condicoes[] = " f.id_fornecedor = ".$_POST['id_fornecedor']." "; }

$where = null;
if($condicoes != ""){
   $where .= " ".implode(" AND ", $condicoes)." ";
}
if (($_POST['dt_inicial'] != '')and($_POST['dt_final'] != '')){
	if(is_null($where)) $D = ""; else $D = " AND";
   	$where .= "$D r.dt_requisicao between ".formata::formataValor('dt_', $_POST['dt_inicial'])." and ".formata::formataValor('dt_', $_POST['dt_final'])." ";
}
$fornecedores = null;
//Classe de conexão com banco de dados
$conn = connection::init();

if(!is_null($where)) $where = "WHERE ".$where;

$sql = "select DISTINCT nr_contrato,r.dt_requisicao as data,nr_requisicao,id_item_contratado,qt_produto_recebido,id_item_recebido,
   CASE WHEN (id_item_contratado > qt_produto_recebido) THEN 'Recebido a menos: '||id_item_contratado - qt_produto_recebido
        WHEN (id_item_contratado < qt_produto_recebido) THEN 'Recebido a mais: '||qt_produto_recebido - id_item_contratado
        ELSE 'OK'
   END as recebimento
from items_recebidos ir 
left join requisicoes r on (ir.id_requisicao = r.id_requisicao)
left join empenhos e on (r.id_empenho = e.id_empenho)
inner join fornecedores f on (e.id_fornecedor = e.id_fornecedor)
$where
order by data ASC";
/*
$sql = "SELECT nm_fornecedor, ds_cnpj, nm_responsavel, SUM(vl_empenho) AS total 
		FROM fornecedores AS a JOIN empenhos AS b USING(id_fornecedor)
		$where
		GROUP BY nm_fornecedor, ds_cnpj, nm_responsavel
		ORDER BY total DESC, nm_fornecedor ASC";
*/
//echo $sql; exit;
$conn->query($sql);
$fornecedores = $conn->get_tupla();

?>
<br><hr><br>
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="10">Requisição de Fornecimento não Atendida</th></th></tr>
<tr class="cab">
	<th width="">N° Contrato</th>
	<th width="">Data</th>
	<th width="">N° Requisição</th>
	<th width="">Item Contratado</th>
	<th width="">Item Recebido</th>	
	<th>Recebimento</th>
</tr>
<?php if($fornecedores){
	foreach($fornecedores AS $fornecedor){ ?>
	<tr class="lin">
		<td align="center" class=""><?php echo $fornecedor['nr_contrato']?></td>
		<td align="center"><?php echo $fornecedor['data']?></td>
		<td align="center" class=""><?php echo $fornecedor['id_item_contratado']?></td>
		<td align="center"><?php echo $fornecedor['id_item_contratado']?></td>
		<td align="center" class=""><?php echo $fornecedor['qt_produto_recebido']?></td>
		<td><?php echo $fornecedor['recebimento']?></td>
	</tr>
<?php }
 } else { ?>
 <tr><th colspan="6" class="erro">Nenhuma Registro Encontrado</th></tr>
<?php } ?>
</table>

