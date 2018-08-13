<?php
$conn->query("SELECT * FROM perfil ORDER BY nm_perfil");
$perfis = $conn->get_tupla();

$conn->query("SELECT modulos.id_modulos, nm_modulo, id_rotina, nm_rotina
				FROM rotinas JOIN modulos ON (rotinas.id_modulos=modulos.id_modulos)
				ORDER BY nm_modulo, id_rotina");
while($tupla = $conn->fetch_row()) $modulos_rotinas[$tupla['nm_modulo']][$tupla['nm_rotina']] = $tupla;

//echo "<pre>"; print_r($modulos_rotinas); echo "</pre>"; exit;

?>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_perfilamento" id="frm_perfilamento" onsubmit="submitForm(); return false;" onreset="" action="./modulos/administrativo/inc_perfilamento.php">
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="2"><?php echo $modulo_arquivo['nm_rotina']?></th></tr>
<tr><th colspan="2">&nbsp;</th></tr>
<tr>
	<td>PERFIL : &nbsp;
		<select name="id_perfil" id="id_perfil" onchange="loadPerfilamento()">
			<option value="">-----------------------------------------------</option>
		<?php foreach($perfis AS $perfil){ ?>
			<option value="<?php echo $perfil['id_perfil']?>"><?php echo $perfil['nm_perfil']?></option>
		<?php } ?>
		</select>
	</td>
</tr>
</table>
<hr>
<table border="0" width="100%" align="center" id="tabperfil">
<?php foreach($modulos_rotinas AS $modulo => $rotinas){ ?>
<tr><th colspan="6"><?php echo $modulo?></th></tr>
	<?php foreach($rotinas AS $rotina => $arr){ ?>
		<tr>
			<td id='hlist'><?php echo $rotina?></td>
			<td><input type="checkbox" name="ch_completo[<?php echo $arr['id_rotina']?>]" id="ch_completo[<?php echo $arr['id_rotina']?>]" onchange="loadCHK(this, '<?php echo $arr['id_rotina']?>')" value=""> Completo</td>
			<td><input type="checkbox" name="chk_perfil[<?php echo $arr['id_rotina']?>][ch_consultar]" id="chk_perfil[<?php echo $arr['id_rotina']?>][ch_consultar]" value="S"> Consultar</td>
			<td><input type="checkbox" name="chk_perfil[<?php echo $arr['id_rotina']?>][ch_inserir]" id="chk_perfil[<?php echo $arr['id_rotina']?>][ch_inserir]" value="S"> Inserir</td>
			<td><input type="checkbox" name="chk_perfil[<?php echo $arr['id_rotina']?>][ch_alterar]" id="chk_perfil[<?php echo $arr['id_rotina']?>][ch_alterar]" value="S"> Alterar</td>
			<td><input type="checkbox" name="chk_perfil[<?php echo $arr['id_rotina']?>][ch_excluir]" id="chk_perfil[<?php echo $arr['id_rotina']?>][ch_excluir]" value="S"> Excluir</td>
		</tr>
	<?php } ?>
<?php } ?>
</table>
<hr>
<p><input type="submit" name="btn_incluir" id="btn_incluir" class="botao" Value="Ok">&nbsp;<input type="reset" name="btn_limpar" class="botao" Value="Limpar"></p>
</form>
<script type="text/javascript">

	function submitForm(){
	  if($('id_perfil').value != ""){
			$('frm_perfilamento').request({
				method: 'post',
				onComplete: function(transport){
					//alert(transport.responseText); exit;
					var xmldoc = transport.responseXML;
					//alert(xmldoc);
					var flg = xmldoc.getElementsByTagName('flg')[0].firstChild.data;
					//alert(flg);
					if(flg == 1){
						alert(xmldoc.getElementsByTagName('mesg')[0].firstChild.data);
						$('frm_perfilamento').reset();
					}else{
						alert(xmldoc.getElementsByTagName('mesg')[0].firstChild.data);
					}
				}
			});
		}
	  return false;
	}


	function loadCHK(element, id_rot){

		if(element.checked){
			$('chk_perfil['+id_rot+'][ch_consultar]').checked = true;
			$('chk_perfil['+id_rot+'][ch_inserir]').checked = true;
			$('chk_perfil['+id_rot+'][ch_alterar]').checked = true;
			$('chk_perfil['+id_rot+'][ch_excluir]').checked = true;
		}else{
			$('chk_perfil['+id_rot+'][ch_consultar]').checked = false;
			$('chk_perfil['+id_rot+'][ch_inserir]').checked = false;
			$('chk_perfil['+id_rot+'][ch_alterar]').checked = false;
			$('chk_perfil['+id_rot+'][ch_excluir]').checked = false;
		}
	}


	function loadPerfilamento(){

		limparCHK();

		if($('id_perfil').value != ""){
			var valor = $('id_perfil').value;
			new Ajax.Request("./modulos/administrativo/cons_perfilamento.php", {
				method: "POST",
				parameters: { 'id' : $('id_perfil').value },
				onLoading: Element.insert($('id_perfil').name, {after:"<div id='load_"+$('id_perfil').name+"' class='loading'><img src='./imagens/loader.gif' width='14' height='14'/></div>"}),
				onSuccess: function(transport){
					//alert(transport.responseText); exit;
					var xmldoc = transport.responseXML;
					//Se existir registro
					if(xmldoc.getElementsByTagName('lib')[0].firstChild.data == 1) {

						var r = xmldoc.getElementsByTagName('registro');
						//alert(registro.length);
						for(i=0; i < r.length; i++){

							try{

								var id_rotina 		= r[i].getElementsByTagName('id_rotina')[0].firstChild.data;
								var id_modulo 		= r[i].getElementsByTagName('id_modulos')[0].firstChild.data;
								var ch_consultar 	= r[i].getElementsByTagName('ch_consultar')[0].firstChild.data;
								var ch_inserir 		= r[i].getElementsByTagName('ch_inserir')[0].firstChild.data;
								var ch_alterar 		= r[i].getElementsByTagName('ch_alterar')[0].firstChild.data;
								var ch_excluir 		= r[i].getElementsByTagName('ch_excluir')[0].firstChild.data;

								if(ch_consultar == 'S'){ $('chk_perfil['+id_rotina+'][ch_consultar]').checked = true; }else{ $('chk_perfil['+id_rotina+'][ch_consultar]').checked = false; }
								if(ch_inserir == 'S'){ $('chk_perfil['+id_rotina+'][ch_inserir]').checked = true; }else{ $('chk_perfil['+id_rotina+'][ch_inserir]').checked = false; }
								if(ch_alterar == 'S'){ $('chk_perfil['+id_rotina+'][ch_alterar]').checked = true; }else{ $('chk_perfil['+id_rotina+'][ch_alterar]').checked = false; }
								if(ch_excluir == 'S'){ $('chk_perfil['+id_rotina+'][ch_excluir]').checked = true; }else{ $('chk_perfil['+id_rotina+'][ch_excluir]').checked = false; }
								if(ch_consultar == 'S' && ch_inserir == 'S' && ch_alterar == 'S' && ch_excluir == 'S'){
									$('ch_completo['+id_rotina+']').checked = true;
								} else {
									$('ch_completo['+id_rotina+']').checked = false;
								}



							}catch(e){
								//alert(e);
							}

						}

					//Sen�o existir nenhum registro
					}

					//Terminou o carregamento retira a imagem de load
					Element.remove($('load_'+$('id_perfil').name));
				}
			});
		}
	}

	function limparCHK(){
		for(n = 0; n < $('frm_perfilamento').length; n++){
			if($('frm_perfilamento')[n].type == 'checkbox'){
				$('frm_perfilamento')[n].checked = false;
			}
		}
	}

</script>
