<?php
$where = null;

/*
 *  Se perfil for de Unidade, coloca a condiçao de que enxrgue só os dados da sua unidade
 */
/*if($sessao_perfil == 500){
	$where = " WHERE b.id_unidade = ".$sessao_unidade;
}*/
//echo 'Perfil '.$sessao_perfil.'<br>';
//echo 'Unidade '.$sessao_unidade.'<br>';
/*
 * Tipos de relatorios existentes
 */
$consultas = array(
		//'relatorio_notasduplicata.php' => 'NOTAS FISCAIS EM DUPLICATA',
		'relatorio_historicovalor.php' 					=> 'HISTORICO DE ALTERAÇÃO DE VALOR',
		'relatorio_requisicaoemitida.php' 				=> 'REQUISIÇÕES EMITIDAS',
		'relatorio_nomefornecedores.php' 				=> 'FORNECEDORES POR NOME',
		'relatorio_notasfiscais.php' 					=> 'NOTAS FISCAIS POR EMPRESA',
		'relatorio_maiorfornecedor.php' 				=> 'MAIORES FORNECEDORES',
		'relatorio_maiorfornecedorproduto.php' 			=> 'MAIORES FORNECEDORES POR PRODUTO',
		'relatorio_requisicaodefornecimentonaoatendida.php'	=> '1-REQUISIÇÕES DE FORNECIMENTO NÃO ATENDIDAS',
		'relatorio_itenscontratadosporempresa.php'		=> '6-ITENS CONTRATADOS POR EMPRESA',
		'relatorio_requisicoesporempresa.php'			=> '7-REQUISIÇÕES POR EMPRESA',
		'relatorio_fornecedorprodserv.php'				=> '9-FORNECEDORES POR PRODUTO/SERVIÇO',
		'relatorio_empenhos.php'						=> '10-EMPENHOS',
		'relatorio_apresentacontrato.php'				=> '11-APRESENTA CONTRATO',
		'relatorio_nfdevolvidasaudirotia.php'			=> '12-NOTAS FISCAIS DEVOLVIDAS PELA AUTIDOTIA'
	);


$sql = "SELECT DISTINCT a.id_fornecedor, a.nm_fornecedor, a.ds_cnpj
				FROM fornecedores AS a JOIN empenhos AS b ON (a.id_fornecedor=b.id_fornecedor)
				$where
				ORDER BY nm_fornecedor";

$conn->query($sql);
$fornecedores = $conn->get_tupla();

$sql = "SELECT DISTINCT id_produto, nm_produto FROM produtos ORDER BY nm_produto";
$conn->query($sql);
$produtos = $conn->get_tupla();

$sql = "SELECT DISTINCT id_empenho, ds_empenho, nr_contrato FROM empenhos ORDER BY ds_empenho";
$conn->query($sql);
$empenhos = $conn->get_tupla();

$sql = "SELECT DISTINCT id_unidade, nm_unidade FROM unidades_beneficiadas ORDER BY id_unidade";
$conn->query($sql);
$unidades = $conn->get_tupla();

//echo "<pre>"; print_r($unidades); echo "</pre>"; exit;

?>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_consulta" id="frm_consulta" onsubmit="" 
onreset="Limpar(); return true;" >
<!--<input type="hidden" name="id_unidade" id="id_unidade" value="<?php echo $sessao_unidade?>">-->
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="2"><?php echo $modulo_arquivo['nm_rotina']?></th></tr>
<tr>
	<td>Tipo de Relatório:</td>
	<td>
		<select id="consulta" name="consulta" class="validate-selection" onchange="loadTipo()">
		<option value="">-------------------------------------------------------------------</option>
		<?php foreach($consultas AS $link => $nome){ ?>
		<option value="<?php echo $link?>"><?php echo $nome?></option>
		<?php } ?>
		</select>
	</td>
</tr>
<tr id="celulaUnidade">
	<td>Unidade Beneficiária:</td>
	<td>
		<select id="id_unidade" name="id_unidade[]" class="validate-selection" size="10" multiple>
		<option value="">TODAS AS UNIDADES</option>
		<?php foreach($unidades AS $unidade){ ?>
		<option value="<?php echo $unidade['id_unidade']?>"><?php echo $unidade['nm_unidade']?></option>
		<?php } ?>
		</select>
	</td>
</tr>
<tr id="celulaFornecedor">
	<td>Nome Fornecedor:</td>
	<td>
		<select id="id_fornecedor" name="id_fornecedor" class="validate-selection">
		<option value="">-------------------------------------------------------------------</option>
		<?php foreach($fornecedores AS $fornecedor){ ?>
		<option value="<?php echo $fornecedor['id_fornecedor']?>"><?php echo (strlen($fornecedor['ds_cnpj'])<18?$fornecedor['ds_cnpj'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;':$fornecedor['ds_cnpj'])?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $fornecedor['nm_fornecedor']?></option>
		<?php } ?>
		</select>
	</td>
</tr>
<tr id="celulaProduto" style="display: none;">
	<td>Nome Produto:</td>
	<td>
		<select id="id_produto" name="id_produto" class="validate-selection">
		<option value="">-------------------------------------------------------------------</option>
		<?php foreach($produtos AS $produto){ ?>
		<option value="<?php echo $produto['id_produto']?>"><?php echo $produto['nm_produto']?></option>
		<?php } ?>
		</select>
	</td>
</tr>
<tr id="celulaEmpenho" style="display: none;">
	<td>Empenho:</td>
	<td>
		<select id="id_empenho" name="id_empenho" class="validate-selection">
		<option value="">-------------------------------------------------------------------</option>
		<? foreach($empenhos AS $empenho){?>
		<option value="<?=$empenho['id_empenho']?>"><?=$empenho['ds_empenho']?></option>
		<? } ?>
		</select>
	</td>
</tr>
<tr id="celulaContrato" style="display: none;">
	<td>Nº do Contrato:</td>
	<td>
		<select id="id_contrato" name="id_contrato" class="validate-selection">
		<option value="">-------------------------------------------------------------------</option>
		<?php foreach($empenhos AS $contrato){?>
		<option value="<?php echo $contrato['id_empenho']?>"><?php echo $contrato['nr_contrato']?></option>
		<?php } ?>
		</select>
	</td>
</tr>

<tr id="requisicao" style="display: none;"><td>Requisição</td><td><input type="text" name="ds_requisicao" id="ds_requisicao" value="" size='10'></td></tr>
<tr id="data1" style="display: none;"><td>Data Inicial</td><td><input type="text" name="dt_inicial" id="dt_inicial" value="" size="10" maxlength="10" > <a name="btn" onclick="javascript:displayCalendar($('dt_inicial'),'dd/mm/yyyy',this);"><img src="imagens/iconCalendar.gif" align="top"></img></a></td></tr>
<tr id="data2" style="display: none;"><td>Data Final</td><td><input type="text" name="dt_final" id="dt_final" value="" size="10" maxlength="10" > <a name="btn" onclick="javascript:displayCalendar($('dt_final'),'dd/mm/yyyy',this);"><img src="imagens/iconCalendar.gif" align="top"></img></a></td></tr>
</table>

<hr>
<p>
   <input type="button" name="btn_incluir" id="btn_incluir" class="botao" Value="Pesquisar" onclick="loadTipoCons()">&nbsp;
   <input type="reset" name="btn_limpar" class="botao" Value="Limpar">
</p>
<div id="lista">&nbsp;</div>
</form>

<script type="text/javascript">

	function montaRelatorio(tipo){
		//var ext = tipo;
		$('ext').value = tipo;
		//$('frm_consulta').action = "";
		//$('frm_consulta').action = arq;
		$('frm_cons_geral_lib').submit();
	}
	
/**
 * Funcao que carrega a lista de requisicoes
 */
function loadTipoCons(){
	$('lista').innerHTML = '&nbsp;';

	if($('consulta').value != ""){

		var arq = $('consulta').value;
		//alert('aqui');alert(arq);
		new Ajax.Request('./modulos/auditoria/'+arq, {
			method: 'post',
			parameters: $('frm_consulta').serialize()+"&p=<?php echo $sessao_perfil?>",
			onLoading : $('lista').innerHTML =  "<p align='center'><img src='./imagens/loader2.gif' width='21' height='21'/></p>",
			onComplete: function(transport){
				//alert(transport.responseText); exit;
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

function loadTipo(){
	
	switch($('consulta').value){
		
		case 'relatorio_nomefornecedores.php':
			$('celulaFornecedor').show();
			$('celulaUnidade').hide();
			$('celulaEmpenho').hide();
			$('celulaContrato').hide();
			$('requisicao').hide();
			$('data1').hide();
			$('data2').hide();
			 break;
		case 'relatorio_notasfiscais.php':
			$('celulaFornecedor').show();
			$('celulaUnidade').hide();
			$('celulaEmpenho').hide();
			$('celulaContrato').hide();
			$('requisicao').hide();
			$('data1').hide();
			$('data2').hide();
			 break;
		case 'relatorio_requisicaoemitida.php':
			 $('requisicao').show();
			 $('celulaFornecedor').show();
			 $('celulaUnidade').show();
			 $('celulaEmpenho').hide();
			 $('celulaContrato').hide();
 			 $('data1').hide();
			 $('data2').hide();
			 break;

		case 'relatorio_requisicoesporempresa.php':
			 $('celulaFornecedor').show();
			 $('celulaUnidade').hide();
			 $('celulaProduto').hide();
			 $('requisicao').hide();
			 $('celulaEmpenho').hide();
			 $('celulaContrato').hide();
 			 $('data1').hide();
			 $('data2').hide();
			 break;

		case 'relatorio_requisicaodefornecimentonaoatendida.php':
			 $('celulaFornecedor').show();
			 $('celulaUnidade').show();
			 $('requisicao').hide();
			 $('celulaProduto').hide();
			 $('celulaEmpenho').hide();
			 $('celulaContrato').hide();
 			 $('data1').show();
			 $('data2').show();
			 break;

		case 'relatorio_itenscontratadosporempresa.php':
			 $('celulaFornecedor').show();
			 $('celulaUnidade').hide();
			 $('requisicao').hide();
			 $('celulaProduto').hide();
			 $('celulaEmpenho').hide();
			 $('celulaContrato').hide();
 			 $('data1').show();
			 $('data2').show();
			 break;

		case 'relatorio_fornecedorprodserv.php':
			 $('celulaProduto').show();
			 $('celulaUnidade').show();
			 $('celulaEmpenho').hide();
			 $('celulaContrato').hide();
			 $('requisicao').hide();
 			 $('data1').hide();
			 $('data2').hide();
			 $('celulaFornecedor').hide();
			break;		

		case 'relatorio_nfdevolvidasaudirotia.php':
			 $('celulaProduto').hide();
			 $('celulaUnidade').hide();
			 $('celulaEmpenho').hide();
			 $('celulaContrato').hide();
			 $('celulaFornecedor').hide();
			 $('requisicao').hide();
 			 $('data1').show();
			 $('data2').show();
			break;	

		case 'relatorio_apresentacontrato.php':
			 $('celulaContrato').show();
			 $('celulaEmpenho').hide();
			 $('celulaFornecedor').hide();
			 $('celulaProduto').hide();
			 $('requisicao').hide();
 			 $('data1').hide();
			 $('data2').hide();
			break;		

		case 'relatorio_empenhos.php':
			 $('celulaEmpenho').show();
			 $('celulaContrato').hide();
			 $('celulaFornecedor').hide();
			 $('celulaProduto').hide();
			 $('requisicao').hide();
 			 $('data1').hide();
			 $('data2').hide();
			break;		

		default:
			$('celulaFornecedor').show();
			$('celulaUnidade').show();
			$('celulaEmpenho').hide();
			$('celulaContrato').hide();
			$('requisicao').hide();
			$('data1').hide();
			$('data2').hide();
	}
	$('lista').innerHTML = '&nbsp;';
}



function Limpar(){
	$('lista').innerHTML = '&nbsp;';
	$('consulta').value = "";
	loadTipo();
}

function Valida(campo) {
  if (campo.value.length == '')
  {
    if (confirm("Preenche com a data de Hoje?"))
    {
      Hoje = new Date();
      Data = Hoje.getDate();
      Dia = Hoje.getDay();
      Mes = Hoje.getMonth();
      Ano = Hoje.getFullYear();
      if(Data < 10)
        Data = "0" + Data;
      campo.value = Data + '/' + Mes + '/' + Ano;
    }else{
      campo.focus()
    }
  }
}

</script>
