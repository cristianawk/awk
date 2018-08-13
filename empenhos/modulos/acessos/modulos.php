<?

$msg = null;

if(($_POST['id_modulos'] != "")&&($_POST['nm_modulo'] != "")&&($_POST['nm_diretorio'] != "")){

	//echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
	$id_modulos = trim($_POST['id_modulos']);
	$nm_modulos = formataCampo($_POST['nm_modulo']);
	$nm_diretorio = "'".trim($_POST['nm_diretorio'])."'";
	$ch_ativacao = formataCampo($_POST['ch_ativacao']);

	$sql = "INSERT INTO ".TBL_MODULO." (id_modulos, nm_modulo, nm_diretorio, ch_ativacao) VALUES ($id_modulos, $nm_modulos, $nm_diretorio, $ch_ativacao)";
	//echo $sql; exit;
	$global_conn->query($sql);
	if($global_conn->get_status()==false){
		$msg =  $global_conn->get_msg();
	}else{
		$msg = "O NOVO REGISTRO FOI INSERIDO COM SUCESSO.";
	}

}

$modulos = null;
$sql = "SELECT id_modulos, nm_modulo, nm_diretorio, (CASE ch_ativacao WHEN 'S' THEN 'SIM' ELSE 'NÃO' END) AS ch_ativacao FROM ".TBL_MODULO." ORDER BY id_modulos";
$global_conn->query($sql);
//if($global_conn->get_status()==false) die ($global_conn->get_msg());
while($tupla = $global_conn->fetch_row()) $modulos[] = $tupla;

?>
<fieldset>
<legend>CADASTRO DE MODULOS</legend>
<form method="POST" action="index.php" target="" name="frm_modulos" id="frm_modulos">
<input type="hidden" name="op_menu" id="op_menu" value="<?=$id_load?>">
<table border="0" cellspacing="2" cellpadding="4" width="100%" class="orgTable">
<? if($msg != ""){ ?>
<tr><th colspan="2" class="erro"><?=$msg?></th></tr>
<? }else{ ?>
<tr><th colspan="2">&nbsp;</th></tr>
<? } ?>
<tr>
    <th>CODIGO DO MODULO:</th>
    <td><input type="text" name="id_modulos" id="id_modulos" value="" size="3" class="required"></td>
</tr>
<tr>
    <th>NOME DO MODULO:</th>
    <td><input type="text" name="nm_modulo" id="nm_modulo" value="" size="30" class="required"></td>
</tr>
<tr>
    <th>DIRETORIO DO MODULO:</th>
    <td><input type="text" name="nm_diretorio" id="nm_diretorio" value="./modulos/" size="30" class="required"></td>
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
        <input type="submit" name="btn_incluir" id="btn_incluir" value="INCLUIR" class="botao">
    </th>
</tr>
</table>
</form>
<br>
<hr>
<br>
<table border="0" cellspacing="0" cellpadding="4" width="100%">
<tr><th colspan="4" class="head">Modulos Cadastrados</th></tr>
<tr  class="cab">
    <th width="20%">Código Modulo</th>
    <th width="30%">Nome Modulo</th>
    <th width="30%">Nome Diretorio</th>
    <th width="20%">Ativado</th>
</tr>
<? if($modulos){
    foreach($modulos AS $modulo){
    if($lin == "linA") $lin = "linB"; else $lin = "linA";
    ?>
    <tr class="<?=$lin?>">
        <td align="center"><?=$modulo['id_modulos']?></td>
        <td align="center"><?=$modulo['nm_modulo']?></td>
        <td align="center"><?=$modulo['nm_diretorio']?></td>
        <td align="center"><?=$modulo['ch_ativacao']?></td>
    </tr>
    <? }
}?>
</table>
</fieldset>
<script type="text/javascript">
	var frm_modulos = new Validation('frm_modulos');
</script>

