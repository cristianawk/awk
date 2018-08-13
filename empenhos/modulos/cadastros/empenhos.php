<?php
//echo "<pre>"; print_r($_POST); echo "</pre>";
$sql = "SELECT id_unidade, nm_unidade FROM ".TBL_UNIDADE." ORDER BY id_unidade";
$global_conn->query($sql);
while($tupla = $global_conn->fetch_row()) $obms[] = $tupla;

$filtro = "'".implode("', '",
        array('btn_incluir', 'btn_reset', 'op_menu', 'filtro', 'nm_usuario', 'nr_cpf_usuario', 'nm_unidade', 'ds_email_usuario', 'nm_posto')
    )."'";

?>
<body>
<fieldset>
<legend>CADASTRO DE EMPENHOS</legend>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_empenho" id="frm_empenho" onsubmit="submitForm(); return false;" onreset="limparForm();" action="./modulos/cadastros/inc_empenhos.php">
<input type="hidden" name="op_menu" id="op_menu" value="<?=$id_load?>">
<input type="hidden" name="id_mtr_usuario" id="id_mtr_usuario" value="<?=$matricula_usuario?>">
<table border="0" width="100%" cellspacing="0" cellpadding="4" class="orgTableBorder">
<input type="hidden" name="id_empenho" id="id_empenho" value="">
<tr><th colspan="2" id="mesg">&nbsp;</th></tr>
<tr>
	<th width="20%">MATRICULA GESTOR:</th>
	<td><input type="text" name="id_mtr_gestor" id="id_mtr_gestor" value="" size="20" onblur="busca_gestor()" class="required"></td>
</tr>
<tr>
	<th>NOME GESTOR:</th>
	<td><input type="text" name="nm_usuario" id="nm_usuario" value="" size="60" class="required">&nbsp;&nbsp;<a style='cursor: pointer' onclick='busca_gestor()'><img src='./imagens/buttonright.jpeg' height='16' width='16' title='Consultar Nome do Gestor'></a></td>
</tr>
<tr>
	<th>CPF GESTOR:</th>
	<td><input type="text" name="nr_cpf_usuario" id="nr_cpf_usuario" value="" size="15" maxlength="15" class="required" onblur="cpfcnpj(this)" readonly="true">&nbsp;(Somente Numeros)</td>
</tr>
<tr>
     <th>UNIDADE DO GESTOR:</th>
     <td><input type="text" name="nm_unidade" id="nm_unidade" class="required" title="Unidade do Usuário" value="" size="15" readonly="true"></td>
</tr>
<tr>
     <th>POSTO DO GESTOR:</th>
     <td><input type="text" name="nm_posto" id="nm_posto" class="required" title="Unidade do Usuário" value="" size="20" readonly="true"></td>
</tr>
<tr>
	<th>EMAIL GESTOR:</th>
	<td><input type="text" name="ds_email_usuario" id="ds_email_usuario" value="" size="60" class="required" onblur="validaemail(this)"></td>
</tr>
<tr>
	<th>EMAIL COMANDANTE:</th>
	<td><input type="text" name="ds_email_comandante" id="ds_email_comandante" value="" size="60" class="required" onblur="validaemail(this)"></td>
</tr>

<tr><td colspan="4"><hr /></td></tr>

<tr>
	<th>UNIDADE GESTORA:</th>
	<td><input type="text" name="nr_orcamentario" id="nr_orcamentario" value="" size="6" maxlength="6" class="required" onblur="validanum(this,'6')">&nbsp;(Somente Numeros)</td>
</tr>
<tr>
	<th>SUBACAO:</th>
	<td><input type="text" name="nr_subacao" id="nr_subacao" value="" maxlength="4" size="4" onblur="validanum(this,'4')" class="required"></td>
</tr>
<tr>
	<th>NATUREZA DA DESPESA:</th>
	<td><input type="text" name="nr_despesa" id="nr_despesa" value="" size="8" maxlength="8" onblur="validanum(this,'8')" class="required">&nbsp;(Somente Numeros)</td>
</tr>
<tr>
	<th>EXIGIR TC-28:&nbsp;</th>
	<td>
		<select name="ch_tc28" id="ch_tc28">
			<option value="S">SIM</option>
			<option value="N">NÃO</option>
		</select>
	</td>
</tr>
<tr>
	<th>NUMERO DO EMPENHO:</th>
	<td><input type="text" name="ds_empenho" id="ds_empenho" value="" size="12" maxlength="12" onfocus="this.value = '<?=date('Y')."NE"?>';"  onblur="validaNE(this), verificaNE(this)" onkeyup="checaNE(this)" class="required">&nbsp;(ex: 2011NE000035)&nbsp;&nbsp;<a style='cursor: pointer' onclick='busca_empenho()'><img src='./imagens/buttonright.jpeg' height='16' width='16' title='Consultar Empenho'></a></td>
</tr>
<tr>
	<th>FONTE DE RECURSO:</th>
	<td><input type="text" name="nr_fonte_recursos" id="nr_fonte_recursos" value="" size="4" maxlength="4" class="required" onblur="validanum(this,'4')">&nbsp;(Somente Numeros)</td>
</tr>

<tr>
	<th>DATA DO EMPENHO:</th>
	<td><input type="text"  name="dt_empenho" id="dt_empenho" value="" size="10" class="required" onkeyup="checadata(this)" onblur="/*verificarData(this, '<?=date('d/m/Y')?>', 'Data do Empenho', 'Atual', true)*/" maxlength="10">&nbsp;<input type="button" value="" title="Clique para aparecer o calendário" style="background-image : url('./imagens/img.gif');" onclick="return showCalendar('dt_empenho', 'dd/mm/y');"></td>
</tr>
<tr>
	<th>DATA DO PAGAMENTO:</th>
	<td><input type="text"  name="dt_pagamento" id="dt_pagamento" value="" size="10" class="required" onkeypress="return formtData(this, event);" onkeyup="checadata(this)" onblur="verificarData(this, $('dt_empenho').value, 'Data do Pagamento', 'do Empenho', false)" maxlength="10">&nbsp;<input type="button" value="" title="Clique para aparecer o calendário" style="background-image : url('./imagens/img.gif');" onclick="return showCalendar('dt_pagamento', 'dd/mm/y');"></td></td>
</tr>
<tr>
	<th>VALOR REPASSADO:</th>
	<td><input type="text" name="ds_repasse" id="ds_repasse" value="" size="35" onblur="decimal(this,2);" class="required"></td>
</tr>
</table>
<br><hr/><br>
<table>
<tr>
    <th colspan="4">
        <input type="submit" name="btn_incluir" id="btn_incluir" value="CADASTRAR" class="botao">
        <input type="reset" name="btn_reset" id="btn_reset" value="LIMPAR" class="botao">
    </th>
</tr>
</table>
</form>
</fieldset>
</body>
<script type="text/javascript">

     //Variavel para janela
    var globalWin = "";
    var largura = window.innerWidth - ((window.innerWidth / 100) * 20);
    var altura = window.innerHeight - ((window.innerHeight / 100) * 20);

	var frm_empenho = new Validation('frm_empenho');

	function submitForm(){

		if(frm_empenho.validate()){
			$('frm_empenho').request({
				method: 'post',
				parameters: { 'filtro[]':[<?=$filtro?>] },
				onComplete: function(transport){
					//alert(transport.responseText); exit;
					var xmldoc=transport.responseXML;
					//alert(xmldoc);
					var flg = xmldoc.getElementsByTagName('flg')[0].firstChild.data;
					//alert(flg);
					if(flg == 1){
						loadMesg(xmldoc.getElementsByTagName('mesg')[0].firstChild.data, 'acerto');
						$('frm_empenho').reset();
					}else{
						loadMesg(xmldoc.getElementsByTagName('mesg')[0].firstChild.data, 'erro');
					}
				}
			});
		}

		return false;
	}

	function loadMesg(msg, optClass){

		$('mesg').innerHTML = "<div class='"+optClass+"'>"+msg+"</div>"

		//location.href='index.php#';

		setTimeout(function(){ $('mesg').innerHTML = '&nbsp;' }, 8000);

	}


    function busca_gestor(){

        if(globalWin != ""){ globalWin.destroy(); }//alert('entra aqui');

         if(($('id_mtr_gestor').value != "")||($('nm_usuario').value != "")){
            globalWin = new Window("gestor", {
                className: "alphacube",
                width:largura,
                height:altura,
                title:"Pesquisa de Gestores",
                url: "./modulos/cadastros/cons_gestores.php?id_mtr_usuario="+$('id_mtr_gestor').value+"&nm_usuario="+$('nm_usuario').value,
                showEffectOptions: {duration:0.1},
                //destroyOnClose: true,
                minimizable: false
            });

            globalWin.showCenter();
        }

    }

   function busca_empenho(){

        if(globalWin != ""){ globalWin.destroy(); }//alert('entra aqui');

         if($('ds_empenho').value != ""){
            globalWin = new Window("gestor", {
                className: "alphacube",
                width:largura,
                height:altura,
                title:"Pesquisa de Empenhos",
                url: "./modulos/cadastros/popup_empenhos.php?v=<?=$perfil_usuario?>&ds_empenho="+$('ds_empenho').value,
                showEffectOptions: {duration:0.1},
                //destroyOnClose: true,
                minimizable: false
            });

            globalWin.showCenter();
        }

    }


    function verificaNE(element){

		var aux = element.value.split("NE");

		//alert(aux);

		if(aux[1] == 000000){
			alert("Numero do empenho inválido!");
			$('nr_fonte_recursos').value = "";
            element.value = "";
            element.focus();
			exit;
		}

		var j = new Ajax.Request('./modulos/cadastros/validar_empenho.php' ,{
				method: 'post',
				parameters: 'empenho='+element.value,
				onComplete: function(transport){
					//alert(transport.responseText); exit;
					var xmldoc=transport.responseXML;
					//alert(xmldoc);
					var flg = xmldoc.getElementsByTagName('flg')[0].firstChild.data;
					//alert(flg);
					if(flg == 1){
                      var d = Dialog.confirm(xmldoc.getElementsByTagName('mesg')[0].firstChild.data, {
                            className: "alphacube",
                            id: "Dialogo",
                            buttonClass: "ButtonClass",
                            title:"AVISO",
                            width:480,
                            height:100,
                            showEffectOptions: {duration:0.1},
                            destroyOnClose: true,
                            okLabel: "SIM",
                            cancelLabel: "NÃO",
                            ok: function(){
                                    d.hide();
                                    d.destroy();
                                    $('nr_fonte_recursos').value = "";
                                    //element.value = "";
                                    //element.focus();
                                    busca_empenho();
                                    return true;
                                },
                            cancel: function(){
                                    d.hide();
                                    d.destroy();
                                    $('nr_fonte_recursos').value = "";
                                    element.value = "";
                                    element.focus();
                                    return true;
                                }
                        });
					}
				}
			});

    }


    function limparForm(){
		$('frm_empenho').reset();
		$('btn_incluir').value = "CADASTRAR";
	}

</script>
