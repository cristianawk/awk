<?
$obms = null;
$sql = "SELECT id_unidade, nm_unidade FROM ".TBL_UNIDADE." ORDER BY id_unidade";
$global_conn->query($sql);
while($tupla = $global_conn->fetch_row()) $obms[] = $tupla;

?>
<body>
<form  target="_blank" enctype="multipart/form-data" onsubmit="return validaCampos();" method="post" name="frm_rel" id="frm_rel" action="./modulos/relatorios/rel_res_obm.php">
<input type="hidden" name="op_menu" id="op_menu" value="<?=$id_load?>">
<table width="100%" cellspacing="5" border="0" cellpadding="0" align="center">
	<tr>
                <td colspan="4">
                  <fieldset>
                    <legend>OBM</legend>
    <table width="100%" border="0">
           <tr>     <th>UNIDADE DO GESTOR:</th>
	      <td>
		  <select name="id_unidade" id="id_unidade" class="required" title="Unidade do Usuário">
		  <option value="">-------------------------------------</option>
		  <? foreach($obms AS $obm){ ?>
		  <option value="<?=$obm['id_unidade']?>"><?=$obm['nm_unidade']?></option>
		  <? } ?>
		  </select>
	      </td>
	  </tr>
	</table>
            </fieldset>
            </td>
	      </tr>
              <tr>
                  <td colspan="6" align="center"><br>
                        <input name="Button" type="submit" class="botao" value="Gerar Relat&oacute;rio">
                  </td>
               </tr>
         </tr>
</table>
</form>
<?if($_POST){?>
    <script language="javascript" type="text/javascript">//<!--
        var frm = document.frm_rel;
        frm.id_unidade.value = "<?=$_POST["id_unidade"]?>";
    </script>
<?}?>
</body>
