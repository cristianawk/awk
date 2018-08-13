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
 *  Se perfil for de Unidade, coloca a condi��o de que enxrgue s� os dados da sua unidade
 */
if($_POST['p'] == 500){
	$condicoes[] = " a.id_unidade = ".$_POST['id_unidade'];
}

/*
 *  Se selecionar o fornecedor
 */
if($_POST['id_fornecedor']){ $condicoes[] = " f.id_fornecedor = ".$_POST['id_fornecedor']." "; }
if($condicoes != "") $where = " WHERE ".implode(" AND ", $condicoes);

$requisicoes = null;
//Classe de conex�o com banco de dados
$conn = connection::init();


$sql = "select distinct r.ds_requisicao,nr_requisicao,to_char(dt_requisicao,'DD/MM/YYYY')as data,hr_requisicao
	from fornecedores f
	inner join empenhos e on (f.id_fornecedor = e.id_fornecedor)
	inner join requisicoes r on (e.id_empenho = r.id_empenho)
	inner join items_recebidos ir on (r.id_requisicao = ir.id_requisicao)
	inner join items_contratados ic on (e.id_empenho = ic.id_empenho)
	$where
	and ir.qt_produto_recebido=ic.qt_item_contratado
	order by r.ds_requisicao";

//echo $sql; exit;
$conn->query($sql);
$requisicoes = $conn->get_tupla();

?>
<br><hr><br>
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="10">REQUISI��ES COM A ENTREGA DIFERENTE DO CONTRATO</th></th></tr>
<tr class="cab">
	<th>DESCRI��O</th>
	<th>N� REQUISI��O</th>
	<th>DATA</th>
	<th>HORA</th>
</tr>
<?php if($requisicoes){
	foreach($requisicoes AS $requisicao){ ?>
	<tr class="lin">
		<td class="cen"><?php echo $requisicao['ds_requisicao']?></td>
		<td class="cen"><?php echo $requisicao['nr_requisicao']?></td>
		<td><?php echo $requisicao['data']?></td>
		<td class="cen"><?php echo $requisicao['hr_requisicao']?></td>
	</tr>
<?php }
 } else { ?>
 <tr><th colspan="6" class="erro">Nenhuma Requisi��o foi Encontrado</th></tr>
<?php } ?>
</table>

