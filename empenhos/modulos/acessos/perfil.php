<?


$perfis = null;
$sql = "SELECT id_perfil, nm_perfil, (CASE ch_ativacao WHEN 'S' THEN 'SIM' ELSE 'NÃO' END) AS ch_ativacao FROM ".TBL_PERFIL." ORDER BY id_perfil";
$global_conn->query($sql);
if($global_conn->get_status()==false) die ($global_conn->get_msg());
while($tupla = $global_conn->fetch_row()) $perfis[] = $tupla;

?>
<fieldset>
<legend>CADASTRO DE PERFIL</legend>
<form method="POST" action="" name="frm_perfil" id="frm_perfil">
<table border="0" cellspacing="2" cellpadding="4" align="center" width="100%" class="orgTable">
<tr>
    <th>CODIGO DO PERFIL:</th>
    <td><input type="text" name="id_perfil" id="id_perfil" value="" size="3" class="required"></td>
</tr>
<tr>
    <th>NOME DO PERFIL:</th>
    <td><input type="text" name="nm_perfil" id="nm_perfil" value="" size="30" class="required"></td>
</tr>
<tr>
    <th>ATIVAÇÃO:</th>
    <td>
        <select name="ch_ativacao" id="ch_ativacao">
            <option value="S">SIM</option>
            <option value="S">NÃO</option
        </select>
    </td>
</tr>
<tr><th colspan="2">&nbsp;</th></tr>
<tr>
    <th colspan="2">
        <input type="submit" name="btn_incluir" id="btn_incluir" value="INCLUIR">
    </th>
</tr>
</table>
</form>
<br>
<hr>
<br>
<table border="0" cellspacing="0" cellpadding="4" width="100%">
<tr><th colspan="4" class="head">Perfis Cadastrados</th></tr>
<tr  class="cab">
    <th width="20%">Código Perfil</th>
    <th width="60%">Nome Perfil</th>
    <th width="20%">Ativado</th>
</tr>
<? if($perfis){
    foreach($perfis AS $perfil){
    if($lin == "linA") $lin = "linB"; else $lin = "linA";
    ?>
    <tr class="<?=$lin?>">
        <td align="center"><?=$perfil['id_perfil']?></td>
        <td align="center"><?=$perfil['nm_perfil']?></td>
        <td align="center"><?=$perfil['ch_ativacao']?></td>
    </tr>
    <? }
}?>
</table>
</fieldset>
<script type="text/javascript">
	var frm_perfil = new Validation('frm_perfil');
</script>
