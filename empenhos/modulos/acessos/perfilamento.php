<?







$perfis = null;
$sql= "SELECT id_perfil, nm_perfil FROM ".TBL_PERFIL." ORDER BY nm_perfil ASC";
$global_conn->query($sql);
if($global_conn->get_status()==false) die ($global_conn->get_msg());
while($tupla = $global_conn->fetch_row()) $perfis[] = $tupla;

$perfilamentos = null;
$sql = "SELECT id_rotina, nm_rotina, nm_modulo FROM ".TBL_ROTINA." JOIN ".TBL_MODULO." USING(id_modulos) ORDER BY nm_modulo, nm_rotina ASC";
$global_conn->query($sql);
if($global_conn->get_status()==false) die ($global_conn->get_msg());
while($tupla = $global_conn->fetch_row()) $perfilamentos[] = $tupla;

//echo "<pre>"; print_r($perfilamentos); echo "</pre>"; exit;

?>
<script language="JavaScript" type="text/javascript">//<!--
function completa(id){

	if($('chk_completo['+id+']').checked == true){
		$('ch_consultar['+id+']').checked = true;
		$('ch_inserir['+id+']').checked = true;
		$('ch_alterar['+id+']').checked = true;
		$('ch_excluir['+id+']').checked = true;
    }else{
		$('ch_consultar['+id+']').checked = false;
		$('ch_inserir['+id+']').checked = false;
		$('ch_alterar['+id+']').checked = false;
		$('ch_excluir['+id+']').checked = false;
    }
}


</script>
<fieldset>
<legend>PERFILAMENTO</legend>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_perfilamento">
<input type="hidden" name="op_menu" id="op_menu" value="<?=$id_load?>">
<table border="0" cellspacing="0" cellpadding="0" width="100%" align="center" class="orgTable">
<tr>
	<th width="20%">PERFIS:</th>
	<td>
		<select name="id_perfil" id="id_perfil" onchange="">
		<option value="">--------------------------</option>
		<? foreach($perfis AS $perfil){ ?>
			<option value="<?=$perfil['id_perfil']?>"><?=$perfil['nm_perfil']?></option>
		<? } ?>
		</select>
	</td>
</tr>
</table>
<br><hr><br>
<table border="0" cellspacing="0" cellpadding="2" width="100%" align="center" class="orgTable">
<tr class="cab">
	<th width="15%">Modulos</th>
	<th width="35%">Rotinas</th>
	<th width="10%">Completo</th>
	<th width="10%">Consulta</th>
	<th width="10%">Inclusão</th>
	<th width="10%">Alteração</th>
	<th width="10%">Exclusão</th>
</tr>
<? foreach($perfilamentos AS $perfilamento){ ?>
<tr class="linA">
	<td align="center"><?=$perfilamento['nm_modulo']?></td>
	<td><? echo "&nbsp;".$perfilamento['id_rotina']." - ".$perfilamento['nm_rotina'];?></td>
	<th><input type="checkbox" name="chk_completo[<?=$perfilamento['id_rotina']?>]" id="chk_completo[<?=$perfilamento['id_rotina']?>]" onchange="completa('<?=$perfilamento['id_rotina']?>')"></th>
	<th><input type="checkbox" name="ch_consultar[<?=$perfilamento['id_rotina']?>]" id="ch_consultar[<?=$perfilamento['id_rotina']?>]" value="S"></th>
	<th><input type="checkbox" name="ch_inserir[<?=$perfilamento['id_rotina']?>]" id="ch_inserir[<?=$perfilamento['id_rotina']?>]" value="S"></th>
	<th><input type="checkbox" name="ch_alterar[<?=$perfilamento['id_rotina']?>]" id="ch_alterar[<?=$perfilamento['id_rotina']?>]" value="S"></th>
	<th><input type="checkbox" name="ch_excluir[<?=$perfilamento['id_rotina']?>]" id="ch_excluir[<?=$perfilamento['id_rotina']?>]" value="S"></th>
</tr>
<? } ?>
<tr><th colspan="7">&nbsp;</th></tr>
<tr>
    <th colspan="7">
        <input type="button" name="btn_incluir" id="btn_incluir" value="SALVAR" class="botao">
    </th>
</tr>
</table>
</form>
</fieldset>
