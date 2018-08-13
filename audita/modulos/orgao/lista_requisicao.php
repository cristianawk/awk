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
//echo "<pre>"; print_r($_POST); echo "</pre>"; //exit;
$empenhos = null;

if($_POST){
	$sql = "SELECT a.id_empenho, a.ds_empenho, b.id_requisicao, b.nr_requisicao, b.ds_requisicao
			FROM empenhos AS a LEFT JOIN requisicoes AS b USING (id_empenho)
			WHERE a.id_unidade = ".$_POST['unidade']." AND id_fornecedor =".$_POST['fornecedor']." ORDER BY ds_requisicao";
	//echo $sql; exit;
	//Classe de conexao com banco de dados
	$conn = connection::init();
	$conn->query($sql);
	while($tupla = $conn->fetch_row()) $empenhos[$tupla['ds_empenho']][] = $tupla;
	//echo "<pre>"; print_r($empenhos); echo "</pre>"; exit;

	connection::close();
}

if($empenhos){ ?>
<hr>
<table border="0" width="60%" align="center" id="tabLista">
<tr><th>Empenho</th><th>Requisi&ccedil;&atilde;o</th><th>Env</th><th>Vis</th></tr>
<?php foreach($empenhos AS $ds_empenho => $empenho){ ?>
<tr>
	<td id='hlist'><?php echo $ds_empenho?></td>
	<?php $n = 1;
		foreach($empenho AS $emp){
			if($emp['ds_requisicao'] != "") $nr_requisicao = $emp['ds_requisicao']; else $nr_requisicao = "&nbsp;";
			if($n == 1){
				if($emp['ds_requisicao'] != ""){
					echo "<td>{$nr_requisicao}</td>
					      <td style='cursor:pointer;' onclick='loadRequisicao(\"".$emp['id_requisicao']."\")' >
						  <img src='./imagens/070.ico' height='12' width='12' title='Ordem da Requisi&ccedil;&atilde;o {$nr_requisicao}'/></td>
					      <td style='cursor:pointer;' onclick='editReq(\"".$emp['id_requisicao']."\", \"{$ds_empenho}\")'>
						  <img src='./imagens/070o.ico' height='12' width='12' title='Visualizar Requisi&ccedil;&atilde;o {$nr_requisicao}'/></td>
					    </tr>\n";
				}else{
					echo "<td colspan='6'>{$nr_requisicao}</td></tr>\n";
				}
				$n = 0;
			} else {
				echo "<tr><td>&nbsp;</td><td>{$nr_requisicao}</td><td style='cursor:pointer;' onclick='loadRequisicao(\"".$emp['id_requisicao']."\")' >
					      <img src='./imagens/070.ico' height='12' width='12' title='Ordem da Requisi&ccedil;&atilde;o {$nr_requisicao}'/></td>
					 <td style='cursor:pointer;' onclick='editReq(\"".$emp['id_requisicao']."\", \"{$ds_empenho}\")'>
					      <img src='./imagens/070o.ico' height='12' width='12' title='Visualizar Requisi&ccedil;&atilde;o {$nr_requisicao}'/></td></tr>\n";
			}
		} ?>
<tr><td colspan="6"><a onclick="javascript:novaReq('<?=$ds_empenho?>')" href="#" title="Nova Requisi&ccedil;&atilde;o para o Empenho <?php echo $ds_empenho?>">Nova Requisi&ccedil;&atilde;o</a></td></tr>
<?php } ?>
</table>
<?php } ?>
