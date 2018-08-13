<?php
$obms = null;
$sql = "SELECT id_unidade, nm_unidade FROM ".TBL_UNIDADE." ORDER BY id_unidade";
$global_conn->query($sql);
while($tupla = $global_conn->fetch_row()) $obms[] = $tupla;


$tipos = array(
    'id_mtr_gestor' 	=> 'MATRICULA',
    'nm_usuario' 		=> 'NOME',
    'nr_cpf_usuario' 	=> 'CPF',
    'id_unidade' 		=> 'UNIDADE'
);
?>
<fieldset>
<legend>Consulta Empenhos Emitidos</legend>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_empenhos" id="frm_empenhos" >
<input type="hidden" name="op_menu" id="op_menu" value="<?=$id_load?>">
<input type="hidden" name="order_by" id="order_by" value="">
<input type="hidden" name="ord" id="ord" value="ASC">
<table border="0" width="100%" cellspacing="0" cellpadding="4" class="orgTable">
<tr><th colspan="2" id="mesg">&nbsp;</th></tr>
<tr>
	<th>DATA INICIAL:</th>
    <td><input type="text" name="dt_inicial" id="dt_inicial" value="" onkeyup="checadata(this)" maxlength="10" size="10">&nbsp;<input type="button" value="" title="Clique para aparecer o calendário" style="background-image : url('./imagens/img.gif');" onclick="return showCalendar('dt_inicial', 'dd/mm/y');"></td>
</tr>
<tr>
	<th>DATA FINAL:</th>
    <td><input type="text" name="dt_final" id="dt_final" value="" onkeyup="checadata(this)" maxlength="10" size="10">&nbsp;<input type="button" value="" title="Clique para aparecer o calendário" style="background-image : url('./imagens/img.gif');" onclick="return showCalendar('dt_final', 'dd/mm/y');"></td>
</tr>
<tr><th colspan="4"><hr></th></tr>
<tr>
	<th width="15%">TIPO:</th>
          <td>
           <select name="tipo" id="tipo" class="" onchange="defineCampo(this)">
            <option value="">-----------------------</option>
			<? foreach($tipos AS $id => $tp){ ?>
			<option value="<?=$id?>"><?=$tp?></option>
			<? } ?>
           </select>
          </td>
</tr>
<tr>
	<td></td>
	<td id="cmp"><input type="text" name="dados" id="dados" value="" size="30" maxlength="30"></td>
</tr>
<tr><th colspan="4">&nbsp;</th></tr>
<tr><th colspan="4"><hr></th></tr>
<tr>
    <th colspan="4">
        <input type="button" name="btn_incluir" id="btn_incluir" value="PESQUISAR" class="botao" onclick="loadEmpenhos()">
    </th>
</tr>
</table>
</form>
</fieldset>
<script type="text/javascript">
//Variavel para janela
var globalWin = "";

var largura = window.innerWidth - ((window.innerWidth / 100) * 20);
var altura = window.innerHeight - ((window.innerHeight / 100) * 20);

function recebimento(id){

	if(globalWin != ""){ globalWin.destroy(); }

	globalWin = new Window("recebimento", {
		className: "alphacube",
		width:largura,
		height:altura,
		title:"Controle de Prazos",
		url: "./modulos/relatorios/popup_recebimento.php?id="+id,
		showEffectOptions: {duration:0.1},
		//destroyOnClose: true,
		minimizable: false
	});
	globalWin.showCenter();
}


function defineCampo(element){

		if(element.value != ""){

			if(element.value == "id_unidade"){
				$('cmp').innerHTML = "<select name='dados' id='dados' class='campo_obr' ><option value=''>-------------------------------------------</option><? foreach($obms AS $obm){ ?><option value='<?=$obm['id_unidade']?>'><?=$obm['nm_unidade']?></option><? } ?></select>"
			}else{
				$('cmp').innerHTML = "<input type='text' name='dados' id='dados' value='' size='30' maxlength='30'>";
			}


		}else{
			$('cmp').innerHTML = "<input type='text' name='dados' id='dados' value='' size='30' maxlength='30'>";
		}


	}


	function loadEmpenhos(){
		new Ajax.Request('./modulos/relatorios/rel_empenhos.php', {
			method: 'get',
			parameters: 'order_by='+$('order_by').value+'&ord='+$('ord').value+"&dt_inicial="+$('dt_inicial').value+"&dt_final="+$('dt_final').value+'&tipo='+$('tipo').value+'&dados='+$('dados').value,
			onComplete: function(transport){
				//alert(transport.responseText); exit;
				$('emp').innerHTML = transport.responseText;
			}
		});
	}

	loadEmpenhos();

	function montaRelatorio(tipo){
		$('ext').value = tipo;
		$('frm_cons_geral_oco').submit();
	}


	function ordenar(coluna, ord){
		//alert(coluna);
		$('order_by').value = coluna;
		$('ord').value = ord;
		loadEmpenhos();
	}
</script>
<br>
<fieldset>
<legend>EMPENHOS EMITIDOS: </legend>
<div id="emp"></div>
</fieldset>

