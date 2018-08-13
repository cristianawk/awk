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


$sql = "select nr_contrato,ch_contrato,ds_contrato,to_char(dt_contrato,'DD/MM/YYYY') AS data
	from empenhos e
	$where";


//echo $sql; exit;
$conn->query($sql);
$empenhos = $conn->get_tupla();

?>
<br><hr><br>
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="10">EMPENHOS</th></th></tr>
<tr class="cab">
	<th>N° Contrato</th>
	<th>Contrato</th>
	<th>Documento</th>
	<th>DATA</th>
</tr>
<?php if($empenhos){
	foreach($empenhos AS $empenho){ ?>
	<tr class="lin">
		<td class="cen"><?php echo $empenho['nr_contrato']?></td>
		<td ><?php echo $empenho['ch_contrato']?></td>
		<td><A HREF="./contratos/<?php echo $empenho['ds_contrato']?>"><?php echo $empenho['ds_contrato']?></A></td>
		<td class="cen"><?php echo $empenho['data']?></td>
	</tr>
<?php }
 } else { ?>
 <tr><th colspan="6" class="erro">Nenhum Contrato foi Encontrado</th></tr>
<?php } ?>
</table>

