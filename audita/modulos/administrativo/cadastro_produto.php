<?php
$conn->query("SELECT * FROM tipo_produto ORDER BY nm_tipo_produto");
$tipos = $conn->get_tupla();

?>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_produto" id="frm_produto" 
onsubmit="submitForm(); return false;" onreset="novoCodigo(); return true;" action="./modulos/administrativo/inc_produto.php">
<input type="hidden" name="hdn_acao" id="hdn_acao" value="inc">
<input type="hidden" name="id_produto" id="id_produto" value="">
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="2"><?php echo $modulo_arquivo['nm_rotina']?></th></tr>
<tr>
	<td width="20%">Código do Produto / Serviço</td>
	<td>
        <input type="text" name="cd_produto" id="cd_produto" value="" size="4" maxlength="4" class="required" 
		onblur="/*consultaCodigo(this)*/" onkeyup="validanum(this,0)" readOnly=true/>
        &nbsp;* Código gerado pelo Sistema (Somente Numeros)
    </td>
</tr>
<tr>
	<td>Tipo</td>
	<td>
		<select id="id_tipo" name="id_tipo" class="validate-selection">
		<option value="">---------------------</option>
		<?php foreach($tipos AS $tipo){ ?>
		<option value="<?php echo $tipo['id_tipo_produto']?>"><?php echo $tipo['nm_tipo_produto']?></option>
		<?php } ?>
		</select>
	</td>
</tr>
<tr>
	<td>Nome do Produto / Serviço</td>
	<td>
        <input type="text" name="nm_produto" id="nm_produto" value="" size="30" class="required"/>
        &nbsp;<input type="button" name="btn_cons" value="Consultar Produto" class="botao" onclick="buscarProduto($('nm_produto'))">
    </td>
</tr>
</table>
<br>
<p>Os campos sinalizados com <img src='./imagens/obriga.gif' /> são de preenchimento obrigatório</p>
<hr>
<p><input type="submit" name="btn_incluir" id="btn_incluir" class="botao" Value="Cadastrar">&nbsp;<input type="reset" name="btn_limpar" class="botao" Value="Limpar"></p>
</form>
<script type="text/javascript">

    var frm_produto = new Validation('frm_produto');

    function submitForm(){alet('0');
	  if(frm_produto.validate()){
		$('frm_produto').request({
				method: 'post',
				onComplete: function(transport){//alert('1');
					//alert(transport.responseText); exit;
					var xmldoc = transport.responseXML;//alert('2');
					//alert(xmldoc);
					var flg = xmldoc.getElementsByTagName('flg')[0].firstChild.data;//alert('3');
					//alert(flg);
					if(flg == 1){//alert('4');
						alert(xmldoc.getElementsByTagName('mesg')[0].firstChild.data);
						//novoCodigo();
					}else{
						alert(xmldoc.getElementsByTagName('mesg')[0].firstChild.data);
					}
				}
			});
	  }
		return false;
	}


	function consultaCodigo(campo){
		if(campo.value != ""){
			var valor = campo.value;
			new Ajax.Request("./modulos/administrativo/cons_produto.php", {
				method: "POST",
				parameters: "id="+campo.value,
				onLoading: Element.insert(campo.name, {after:"<div id='load_"+campo.name+"' class='loading'><img src='./imagens/loader.gif' width='14' height='14'/></div>"}),
				onSuccess: function(transport){
					//alert(transport.responseText);// exit;
					var xmldoc = transport.responseXML;
					//Se existir registro
					if(xmldoc.getElementsByTagName('lib')[0].firstChild.data == 1) {
						//alert($('frm_fornecedor').length);
						for(i=0; i < $('frm_produto').length; i++){
							try{
								$('frm_produto')[i].value = xmldoc.getElementsByTagName($('frm_produto')[i].name)[0].firstChild.data;
							}catch(e){
								//alert(e);
							}
							$('hdn_acao').value = "alt";
							$('btn_incluir').value = "Alterar";
						}

					//Sen�o existir nenhum registro
					} else {
						campo.value = valor;
					}

					//Terminou o carregamento retira a imagem de load
					Element.remove($('load_'+campo.name));
				}
			});
		}
	}

    function buscarProduto(element){

       //alert(globalWin);
       if(globalWin != ""){ globalWin.destroy(); }
            globalWin = new Window("pr", {
                className: "alphacube",
                width:largura,
                height:altura,
                title:"Lista de Produtos/Serviços",
                url: "./modulos/administrativo/lista_produtos.php?produto="+element.value,
                showEffectOptions: {duration:0.1},
                //destroyOnClose: true,
                minimizable: false
            });
            //globalWin.setDestroyOnClose();
            globalWin.showCenter(true);
    }


	function Limpar(){
        $('frm_produto').reset();
        frm_produto.reset();
        $('hdn_acao').value = 'inc';
        $('btn_incluir').value = 'Cadastrar';
    }

    /*
     * Funcao chamada para gerar um codigo randomico
     */
    novoCodigo();

    function novoCodigo(){//alert('novo codigo');
	Limpar();
        geraSequenciaProduto($('cd_produto'));
    }

</script>
