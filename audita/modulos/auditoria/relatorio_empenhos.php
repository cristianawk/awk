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
if(@$_POST['id_empenho']){
 $condicoes[] = "id_empenho = ".$_POST['id_empenho'];	
}
if($condicoes != "") $where = " WHERE ".implode(" AND ", $condicoes);

$requisicoes = null;

//Classe de conexão com banco de dados
$conn = connection::init();


$sql = "select f.ds_cnpj,nm_fornecedor,e.dt_empenho, vl_empenho, nm_unidade
	from empenhos e
	inner join fornecedores f on (e.id_fornecedor = f.id_fornecedor)
	left join unidades_beneficiadas ub on (e.id_unidade = ub.id_unidade)
	$where";


//echo $sql; exit;
$conn->query($sql);
$empenhos = $conn->get_tupla();

?>
<br><hr><br>
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="10">EMPENHOS</th></th></tr>
<tr class="cab">
	<th>CNPJ</th>
	<th>Fornecedor</th>
	<th>DATA</th>
	<th>Valor</th>
	<th>Unidade Gestora</th>
</tr>
<?php if($empenhos){
	foreach($empenhos AS $empenho){ ?>
	<tr class="lin">
		<td class="cen"><?php echo $empenho['ds_cnpj']?></td>
		<td class="cen"><?php echo $empenho['nm_fornecedor']?></td>
		<td><?php echo $empenho['dt_empenho']?></td>
		<td class="cen"><?php echo $empenho['vl_empenho']?></td>
		<td class="cen"><?php echo $empenho['nm_unidade']?></td>
	</tr>
<?php }
 } else { ?>
 <tr><th colspan="6" class="erro">Nenhum Empenho foi Encontrado</th></tr>
<?php } ?>
</table>

