<?php

$empenhos = null;
$dados_empenho = null;
$sessao_unidade = '1';

$conn->query("SELECT DISTINCT a.id_fornecedor, a.nm_fornecedor, a.ds_cnpj
				FROM fornecedores AS a JOIN empenhos AS b ON (a.id_fornecedor=b.id_fornecedor)
				WHERE b.id_unidade = {$sessao_unidade}");
$fornecedores = $conn->get_tupla();
//echo "aqui<pre>"; print_r($fornecedores); echo "</pre>"; exit;

?>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_requisicao" id="frm_requisicao" onsubmit="" onreset="Limpar(); return true;" action="./index.php?acesso=<?php echo $_GET['acesso']?>&rotina=<?php echo $_GET['rotina']?>">
<input type="hidden" name="ds_empenho" id="ds_empenho" value="">
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
</table>
<hr>
<p><input type="button" name="btn_incluir" id="btn_incluir" class="botao" Value="Pesquisar" onclick="loadEmpReq()">&nbsp;
    <input type="reset" name="btn_limpar" class="botao" Value="Limpar"></p>
<div id="lista_requisicao">&nbsp;</div>
</form>
<script type="text/javascript">
function loadEmpReq(){

	if($('id_fornecedor').value != ""){
		new Ajax.Request('./modulos/orgao/lista_requisicao.php', {
			method: 'post',
			parameters: { 'fornecedor' : $('id_fornecedor').value, 'unidade' : '<?php echo $sessao_unidade?>' },
			onLoading : $('lista_requisicao').innerHTML =  "<p align='center'><img src='./imagens/loader2.gif' width='21' height='21'/></p>",
			onComplete: function(transport){
				//alert(transport.responseText); exit;
				$('lista_requisicao').innerHTML = transport.responseText;
			}
		});
	}
}


function novaReq(ds_empenho){

	//alert(globalWin);
    if(globalWin != ""){ globalWin.destroy(); }
		globalWin = new Window("req", {
        className: "alphacube",
        width:largura,
        height:altura,
        title:"Nova Requisi&ccedil;&atilde;o",
        url: "./modulos/orgao/nova_requisicao.php?empenho="+ds_empenho+"&nm_unidade=<?php echo $sessao_unidade?>&larg="+largura,
        showEffectOptions: {duration:0.1},
        //destroyOnClose: true,
        minimizable: false
    });
    //globalWin.setDestroyOnClose();
    globalWin.showCenter(true);

}

function editReq(id_requisicao, ds_empenho){

	//alert(globalWin);
    if(globalWin != ""){ globalWin.destroy(); }
		globalWin = new Window("req", {
        className: "alphacube",
        width:largura,
        height:altura,
        title:"Editar Requisi&ccedil;&atilde;o",
        url: "./modulos/orgao/editar_requisicao.php?empenho="+ds_empenho+"&requisicao="+id_requisicao+"&nm_unidade=<?php echo $sessao_unidade?>&larg="+largura,
        showEffectOptions: {duration:0.1},
        //destroyOnClose: true,
        minimizable: false
    });
    //globalWin.setDestroyOnClose();
    globalWin.showCenter(true);

}

function loadRequisicao(id_requisicao){//alert('aqui');
	  
    //alert(globalWin);
    if(globalWin != ""){ globalWin.destroy(); }
		globalWin = new Window("req", {
        className: "alphacube",
        width:largura,
        height:altura,
        title:"Ordem Requisi&ccedil;&atilde;o",
   url: "./modulos/orgao/nova_ordem_requisicao.php?id_requisicao="+id_requisicao+"&nm_unidade=<?php echo $sessao_nome_unidade?>&nm_usuario=<?php echo $sessao_nome_usuario?>",
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
