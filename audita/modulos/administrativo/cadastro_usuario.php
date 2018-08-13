<?php

$conn->query("SELECT * FROM estados ORDER BY ds_uf");
$estados = $conn->get_tupla();

$conn->query("SELECT * FROM perfil ORDER BY nm_perfil");
$perfis = $conn->get_tupla();

$conn->query("SELECT * FROM unidades_beneficiadas ORDER BY nm_unidade, id_unidade");
$unidades = $conn->get_tupla();
   
?>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_usuario" id="frm_usuario" onsubmit="submitForm(); return false;" onreset="Limpar(); return true;" action="./modulos/administrativo/inc_usuario.php">
<input type="hidden" name="hdn_acao" id="hdn_acao" value="inc">
<input type="hidden" name="id_usuario" id="id_usuario" value="">
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="2"><?php echo $modulo_arquivo['nm_rotina']?></th></tr>
<tr>
	<td width="20%">Cpf</td>
	<td><input type="text" name="ds_cpf_usuario" id="ds_cpf_usuario" value="" size="15" maxlength="11" class="required" onblur="cpfcnpj(this), consultaCpf(this)"/></td>
</tr>
<tr>
	<td>Nome do Usuário</td>
	<td><input type="text" name="nm_usuario" id="nm_usuario" class="required" value="" size="30" onblur="consultaUsr(this)"/></td>
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
	<td><input type="text" name="nm_cidade" id="nm_cidade" value="" size="30" onkeyup="fonetica(this)" class="required" /></td>
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
	<td>Telefone 1</td>
	<td><input type="text" name="nr_telefone" id="nr_telefone" value="" size="13" maxlength="13" onkeyup="telefone(this)" class="required"/></td>
</tr>
<tr>
	<td>Telefone 2</td>
	<td><input type="text" name="nr_celular" id="nr_celular" value="" size="13" maxlength="13" onkeyup="telefone(this)"/></td>
</tr>
<tr>
	<td>Email</td>
	<td><input type="text" name="ds_email" id="ds_email" value="" size="35" class="required"/></td>
</tr>
<tr><td colspan="2"><hr></td></tr>
<tr>
	<td>Login</td>
	<td><input type="text" name="nm_login" id="nm_login" value="" size="25" class="required"/></td>
</tr>
<tr>
	<td>Nova Senha</td>
	<td><input type="password" name="ps_senha" id="ps_senha" value="cbmscmudar" size="25" class="required"/></td>
</tr>
<tr>
	<td>Perfil</td>
	<td>
		<select id="id_perfil" name="id_perfil" class="validate-selection">
		<option value="">-------------------------------------------------</option>
		<?php foreach($perfis AS $perfil){ ?>
		<option value="<?php echo $perfil['id_perfil']?>"><?php echo $perfil['nm_perfil']?></option>
		<?php } ?>
		</select>
	</td>
</tr>
<tr>
	<td>Unidade Beneficiada</td>
	<td>
		<select id="id_unidade" name="id_unidade" class="validate-selection">
		<option value="">-------------------------------------------------</option>
		<option value=null>TODOS</option>
		<?php foreach($unidades AS $unidade){ ?>
		<option value="<?php echo $unidade['id_unidade']?>"><?php echo $unidade['nm_unidade']?></option>
		<?php } ?>
		</select>
	</td>
</tr>
</table>
<br>
<p>Os campos sinalizados com <img src='./imagens/obriga.gif' /> são de preenchimento obrigatório</p>
<hr>
<p><input type="submit" name="btn_incluir" id="btn_incluir" class="botao" Value="Cadastrar">&nbsp;<input type="reset" name="btn_limpar" class="botao" Value="Limpar"></p>
</form>z
<script type="text/javascript">

    var frm_usuario = new Validation('frm_usuario');

    function submitForm(){
	  if(frm_usuario.validate()){
		$('frm_usuario').request({
				method: 'post',
				onComplete: function(transport){
					//alert(transport.responseText);
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


/*
 * Validar CEP
 */

function cep(element){
    var value = "";

    value = element.value.replace(/\D/gi, "");
    value = value.replace(/^(\d{5})/, "$1-");
    element.value = value;
}

/*
 * Funcoes Uteis
 */
function buscaCep(){
	new Ajax.Request("./templates/cep.php", {
		method: 'POST',
		parameters: 'cep='+$('nr_cep_logradouro').value,
		onLoading: Element.insert($('nr_cep_logradouro').name, {after:"<div id='load_"+$('nr_cep_logradouro').name+"' class='loading'><img src='./imagens/loader.gif' width='14' height='14'/></div>"}),
		onSuccess: function(transport){
					//alert(transport.responseText); exit;
					var xml = transport.responseXML;
			
					if(xml.getElementsByTagName('lib')[0].firstChild.data == 1){

						//$('nr_cep_logradouro').value = xml.getElementsByTagName('nr_cep_logradouro')[0].firstChild.data;
						$('nm_logradouro').value = xml.getElementsByTagName('nm_logradouro')[0].firstChild.data;
						$('nm_bairro').value = xml.getElementsByTagName('nm_bairro')[0].firstChild.data;
						$('nm_cidade').value = xml.getElementsByTagName('nm_cidade')[0].firstChild.data;
						$('id_estado').value = xml.getElementsByTagName('id_estado')[0].firstChild.data;

					}else{
						//$('nr_cep_logradouro').value = "";
						$('nm_logradouro').value = "";
						$('nm_bairro').value = "";
						$('nm_cidade').value = "";
						$('id_estado').value = "";
						
					}
					Element.remove($('load_'+$('nr_cep_logradouro').name));
				}
	});
}

	function consultaCpf(campo){ //alert('consulta cpf arquivo cadastro_usuario.php');alert(campo.value);
		if(campo.value != ""){//alert('entra qui');
			var valor = campo.value; //alert(valor);
			new Ajax.Request("./modulos/administrativo/cons_usuario.php", {
				method: 'POST',
				parameters: 'id='+campo.value,
				onLoading: Element.insert(campo.name, {after:"<div id='load_"+campo.name+"' class='loading'><img src='./imagens/loader.gif' width='14' height='14'/></div>"}),
				onSuccess: function(transport){
					//alert(transport.responseText); //exit;
					var xmldoc = transport.responseXML;
					//Se existir registro
					if(xmldoc.getElementsByTagName('lib')[0].firstChild.data == 1) {
						//alert($('frm_usuario').length);
						for(i=0; i < $('frm_usuario').length; i++){
							try{
								$('frm_usuario')[i].value = xmldoc.getElementsByTagName($('frm_usuario')[i].name)[0].firstChild.data;
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


	function consultaUsr(campo){//alert(flag);
		if(campo.value != ""){//alert('cons_usuario');
			var valor = campo.value;//alert(valor);
			new Ajax.Request("./modulos/administrativo/cons_usuario.php", {
				method: "POST",
				parameters: "nm="+campo.value,
				onLoading: Element.insert(campo.name, {after:"<div id='load_"+campo.name+"' class='loading'><img src='./imagens/loader.gif' width='14' height='14'/></div>"}),
				onSuccess: function(transport){
					//alert(transport.responseText); //exit;
					var xmldoc = transport.responseXML;
					//Se existir registro
					if(xmldoc.getElementsByTagName('lib')[0].firstChild.data == 1) {
						//alert($('frm_usuario').length);
						for(i=0; i < $('frm_usuario').length; i++){
							try{
								$('frm_usuario')[i].value = xmldoc.getElementsByTagName($('frm_usuario')[i].name)[0].firstChild.data;
							}catch(e){
								//alert(e);
							}
							$('hdn_acao').value = "alt";
							$('btn_incluir').value = "Alterar";
						}

					//Sen�o existir nenhum registro
					} else {
						//Limpar();
						campo.value = valor;
					}

					//Terminou o carregamento retira a imagem de load
					Element.remove($('load_'+campo.name));
				}
			});
		}
	}


	function Limpar(){
        $('frm_usuario').reset();
        frm_usuario.reset();
        $('hdn_acao').value = 'inc';
        $('btn_incluir').value = 'Cadastrar';
    }


</script>
