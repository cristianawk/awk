<?php

echo "aqui";

$conn->query("SELECT * FROM funcao ORDER BY nm_funcao");
$funcoes = $conn->get_tupla();

$conn->query("SELECT * FROM estados ORDER BY ds_uf");
$estados = $conn->get_tupla();

$conn->query("SELECT id_banco, to_char(id_banco, '000') AS cd_banco, nm_banco FROM bancos ORDER BY id_banco");
$bancos = $conn->get_tupla();

?>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_fornecedor" id="frm_fornecedor" onsubmit="submitForm(); return false;" onreset="Limpar(); return true;" action="./modulos/administrativo/inc_fornecedor.php">
<input type="hidden" name="hdn_acao" id="hdn_acao" value="inc">
<input type="hidden" name="id_fornecedor" id="id_fornecedor" value="">
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="2"><?php echo $modulo_arquivo['nm_rotina']?></th></tr>
<tr>
	<td width="20%">Cnpj / Cpf</td><!--onblur="cpfcnpj(this), consultaCnpj(this)"/>-->
	<td><input type="text" name="ds_cnpj" id="ds_cnpj" value="" size="15" maxlength="14" class="required" onblur="cpfcnpj(this)"/>
	&nbsp;<input type="button" name="btn_cons" value=">>"  onclick="consultaCnpj($('ds_cnpj'))"></td>
</tr>
<tr>
	<td>Nome do Fornecedor</td>
	<td>
        <input type="text" name="nm_fornecedor" id="nm_fornecedor" class="required" value="" size="30" />
        &nbsp;<input type="button" name="btn_cons" value="Consultar Fornecedor" class="botao" onclick="buscarFornecedor($('nm_fornecedor'))">
    </td>
</tr>
<tr>
	<td>Cep</td>
	<td><input type="text" name="nr_cep_logradouro" id="nr_cep_logradouro" value="" size="9" maxlength="9" onkeyup="cep(this)" class="required" onblur="buscaCep()"></td>
</tr>
<tr>
	<td>Logradouro</td>
	<td><input type="text" name="nm_logradouro" id="nm_logradouro" value="" size="40" class="required" /></td>
</tr>
<tr>
	<td>Bairro</td>
	<td><input type="text" name="nm_bairro" id="nm_bairro" value="" size="35"  class="required"/></td>
</tr>
<tr>
	<td>Cidade</td>
	<td><input type="text" name="nm_cidade" id="nm_cidade" value="" size="30" class="required" /></td>
</tr>
<tr>
	<td>Estado</td>
	<td>
		<select id="id_estado" name="id_estado" class="validate-selection">
		<option value="">----</option>
		<?php foreach($estados AS $estado){ ?>
		<option value="<?php echo $estado['id_estado']?>"><?php echo $estado['ds_uf']?></option>
		<?php } ?>
		</select>
	</td>
</tr>
<tr>
	<td>Responsável</td>
	<td><input type="text" name="nm_responsavel" id="nm_responsavel" value="" size="30" class="required" /></td>
</tr>
<tr>
	<td>Cpf Responsável</td>
	<td><input type="text" name="ds_cpf" id="ds_cpf" value="" size="15" maxlength="11" onblur="cpfcnpj(this)" class="required"/></td>
</tr>
<tr>
	<td>Função</td>
	<td>
		<select id="id_funcao" name="id_funcao" class="validate-selection">
		<option value="">---------------------</option>
		<?php foreach($funcoes AS $funcao){ ?>
		<option value="<?php echo $funcao['id_funcao']?>"><?php echo $funcao['nm_funcao']?></option>
		<?php } ?>
		</select>
	</td>
</tr>
<tr>
	<td>Telefone 1</td>
	<td><input type="text" name="nr_telefone1" id="nr_telefone1" value="" size="13" maxlength="13" onkeyup="telefone(this)" class="required"/></td>
</tr>
<tr>
	<td>Telefone 2</td>
	<td><input type="text" name="nr_telefone2" id="nr_telefone2" value="" size="13" maxlength="13" onkeyup="telefone(this)" /></td>
</tr>
<tr>
	<td>Email 1</td>
	<td><input type="text" name="ds_email1" id="ds_email1" value="" size="35" class="required"/></td>
</tr>
<tr>
	<td>Email 2</td>
	<td><input type="text" name="ds_email2" id="ds_email2" value="" size="35" /></td>
</tr>
<tr>
	<td>Código Banco</td>
	<td>
		<select id="id_banco" name="id_banco" class="validate-selection">
		<option value="">---------------------------------------------------------</option>
		<?php foreach($bancos AS $banco){ ?>
		<option value="<?php echo $banco['id_banco']?>"><?php echo $banco['cd_banco']?> - <?php echo $banco['nm_banco']?></option>
		<?php } ?>
		</select>
	</td>
</tr>
<tr>
	<td>Código Agência</td>
	<td><input type="text" name="ds_agencia" id="ds_agencia" value="" size="6" maxlength="6" onblur="validaBanco(this, '6')" class="required"/> <input type="text" name="ds_agencia_dv" id="ds_agencia_dv" value="" size="2" maxlength="2" class="required" /> <i id="dv_agencia" class="required">Dígito Verificador</i></td>
</tr>
<tr>
	<td>Código Conta</td>
	<td><input type="text" name="ds_conta" id="ds_conta" value="" size="10" maxlength="10" onblur="validaBanco(this, '10')" class="required"/> <input type="text" name="ds_conta_dv" id="ds_conta_dv" value="" size="1" maxlength="1" class="required" /> <i id="dv_conta" class="required">Dígito Verificador</i></td>
</tr>
</table>
<p>Os campos sinalizados com <img src='./imagens/obriga.gif' /> são de preenchimento obrigatório</p>
<hr>
<p>
  <input type="submit" name="btn_incluir" id="btn_incluir" class="botao" Value="Cadastrar">
  &nbsp;
  <input type="reset" name="btn_limpar" class="botao" Value="Limpar">
</p>
</form>
<script type="text/javascript">

	var frm_fornecedor = new Validation('frm_fornecedor');

    function submitForm(){
	  if(frm_fornecedor.validate()){
		$('frm_fornecedor').request({
				method: 'post',
				onComplete: function(transport){
					//alert(transport.responseText); exit;
					var xmldoc = transport.responseXML;
					//alert(xmldoc);
					var flg = xmldoc.getElementsByTagName('flg')[0].firstChild.data;
					//alert(flg);
					if(flg == 1){
						alert(xmldoc.getElementsByTagName('mesg')[0].firstChild.data);
						Limpar();
					}else{
						alert(xmldoc.getElementsByTagName('mesg')[0].firstChild.data);
					}
				}
			});
	  }
		return false;
	}


	function consultaCnpj(campo){
		if(campo.value != ""){
			var valor = campo.value;
			new Ajax.Request("./modulos/administrativo/cons_fornecedor.php", {
				method: "POST",
				parameters: "id="+campo.value,
				onLoading: Element.insert(campo.name, {after:"<div id='load_"+campo.name+"' class='loading'><img src='./imagens/loader.gif' width='14' height='14'/></div>"}),
				onSuccess: function(transport){
					//alert(transport.responseText); exit;
					var xmldoc = transport.responseXML;
					//Se existir registro
					if(xmldoc.getElementsByTagName('lib')[0].firstChild.data == 1) {
						//alert($('frm_fornecedor').length);
						for(i=0; i < $('frm_fornecedor').length; i++){
							try{
								$('frm_fornecedor')[i].value = xmldoc.getElementsByTagName($('frm_fornecedor')[i].name)[0].firstChild.data;
							}catch(e){
								//alert(e);
							}
							$('hdn_acao').value = "alt";
							$('btn_incluir').value = "Alterar";
						}

					//Senao existir nenhum registro
					} else {
						Limpar();
						campo.value = valor;
					}

					//Terminou o carregamento retira a imagem de load
					Element.remove($('load_'+campo.name));
				}
			});
		}
	}

	function buscarFornecedor(element){

       //alert(globalWin);
       if(globalWin != ""){ globalWin.destroy(); }
        //if(element.value != ""){
            globalWin = new Window("fornece", {
                className: "alphacube",
                width:largura,
                height:altura,
                title:"Lista de Fornecedores",
                url: "./modulos/administrativo/lista_fornecedores.php?fornecedor="+element.value,
                showEffectOptions: {duration:0.1},
                //destroyOnClose: true,
                minimizable: false
            });
            //globalWin.setDestroyOnClose();
            globalWin.showCenter(true);
        //}
    }


	function Limpar(){
        $('frm_fornecedor').reset();
        frm_fornecedor.reset();
        $('hdn_acao').value = 'inc';
        $('btn_incluir').value = 'Cadastrar';
    }
</script>
