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
//echo "<pre>"; print_r($_GET); echo "</pre>"; exit;
$dado = null;
$itens = null;

$conn = connection::init();

$sql="select p.nm_produto,to_char(ic.dt_vigencia,'DD/MM/YYYY') AS data, vl_item_contratado
	from empenhos e
	inner join items_contratados ic on (e.id_empenho = ic.id_empenho)
	inner join produtos p on (ic.id_produto = p.id_produto)
	where e.id_empenho = ".$_GET['id_empenho'];

//echo $sql; exit;
$conn->query($sql);
$itens = $conn->get_tupla();
//echo "<pre>"; print_r($itens); echo "</pre>"; exit;

connection::close();
?>
<link href="../../css/audita.css" rel="stylesheet" type="text/css" />
<table border="0" width="60%" align="center" class="orgTable">
<tr class="cab">
	<th>Produto</th>
	<th>data</th>
	<th>Valor</th>
</tr>
<?php
 $total=null;
 foreach($itens AS $item){
 $total = $total + $item['vl_item_contratado'];
?>
	<tr class="lin">
		<td align = left><?php echo $item['nm_produto']?></td>
		<td align = center><?php echo $item['data']?></td>
		<td align = right><?php echo $item['vl_item_contratado']?></td>
	</tr>
<?php } ?>
	<tr class="lin">
		<td align = left></td>
		<td align = center></td>
		<td align = right><?php echo 'Total: '.number_format($total,2,",",".")?></td>
	</tr>
</table>
<hr>
<p align="center">
	<input type="button" name="btn_fechar" class="botao" Value="Fechar" onclick="javascript:fechar_janela()"></p>


<script type="text/javascript">
function fechar_janela(){
   parent.globalWin.hide();
}

</script>
