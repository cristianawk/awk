<?php

$conn->query("SELECT id_fornecedor, nm_fornecedor, ds_email1, ds_email2, ds_cnpj FROM vw_fornecedores ORDER BY nm_fornecedor");
$fornecedores = $conn->get_tupla();
//echo "<pre>"; print_r($fornecedores); echo "<pre>"; exit;

$conn->query("SELECT id_unidade, nm_unidade FROM unidades_beneficiadas ORDER BY id_unidade");
$unidades = $conn->get_tupla();

$conn->query("SELECT ds_cnpj_unidade_orcamentaria, nm_unidade_orcamentaria FROM unidades_orcamentarias ORDER BY ds_cnpj_unidade_orcamentaria DESC");
$orcamentarias = $conn->get_tupla();
//echo "<pre>"; print_r($orcamentarias); echo "<pre>";

?>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_empenho" id="frm_empenho" onsubmit="submitForm(); return false;" onreset="Limpar(); return true;" action="./modulos/administrativo/inc_empenho.php">
<input type="hidden" name="hdn_acao" id="hdn_acao" value="inc">
<input type="hidden" name="id_empenho" id="id_empenho" value="">
<input type="hidden" name="ch_bloqueio" id="ch_bloqueio" value="">
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="2"><?php echo $modulo_arquivo['nm_rotina']?></th></tr>
<tr>
	<td>Nome do Fornecedor</td>
	<td>
		<select id="id_fornecedor" name="id_fornecedor" class="validate-selection">
		<option value="">-------------------------------------------------------</option>
		<?php foreach($fornecedores AS $fornecedor){ ?>
		<option value="<?php echo $fornecedor['id_fornecedor']?>"><?php echo $fornecedor['nm_fornecedor']?></option>
		<?php } ?>
		</select><br>
        &nbsp;<input type="button" name="btn_cons" value="Consultar Empenhos" class="botao" onclick="buscarEmpenhos($('id_fornecedor'), $('id_unidade'))">
	</td>
</tr>
<tr>
	<td width="25%">Número Empenho</td>
	<td>
        <input type="text" name="ds_empenho" id="ds_empenho" value="" size="12" maxlength="12" class="required" onfocus="this.value = '<?php echo date('Y')."NE"?>';"  onblur="validaNE(this); consultaEmpenho(this);" />
    </td>
</tr>
<tr>
	<td>Unidade Beneficiada</td>
	<td>
		<select id="id_unidade" name="id_unidade" class="validate-selection">
		<option value="">---------------------</option>
		<?php foreach($unidades AS $unidade){ ?>
		<option value="<?php echo $unidade['id_unidade']?>"><?php echo $unidade['nm_unidade']?></option>
		<?php } ?>
		</select>
	</td>
</tr>

<tr>
	<td>CNPJ Unidade Orçamentaria</td>
	<td>
        <select id="ds_cnpj_unidade_orcamentaria" name="ds_cnpj_unidade_orcamentaria" value="" class="required">
        
		<?php foreach($orcamentarias AS $orcamentaria){ ?>
		<option value="<?php echo $orcamentaria['ds_cnpj_unidade_orcamentaria']?>"><?php echo $orcamentaria['ds_cnpj_unidade_orcamentaria']?>&nbsp;&nbsp;&nbsp;<?php echo $orcamentaria['nm_unidade_orcamentaria']?></option>
		<?php } ?>
        </select>
    </td>
</tr>

<tr>
	<td>Data do Empenho</td>
	<td><input type="text" name="dt_empenho" id="dt_empenho" value="" size="10" maxlength="10" onChange="verifica_data()"  readonly="readonly" class="required, data" /> 
	<a name="btn" onclick="javascript:displayCalendar($('dt_empenho'),'dd/mm/yyyy',this);"><img src="imagens/iconCalendar.gif" align="top"></img></a>
	<div id="dt_exibe" style="display: none"><font color="red">A Data do Empenho não pode ser diferente da Data Atual!</font></div>
	
	<td>
</tr>

<tr>
	<td>Contrato Exigido</td>
	<td>
		<select id="ch_contrato" name="ch_contrato">
		<option value="N">NÃO</option>
		<option value="S">SIM</option>
		</select>
	</td>
</tr>

<tr>
	<td>Requisição Exigida</td>
	<td>
		<select id="ch_requisicao" name="ch_requisicao">
		<option value="N">NÃO</option>
		<option value="S">SIM</option>
		</select>
	</td>
</tr>

<tr>
	<td>Valor Total do Empenho</td>
	<td><input type="text" name="vl_empenho" id="vl_empenho" value="" size="11" maxlength="13" class="required" onkeyup="FormatNumero(this)" onblur="decimal(this,2);"/></td>
</tr>

<tr>
	<td>Numero do Contrato</td>
	<td><input type="text" name="nr_contrato" id="nr_contrato" value="" size="20" class="required"/></td>
</tr>

<tr>
	<td>Data do Contrato</td>
	<td><input type="text" name="dt_contrato" id="dt_contrato" value="" size="10" maxlength="10" readonly="readonly" class="required" /> <a name="btn" onclick="javascript:displayCalendar($('dt_contrato'),'dd/mm/yyyy',this);"><img src="imagens/iconCalendar.gif" align="top"></img></a></td>
</tr>

<tr>
	<td>Nome do Arquivo do Contrato</td>
	<td><input type="text" name="ds_contrato" id="ds_contrato" value="" size="20" class="required"/></td>
</tr>

<tr>
	<td>Numero de Itens Contratados</td>
	<td>
        <input type="text" name="nr_items_contratados" id="nr_items_contratados" value="" size="10" class="required"/>
        <input type="hidden" name="nr_items_encontrados" id="nr_items_encontrados" value=""/>
    </td>
</tr>

<tr>
	<td>Data Inicio Fornecimento</td>
	<td><input type="text" name="dt_inicio" id="dt_inicio" value="" size="10" maxlength="10" onChange="verifica_dtinicio()"readonly="readonly" class="required"/> 
	<a name="btn" onclick="javascript:displayCalendar($('dt_inicio'),'dd/mm/yyyy',this);"><img src="imagens/iconCalendar.gif" align="top"></img></a>
	<div id="dt_exibe2" style="display: none"><font color="red">A Data do Inicio do Fornecimento não pode ser inferior a da Data do Contrato!</font></div>
	</td>
</tr>

<tr>
	<td>Data Fim Contrato</td>
	<td><input type="text" name="dt_final" id="dt_final" value="" size="10" maxlength="10" readonly="readonly" class="required"/> <a name="btn" onclick="javascript:displayCalendar($('dt_final'),'dd/mm/yyyy',this);"><img src="imagens/iconCalendar.gif" align="top"></img></a></td>
</tr>
</table>
<br>
<p>Os campos sinalizados com <img src='./imagens/obriga.gif' /> são de preenchimento obrigatório</p>
<hr>
<p><input type="submit" name="btn_incluir" id="btn_incluir" class="botao" Value="Cadastrar">&nbsp;<input type="reset" name="btn_limpar" class="botao" Value="Limpar"></p>

</form>

<script type="text/javascript">


	/**
	 * Valida o formulario
	 */
	var frm_empenho = new Validation('frm_empenho');

    /**
     * Funvao executada ao submeter a pagina
     */
	
	function exibe(id){
		mostrado=0;
		elem = document.getElementById(id);
		if(elem.style.display=='block'){
			mostrado=1;
		}else{
		elem.style.display='none';
		}
		if(mostrado!=1)elem.style.display='block';
	}
	

	function verifica_data(){
		var data = new Date();  
		var ano1 = data.getFullYear();
		var ano2 = dt_empenho.value;
		var ano3 = ano2.substr(6,4);
	if(ano3 != ano1){
			exibe('dt_exibe');
        }else{
			elem.style.display='none';
		}

	 }
	
	function verifica_dtinicio(){
		var dtcontrato = $('frm_empenho').dt_contrato.value;
		var dtinicio = dt_inicio.value;
	if(dtcontrato > dtinicio){  
		exibe('dt_exibe2');
		}else{
			elem.style.display='none';
		}
    }
	
	
	function submitForm(){
	$('vl_empenho').value=$('vl_empenho').value.replace('.','');
	//alert($('vl_empenho').value);
      if(frm_empenho.validate()){

        if($('nr_items_contratados').value < $('nr_items_encontrados').value){
           Dialog.alert("O numero de items contratados que esta tentando colocar é menor que o número de items já cadastrados! Se você deseja alterar o numero de items contratados, terá que excluir o numero de items necessários na rotina Cadastro de Items Contratados para poder fazer essa operação.", {
                            className: "alphacube",
                            id: "Dialogo",
                            buttonClass: "botao",
                            title:"AVISO",
                            width:480,
                            height:130,
                            showEffectOptions: {duration:0.1},
                            destroyOnClose: true,
                            okLabel: "OK"
                        });
        }else{
            //Envia o formulario
            enviar();
        }
	  }
		//return false;
	}

    /**
     * Funcao que envia o formulario
     */
    function enviar1(){
        if($('ch_bloqueio').value != 'S'){
            $('frm_empenho').request({
				method: 'post',
                //parameters: { 'deletar_item' : del },
				onComplete: function(transport){
					//alert(transport.responseText); exit;
					var xmldoc = transport.responseXML;
					//alert(xmldoc);
					var flg = xmldoc.getElementsByTagName('flg')[0].firstChild.data;
					//alert(flg);
					if(flg == 1){
						alert(xmldoc.getElementsByTagName('mesg')[0].firstChild.data);
						enviar_email(); //exit;
						Limpar();
					}else{
						alert(xmldoc.getElementsByTagName('mesg')[0].firstChild.data);
					}
				}
            });
           return false;
        }else{
            alert("Alteração dos dados do empenho nao permitida.\nJá existe requisição registradas para esse empenho.");
        }
    }


	/**
	 * Consulta o empenho pela descricao
	 */
	function consultaEmpenho(campo){
		if(campo.value != ""){
			var valor = campo.value;
			new Ajax.Request("./modulos/administrativo/cons_empenho.php", {
				method: "POST",
				parameters: "id="+campo.value,
				onLoading: Element.insert(campo.name, {after:"<div id='load_"+campo.name+"' class='loading'><img src='./imagens/loader.gif' width='14' height='14'/></div>"}),
				onSuccess: function(transport){
					//alert(transport.responseText); exit;
					var xmldoc = transport.responseXML;
					//Se existir registro
					if(xmldoc.getElementsByTagName('lib')[0].firstChild.data == 1) {
						//alert(xmldoc.getElementsByTagName('lib')[0].firstChild.data);
						for(i=0; i < $('frm_empenho').length; i++){
							try{
								$('frm_empenho')[i].value = xmldoc.getElementsByTagName($('frm_empenho')[i].name)[0].firstChild.data;
							}catch(e){
								//alert(e);
							}
							$('hdn_acao').value = "alt";
							$('btn_incluir').value = "Alterar";
						}

					//Senao existir nenhum registro
					} else {
						//Limpar();
						//campo.value = valor;
						$('hdn_acao').value = "inc";
						$('btn_incluir').value = "Cadastrar";
					}

					//Terminou o carregamento retira a imagem de load
					Element.remove($('load_'+campo.name));
				}
			});
		}
	}

	/**
	 * Janela que mostra todas os empenhos cadastrados
	 */
    function buscarEmpenhos(element1, element2){

       //alert(globalWin);
       if(globalWin != ""){ globalWin.destroy(); }
            globalWin = new Window("emp", {
                className: "alphacube",
                width:largura,
                height:altura,
                title:"Lista de Empenhos",
                url: "./modulos/administrativo/lista_empenhos.php?fornecedor="+element1.value+"&unidade="+element2.value,
                showEffectOptions: {duration:0.1},
                //destroyOnClose: true,
                minimizable: false
            });
            //globalWin.setDestroyOnClose();
            globalWin.showCenter(true);
    }

	/**
	 * Limpa o formulario
	 */
	function Limpar(){
		elem = document.getElementById('dt_exibe');
		elem2 = document.getElementById('dt_exibe2');
		elem.style.display='none';
		elem2.style.display='none';
        $('frm_empenho').reset();
        frm_empenho.reset();
        $('id_empenho').value = "";
        $('ch_bloqueio').value = "";
        $('hdn_acao').value = 'inc';
        $('btn_incluir').value = 'Cadastrar';
    }


	/**
	 * Envia o email
	 */
	function enviar_email(){
		//alert("OK");
		var form_mail = new Element("form", { id:"form_mail", name:"form_mail", method:"POST", enctype:"multipart/form-data", target:"iframe_mail", action:"./modulos/administrativo/mail_empenho.php", style:"display: none;"});
		//alert(form_mail);
		var iframe = new Element("iframe", {name:"iframe_mail", id:"iframe_mail", style:"display: none;"});

		var input_hidden = new Element('input', {type:'hidden', name:'dados_fornecedor', id:'dados_fornecedor', style:'display: none;', value:'<?=formata::encodeJSON($fornecedores)?>'});
		var input_hidden2 = new Element('input', {type:'hidden', name:'dados_unidade', id:'dados_unidade', style:'display: none;', value:'<?=formata::encodeJSON($unidades)?>'});

		var ds_empenho = Element.clone($('ds_empenho'));
		var dt_empenho = Element.clone($('dt_empenho'));
		var vl_empenho = Element.clone($('vl_empenho'));
		var id_fornecedor = new Element('input', {type:'hidden', name:'id_fornecedor', id:'id_fornecedor', style:'display: none;', value:$('id_fornecedor').value });
		var id_unidade = new Element('input', {type:'hidden', name:'id_unidade', id:'id_unidade', style:'display: none;', value:$('id_unidade').value });

		form_mail.appendChild(input_hidden);
		form_mail.appendChild(input_hidden2);
		form_mail.appendChild(ds_empenho);
		form_mail.appendChild(id_fornecedor);
		form_mail.appendChild(id_unidade);
		form_mail.appendChild(dt_empenho);
		form_mail.appendChild(vl_empenho);
		//insere na pagina o iframe
		document.body.insert(form_mail);
		document.body.insert(iframe);

		form_mail.submit();

	}
</script>
