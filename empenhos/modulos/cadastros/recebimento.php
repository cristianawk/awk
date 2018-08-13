<?php

$tipos = array(
    '<option value="ds_empenho">N. Empenho</option>',
    '<option value="dt_empenho">Data do Empenho</option>',
    '<option value="nm_usuario">Nome do Gestor</option>',
    '<option value="id_mtr_gestor">Matricula do Gestor</option>',
    '<option value="nr_cpf_usuario">CPF Gestor</option>',
    '<option value="ds_repasse">Valor Repassado</option>'
);

?>
<fieldset>
<legend>RECEBIMENTO</legend>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_recebimento" id="frm_recebimento" onsubmit="submitForm(); return false;" action="./modulos/cadastros/inc_recebimento.php">
<input type="hidden" name="op_menu" id="op_menu" value="<?=$id_load?>">
<table border="0" width="100%" cellspacing="0" cellpadding="4" class="orgTable">
<tr><th colspan="2" id="mesg">&nbsp;</th></tr>
		<tr>
         <th align="right">Tipo:</th>
          <td>
           <select name="tipo" id="tipo" class="campo_obr">
            <option value="">--------------</option>
			<?
			foreach($tipos AS $tipo){
			echo $tipo."\n";
			}
			?>
           </select>
          </td>
        </tr>
<tr>
	<td></td>
	<td><input type="text" name="dados" id="dados" value="" size="12" maxlength="12" class=""></td>
</tr>
<tr>
<tr><th colspan="4">&nbsp;</th></tr>
<tr><th colspan="4"><hr></th></tr>
<tr>
    <th colspan="4">
        <input type="button" name="btn_incluir" id="btn_incluir" value="PESQUISAR" class="botao" onclick="loadRecebimentos()">
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

	function loadMesg(msg, optClass){

		loadRecebimentos();

		$('mesg').innerHTML = "<div class='"+optClass+"'>"+msg+"</div>"

		setTimeout(function(){ $('mesg').innerHTML = '&nbsp;' }, 8000);

	}

	function loadRecebimentos(){
		new Ajax.Request('./modulos/cadastros/cons_recebimento.php', {
			method: 'get',
			parameters: 'tipo='+$('tipo').value+'&dados='+$('dados').value,
			onComplete: function(transport){
				//alert(transport.responseText); exit;
				$('vtr').innerHTML = transport.responseText;
			}
		})
	}

	loadRecebimentos();

function recebimento(id){

	if(globalWin != ""){ globalWin.destroy(); }

	globalWin = new Window("recebimento", {
		className: "alphacube",
		width:largura,
		height:altura,
		title:"Controle de Prazos",
		url: "./modulos/cadastros/popup_recebimento.php?id="+id+"&id_matricula=<?=$matricula_usuario?>&id_perfil=<?=$perfil_usuario?>",
		showEffectOptions: {duration:0.1},
		//destroyOnClose: true,
		minimizable: false
	});
	globalWin.showCenter();
}

</script>
<br>
<fieldset>
<legend>REGISTROS ENCONTRADOS: </legend>
<div id="vtr"></div>
</fieldset>
