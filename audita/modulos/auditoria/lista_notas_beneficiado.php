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
//echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
$conn = connection::init();
$conn->query("SELECT DISTINCT id_notafiscal, nr_notafiscal, to_char(dt_notafiscal, 'DD/MM/YYYY') AS dt_notafiscal
            , nm_fornecedor, id_requisicao, id_empenho
            FROM notas_fiscais AS a JOIN requisicoes AS b USING (id_requisicao)
            JOIN empenhos AS c USING (id_empenho)
            JOIN fornecedores AS d ON (a.id_fornecedor=d.id_fornecedor AND c.id_fornecedor=d.id_fornecedor)
            WHERE id_notafiscal = ".$_POST['id_notafiscal']);
while($tupla = $conn->fetch_row()) $notasfiscais[] = $tupla;
connection::close();
//echo "<pre>"; print_r($notasfiscais); echo "</pre>"; exit;
?>
<hr>
<table border="0" width="60%" align="center" id="tabLista">
<tr><th>Nota Fiscal</th><th>Fornecedor</th><th colspan="2">Data Nota Fiscal</th></tr>
<?php if($notasfiscais){ foreach($notasfiscais AS $notafiscal){ ?>
<tr>
	<td width="25%"><?php echo $notafiscal['nr_notafiscal']?></td>
	<td><?php echo $notafiscal['nm_fornecedor']?></td>
	<td width="25%"><?php echo $notafiscal['dt_notafiscal']?></td>
	<th width="3%"><a href="#" onclick="javascript:loadNotaRecebida('<?php echo $notafiscal['id_requisicao']?>', '<?php echo $notafiscal['id_empenho']?>', '<?php echo $notafiscal['nr_notafiscal']?>')" title="Nota fiscal do fornecedor <?php echo $notafiscal['nm_fornecedor']?>"><img src="./imagens/combo.gif"></a></th>
</tr>
<?php }
} else {?>
<tr><td colspan="4" class="erro">Nenhuma Nota Encontrada</td></tr>
<?php } ?>
</table>
