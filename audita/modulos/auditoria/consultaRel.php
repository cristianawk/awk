<?php
$where = null;
/*
 *  Se perfil for de Unidade, coloca a condição de que enxrgue só os dados da sua unidade
 */
if($sessao_perfil == 500){
	$where = " WHERE b.id_unidade = ".$sessao_unidade;
}


/*
 * Tipos de relatorios existentes
 */
$consultas = array(
		'rel_unidades_beneficiarias.php'	=> 'UNIDADES BENEFICIÁRIAS',
		'rel_fornecedores.php' 			=> 'FORNECEDORES',
		'rel_produto_servico.php' 		=> 'PRODUTOS E SERVIÇOS',
		'rel_unidade_medida.php' 		=> 'RELAÇÃO DAS UNIDADE DE MEDIDA',
		'rel_empenhos.php' 			=> 'RELAÇÃO DOS EMPENHOS',
		'rel_itens_contratados.php'		=> 'ITENS CONTRATADOS',
		'rel_requisicoes_forneceimento.php'	=> 'REQUISIÇÕES DE FORNECIMENTO',
		'rel_notas_fiscais.php'			=> 'NOTAS FISCAIS',
		'rel8_.php'			=> 'NOTAS FISCAIS LIBERADAS POR PAGAMENTO'
	);

//echo "<pre>"; print_r($consultas); echo "</pre>";exit;

?>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_consulta" id="frm_consulta" onsubmit="" onreset="Limpar(); return true;" action="./index.php?acesso=<?php echo $_GET['acesso']?>&rotina=<?php echo $_GET['rotina']?>">
<input type="hidden" name="id_unidade" id="id_unidade" value="<?php echo $sessao_unidade?>">
<input type="hidden" name="order_by" id="order_by" value="">
<input type="hidden" name="ord" id="ord" value="ASC">

<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="2"><?php echo $modulo_arquivo['nm_rotina']?></th></tr>
<tr>
	<td ALIGN=RIGHT>Tipo de Relatório:</td>
	<td>
		<select id="consulta" name="consulta" class="validate-selection" onchange="loadTipo()">
		<option value="">-------------------------------------------------------------------</option>
		<?php foreach($consultas AS $link => $nome){ ?>
		<option value="<?php echo $link?>"><?php echo $nome?></option>
		<?php } ?>
		</select>
	</td>
</tr>
</table>

<hr>
<p>
   <input type="button" name="btn_incluir" id="btn_incluir" class="botao" Value="Pesquisar" onclick="loadTipoCons()">&nbsp;
   <input type="reset" name="btn_limpar" class="botao" Value="Limpar">
</p>
<div id="lista">&nbsp;</div>
</form>

<script type="text/javascript">

/**
 * Função que carrega a lista de requisições
 */
/*
function montaRelatorio(tipo){
	$('ext').value = tipo;
	alert($('ext').value);
	$('frm_cons_geral_oco').submit();
}
*/
function montaRelatorio(tipo,col,val){
  var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "./modulos/auditoria/montaRel.php");
  var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "ext");
    hiddenField.setAttribute("value", tipo);
    form.appendChild(hiddenField);
  var hiddenFieldCol = document.createElement("input");
    hiddenFieldCol.setAttribute("type", "hidden");
    hiddenFieldCol.setAttribute("name", "colunas");
    hiddenFieldCol.setAttribute("value", col);
    form.appendChild(hiddenFieldCol);
  var hiddenFieldVal = document.createElement("input");
    hiddenFieldVal.setAttribute("type", "hidden");
    hiddenFieldVal.setAttribute("name", "valores");
    hiddenFieldVal.setAttribute("value", val);
    form.appendChild(hiddenFieldVal);
    document.body.appendChild(form);
    form.submit();
}

function ordenar(coluna, ord){
	//alert(coluna);
	$('order_by').value = coluna;
	$('ord').value = ord;
	loadTipoCons();
}

function loadTipoCons(){
//	$('lista').innerHTML = '&nbsp;';

	if($('consulta').value != ""){

		var arq = $('consulta').value;

		new Ajax.Request('./modulos/auditoria/'+arq, {
			method: 'get',
			parameters: $('frm_consulta').serialize()+"&p=<?php echo $sessao_perfil?>"+'&order_by='+$('order_by').value+'&ord='+$('ord').value,
			onLoading : $('lista').innerHTML =  "<p align='center'><img src='./imagens/loader2.gif' width='21' height='21'/></p>",
			onComplete: function(transport){
				$('lista').innerHTML = transport.responseText;
			}
		});
	} else {
		alert("Selecione o tipo de relatório.");
	}
}

function loadEmpenhos(id_empenho,ds_empenho){
	  
	if(globalWin != ""){ globalWin.destroy(); }
		globalWin = new Window("req", {
        className: "alphacube",
        width:largura,
        height:altura,
        title:"Lista de itens contratados do empenho "+ds_empenho,
        url: "./modulos/auditoria/lista_itens_contratados.php?id_empenho="+id_empenho,
        showEffectOptions: {duration:0.1},
        //destroyOnClose: true,
        minimizable: false
    });
    globalWin.showCenter(true);
}

function Limpar(){
	$('lista').innerHTML = '&nbsp;';
	$('consulta').value = "";
}

</script>
