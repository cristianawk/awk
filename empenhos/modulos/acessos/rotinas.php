<?


$modulos = null;
$sql = "SELECT id_modulos, nm_modulo FROM ".TBL_MODULO." ORDER BY id_modulos";
$global_conn->query($sql);
if($global_conn->get_status()==false) die ($global_conn->get_msg());
while($tupla = $global_conn->fetch_row()) $modulos[] = $tupla;


$rotinas = null;
$sql = "SELECT id_rotina, a.id_modulos, nm_rotina, nm_fonte, (CASE a.ch_ativacao WHEN 'S' THEN 'SIM' ELSE 'NÃO' END) AS ch_ativacao, nm_modulo FROM ".TBL_ROTINA." AS a, ".TBL_MODULO." AS b WHERE a.id_modulos=b.id_modulos  ORDER BY id_rotina";
$global_conn->query($sql);
if($global_conn->get_status()==false) die ($global_conn->get_msg());
while($tupla = $global_conn->fetch_row()) $rotinas[] = $tupla;

?>
<fieldset>
<legend>CADASTRO DE ROTINAS</legend>
<form method="POST" action="" target="" name="frm_rotina" id="frm_rotina">
<table border="0" cellspacing="2" cellpadding="4" align="center" width="100%" class="orgTable">
<tr>
    <th>CODIGO DA ROTINA:</th>
    <td><input type="text" name="id_rotina" id="id_rotina" value="" size="3" class="required"></td>
</tr>
<tr>
    <th>NOME DA ROTINA:</th>
    <td><input type="text" name="nm_rotina" id="nm_rotina" value="" size="30"  class="required"></td>
</tr>
<tr>
    <th>NOME DO FONTE:</th>
    <td><input type="text" name="nm_fonte" id="nm_fonte" value="" size="30" class="required"></td>
</tr>
<tr>
    <th>MODULO DA ROTINA:</th>
    <td>
        <select name="id_modulos" id="id_modulos"  class="required" />
            <option value="">-----------------------</option>
        <? foreach($modulos AS $modulo){ ?>
            <option value="<?=$modulo['id_modulo']?>"><?=$modulo['nm_modulo']?></option>
        <? } ?>
        </select>
    </td>
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
        <input type="button" name="btn_incluir" id="btn_incluir" value="INCLUIR">
    </th>
</tr>
</table>
</form>
<br>
<hr>
<br>
<table border="0" cellspacing="0" cellpadding="4" width="100%">
<tr><th colspan="5" class="head">Rotinas Cadastrados</th></tr>
<tr  class="cab">
    <th width="20%">Código Rotina</th>
    <th width="25%">Nome Rotina</th>
    <th width="25%">Nome Fonte</th>
    <th width="20%">Modulo</th>
    <th width="10%">Ativado</th>
</tr>
<? if($rotinas){
    foreach($rotinas AS $rotina){
    if($lin == "linA") $lin = "linB"; else $lin = "linA";
    ?>
    <tr class="<?=$lin?>">
        <td align="center"><?=$rotina['id_rotina']?></td>
        <td align="center"><?=$rotina['nm_rotina']?></td>
        <td align="center"><?=$rotina['nm_fonte']?></td>
        <td align="center"><?=$rotina['nm_modulo']?></td>
        <td align="center"><?=$rotina['ch_ativacao']?></td>
    </tr>
    <? }
}?>
</table>
</fieldset>
<script type="text/javascript">
	var frm_perfil = new Validation('frm_rotina');
</script>