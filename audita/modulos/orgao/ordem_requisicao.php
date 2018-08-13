<?php
$sessao_unidade = '1';

$conn->query("SELECT DISTINCT a.id_fornecedor, a.nm_fornecedor, a.ds_cnpj
				FROM fornecedores AS a JOIN empenhos AS b ON (a.id_fornecedor=b.id_fornecedor)
				WHERE b.id_unidade = {$sessao_unidade}
			");
$fornecedores = $conn->get_tupla();
//echo "<br>aqui: ".$sessao_nome_usuario."<br>";
?>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_ordem" id="frm_ordem" onsubmit="" onreset="Limpar()" action="./index.php?acesso=<?php echo $_GET['acesso']?>&rotina=<?php echo $_GET['rotina']?>">
<input type="hidden" name="id_unidade" id="id_unidade" value="<?php echo $sessao_unidade?>">
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="2"><?php echo $modulo_arquivo['nm_rotina']?></th></tr>
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
<tr><td>Data Inicial</td><td><input type="text" name="dt_inicial" id="dt_inicial" value="" size="10" maxlength="10"> <a name="btn" onclick="javascript:displayCalendar($('dt_inicial'),'dd/mm/yyyy',this);"><img src="imagens/iconCalendar.gif" align="top"></img></a></td></tr> 
<tr><td>Data Final</td><td><input type="text" name="dt_final" id="dt_final" value="" size="10" maxlength="10"> <a name="btn" onclick="javascript:displayCalendar($('dt_final'),'dd/mm/yyyy',this);"><img src="imagens/iconCalendar.gif" align="top"></img></a></td>
</tr>
</table>
<hr>
<p><input type="button" name="btn_incluir" id="btn_incluir" class="botao" Value="Pesquisar" onclick="loadOrdemReq()">
    &nbsp;<input type="reset" name="btn_limpar" class="botao" Value="Limpar"></p>
<div id="lista_requisicao">&nbsp;</div>
</form>
<script type="text/javascript">
/**
 * Funcao que carrega a lista de requisicoes
 */
function loadOrdemReq(){

	new Ajax.Request('./modulos/orgao/lista_ordem_requisicao.php', {
			method: 'post',
			parameters: $('frm_ordem').serialize(),
			onLoading : $('lista_requisicao').innerHTML =  "<p align='center'><img src='./imagens/loader2.gif' width='21' height='21'/></p>",
			onComplete: function(transport){
				//alert(transport.responseText); exit;
				$('lista_requisicao').innerHTML = transport.responseText;
			}
		});
}



function loadRequisicao(id_requisicao){
	  
    //alert(globalWin);
    if(globalWin != ""){ globalWin.destroy(); }
		globalWin = new Window("req", {
        className: "alphacube",
        width:largura,
        height:altura,
        title:"Ordem Requisição",
        url: "./modulos/orgao/nova_ordem_requisicao.php?id_requisicao="+id_requisicao+"&unidade=<?php echo $sessao_unidade?>&usuario=<?php echo $sessao_usuario?>&nm_usuario=<?php echo $sessao_nome_usuario?>&nm_unidade=<?php echo $sessao_nome_unidade?>",
        showEffectOptions: {duration:0.1},
        //destroyOnClose: true,
        minimizable: false
    });
    //globalWin.setDestroyOnClose();
    globalWin.showCenter(true);

}

function Limpar(){
    $('lista_requisicao').innerHTML = "&nbsp;";
}

</script>
