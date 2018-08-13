<?php
//echo "<pre>"; print_r($_GET); echo "</pre>";exit;
require "../../lib/loader.php";

if($_GET['id_mtr_usuario']){
    $where = " WHERE id_mtr_usuario = ".$_GET['id_mtr_usuario'];
}elseif($_GET['nm_usuario']){
    $where = " WHERE tira_acentos(UPPER(nm_usuario)) LIKE tira_acentos('%".strtoupper($_GET['nm_usuario'])."%')";
}else{
    $where = null;
}

$usuarios = null;
$sql = "SELECT id_mtr_usuario, nm_usuario,
        nr_cpf_usuario, ds_email_usuario, id_unidade,
        nm_unidade, nm_login, id_perfil,
        to_char(nr_telefone,'(00)0000-0000') AS nr_telefone,
        to_char(nr_celular,'(00)0000-0000') AS nr_celular, ds_agencia, ds_conta, id_banco, id_posto, nm_posto,
        ds_agencia_digito, ds_conta_digito
        FROM ".TBL_USUARIO."
        JOIN ".TBL_UNIDADE." USING (id_unidade)
        JOIN ".TBL_POSTO." USING (id_posto)
        $where ORDER BY nm_usuario";
//echo $sql; exit;
$global_conn->query($sql);
while($tupla = $global_conn->fetch_row()) $usuarios[] = $tupla;
//echo "<pre>"; print_r($usuarios); echo "</pre>";exit;
?>
<script language="JavaScript" type="text/javascript" src="../../js/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="../../css/dlf.css">
<script type="text/javascript">
    function carregar_usuario(obj_usuario){
        //alert(obj_usuario); exit;
        <? foreach($usuarios[0] AS $key => $v){
            if($key != 'nm_unidade'){
                if($key != 'nm_posto'){ ?>
                parent.$('<?=$key?>').value = obj_usuario.<?=$key?>;
        <?  } }
          } ?>
        parent.$('hdn_acao').value = 'alt';
        parent.$('btn_incluir').value = 'ALTERAR';
        parent.liberaBanco();
        fechar();

    }

    function fechar(){
        parent.globalWin.hide();
    }
</script>
<table align="center" border="0" cellpadding="2" cellspacing="2" width="95%" class="orgTable">
<tr class="head">
    <th>Matricula</th>
    <th>Nome</th>
    <th>Cpf</th>
    <th>Posto/Graduação</th>
    <th>Email</th>
    <th>Unidade Operacional</th>
    <th>&nbsp;</th>
</tr>
<? if($usuarios){
    foreach($usuarios AS $usuario){
        if($lin == "linA") $lin = "linB"; else $lin = "linA";
?>
<tr class="<?=$lin?>">
    <th><?=$usuario['id_mtr_usuario']?></th>
    <td><?=$usuario['nm_usuario']?></td>
    <td><?=$usuario['nr_cpf_usuario']?></td>
    <td><?=$usuario['nm_posto']?></td>
    <td><?=$usuario['ds_email_usuario']?></td>
    <td><?=$usuario['nm_unidade']?></td>
    <th style="cursor:pointer;" onclick='carregar_usuario(<?=JSONEncoder($usuario)?>)'/><img src="../../imagens/combo.gif" title="Carregar Dados de <?=$usuario['nm_usuario']?>"/></th>
</tr>
<?
    }
   }else{ ?>
<tr class="erro"><th colspan="7">Nenhum registro encontrado</th></tr>
<? } ?>
</table>
<p align="center"><a href="javascript:fechar()">FECHAR</a></p>
