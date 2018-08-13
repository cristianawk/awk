<?php

$obms = null;
$sql = "SELECT id_unidade, nm_unidade FROM ".TBL_UNIDADE." ORDER BY id_unidade";
$global_conn->query($sql);
while($tupla = $global_conn->fetch_row()) $obms[] = $tupla;

$tipos = array(
    'id_mtr_usuario' 	=> 'MATRICULA',
    'nm_usuario' 		=> 'NOME',
    'nr_cpf_usuario' 	=> 'CPF',
    'id_unidade' 		=> 'UNIDADE'
);

?>
<fieldset>
<legend>Consulta Gestores Liberados</legend>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_liberados" id="frm_liberados" >
<input type="hidden" name="op_menu" id="op_menu" value="<?=$id_load?>">
<input type="hidden" name="order_by" id="order_by" value="">
<input type="hidden" name="ord" id="ord" value="ASC">
<table border="0" width="100%" cellspacing="0" cellpadding="4" class="orgTable">
<tr><th colspan="2" id="mesg">&nbsp;</th></tr>
<tr>
	<th width="15%">TIPO:</th>
          <td>
           <select name="tipo" id="tipo" class="campo_obr" onchange="defineCampo(this)">
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
<tr>
<tr><th colspan="4">&nbsp;</th></tr>
<tr><th colspan="4"><hr></th></tr>
<tr>
    <th colspan="4">
        <input type="button" name="btn_incluir" id="btn_incluir" value="PESQUISAR" class="botao" onclick="loadLiberados()">
    </th>
</tr>
</table>
</form>
</fieldset>
<script type="text/javascript">

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



	function loadLiberados(){
		new Ajax.Request('./modulos/relatorios/rel_liberados.php', {
			method: 'get',
			parameters: 'tipo='+$('tipo').value+'&dados='+$('dados').value+'&order_by='+$('order_by').value+'&ord='+$('ord').value,
			onComplete: function(transport){
				//alert(transport.responseText); exit;
				$('rec').innerHTML = transport.responseText;
			}
		});
	}

	loadLiberados();

	function montaRelatorio(tipo){
		$('ext').value = tipo;
		$('frm_cons_geral_lib').submit();
	}


	function ordenar(coluna, ord){
		//alert(coluna);
		$('order_by').value = coluna;
		$('ord').value = ord;
		loadLiberados();
	}
</script>
<br>
<fieldset>
<legend>GESTORES LIBERADOS: </legend>
<div id="rec"></div>
</fieldset>

