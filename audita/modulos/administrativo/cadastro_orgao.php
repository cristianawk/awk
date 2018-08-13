<?php
$conn->query("SELECT * FROM estados ORDER BY ds_uf");
$estados = $conn->get_tupla();

?>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_orgao" id="frm_orgao" onsubmit="submitForm(); return false;" onreset="Limpar(); return true;" action="./modulos/administrativo/inc_orgaos.php">
<input type="hidden" name="hdn_acao" id="hdn_acao" value="inc">
<input type="hidden" name="id_unidade" id="id_unidade" value="">
<input type="hidden" name="id_endereco" id="id_endereco" value="">
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="2"><?php echo $modulo_arquivo['nm_rotina']?></th></tr>
<tr>
	<td width="20%">Nome da Unidade Beneficiária</td>
	<td><input type="text" name="nm_unidade" id="nm_unidade" class="required" value="" size="30" />
	&nbsp;<input type="button" name="btn_cons" value="Consultar Unidade" class="botao" onclick="buscarOrgao($('nm_unidade'))"></td>
</tr>
<tr>
	<td width="20%">Nome do Responsável</td>
	<td><input type="text" name="nm_ordenador" id="nm_ordenador" class="required" value="" size="40" /></td>
</tr>
<tr>
	<td width="20%">Cargo do Responsável</td>
	<td><input type="text" name="ds_cargo_ordenador" id="ds_cargo_ordenador" class="required" value="" size="35" /></td>
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
	<td>Telefone 1</td>
	<td><input type="text" name="nr_telefone_1" id="nr_telefone_1" value="" size="13" maxlength="13" onkeyup="telefone(this)" class="required"/></td>
</tr>
</tr>
	<td>Telefone 2</td>
	<td><input type="text" name="nr_telefone_2" id="nr_telefone_2" value="" size="13" maxlength="13" onkeyup="telefone(this)"/></td>
</tr>
<tr>
	<td>Email</td>
	<td><input type="text" name="ds_email" id="ds_email" value="" size="35"/></td>
</tr>
</table>
<br>
<p>Os campos sinalizados com <img src='./imagens/obriga.gif' /> são de preenchimento obrigatório</p>
<hr>
<p><input type="submit" name="btn_incluir" id="btn_incluir" class="botao" Value="Cadastrar">&nbsp;<input type="reset" name="btn_limpar" class="botao" Value="Limpar"></p>
</form>
<script type="text/javascript">

	var frm_orgao = new Validation('frm_orgao');

    function submitForm(){
	  if(frm_orgao.validate()){
		$('frm_orgao').request({
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

	function buscarOrgao(element){

       //alert(globalWin);
       if(globalWin != ""){ globalWin.destroy(); }
            globalWin = new Window("org", {
                className: "alphacube",
                width:largura,
                height:altura,
                title:"Lista de Unidades Beneficiadas",
                url: "./modulos/administrativo/cons_orgao.php?beneficiario="+element.value,
                showEffectOptions: {duration:0.1},
                //destroyOnClose: true,
                minimizable: false
            });
            globalWin.showCenter(true);
    }


	function Limpar(){
        $('frm_orgao').reset();
        frm_orgao.reset();
        $('hdn_acao').value = 'inc';
        $('btn_incluir').value = 'Cadastrar';
    }


</script>
