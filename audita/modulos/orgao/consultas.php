<?php
$consultas = array(
		//'relatorio_notasduplicata.php' => 'NOTAS FISCAIS EM DUPLICATA',
		'relatorio_historicovalor.php' => 'HISTORICO DE ALTERA��O DE VALOR'
	);

$conn->query("SELECT DISTINCT a.id_fornecedor, a.nm_fornecedor, a.ds_cnpj
				FROM fornecedores AS a JOIN empenhos AS b ON (a.id_fornecedor=b.id_fornecedor)
				ORDER BY nm_fornecedor");
$fornecedores = $conn->get_tupla();
?>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_consulta" id="frm_consulta" onsubmit="" onreset="Limpar(); return true;" action="./index.php?acesso=<?php echo $_GET['acesso']?>&rotina=<?php echo $_GET['rotina']?>">
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="2"><?php echo $modulo_arquivo['nm_rotina']?></th></tr>
<tr>
	<td>Tipo de Relatório</td>
	<td>
		<select id="consulta" name="consulta" class="validate-selection">
		<option value="">-------------------------------------------------------------------</option>
		<?php  foreach($consultas AS $link => $nome){ ?>
		<option value="<?php echo $link?>"><?php echo $nome?></option>
		<?php } ?>
		</select>
	</td>
</tr>
<tr>
	<td>Nome do Fornecedor</td>
	<td>
		<select id="id_fornecedor" name="id_fornecedor" class="validate-selection">
		<option value="">-------------------------------------------------------------------</option>
		<?php foreach($fornecedores AS $fornecedor){ ?>
		<option value="<?php echo $fornecedor['id_fornecedor']?>"><?php echo $fornecedor['ds_cnpj']?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $fornecedor['nm_fornecedor']?></option>
		<?php } ?>
		</select>
	</td>
</tr>
<!--<tr><td>Data Inicial</td><td><input type="text" name="dt_inicial" id="dt_inicial" value="" size="10" maxlength="10"> <a name="btn" onclick="javascript:displayCalendar($('dt_inicial'),'dd/mm/yyyy',this);"><img src="imagens/iconCalendar.gif" align="top"></img></a></td></tr>
<tr><td>Data Final</td><td><input type="text" name="dt_final" id="dt_final" value="" size="10" maxlength="10"> <a name="btn" onclick="javascript:displayCalendar($('dt_final'),'dd/mm/yyyy',this);"><img src="imagens/iconCalendar.gif" align="top"></img></a></td></tr>-->
</table>
<hr>
<p><input type="button" name="btn_incluir" id="btn_incluir" class="botao" Value="Pesquisar" onclick="loadTipoCons()">&nbsp;<input type="reset" name="btn_limpar" class="botao" Value="Limpar"></p>
<div id="lista">&nbsp;</div>
</form>
<script type="text/javascript">
/**
 * Funcaoo que carrega a lista de requisicoes
 */
function loadTipoCons(){

	if($('consulta').value != ""){
		var arq = $('consulta').value;

		new Ajax.Request('./modulos/auditoria/'+arq, {
			method: 'post',
			parameters: $('frm_consulta').serialize(),
			onLoading : $('lista').innerHTML =  "<p align='center'><img src='./imagens/loader2.gif' width='21' height='21'/></p>",
			onComplete: function(transport){
				//alert(transport.responseText); exit;
				$('lista').innerHTML = transport.responseText;
			}
		});
	}
}

function Limpar(){
	$('lista').innerHTML = '&nbsp;';
}
</script>
