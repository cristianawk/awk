<?php
require "../../lib/loader.php";
//echo "<pre>"; print_r($global_obj_sessao->is_mtr_in()); echo "</pre>";exit;

if($_GET['id_mtr_usuario']){
    $where = " AND id_mtr_usuario = ".$_GET['id_mtr_usuario'];
}elseif($_GET['nm_usuario']){
    $where = " AND tira_acentos(UPPER(nm_usuario)) LIKE tira_acentos('%".strtoupper($_GET['nm_usuario'])."%')";
}else{
    exit;
}

$usuarios = null;
$sql = "SELECT id_mtr_usuario, nm_usuario, nr_cpf_usuario, ds_email_usuario, id_unidade, nm_unidade, nm_posto FROM ".TBL_USUARIO."
        JOIN ".TBL_UNIDADE." USING (id_unidade)
        JOIN ".TBL_POSTO." USING (id_posto)
        WHERE id_banco IS NOT NULL AND ds_agencia IS NOT NULL AND ds_conta IS NOT NULL
        $where ORDER BY nm_usuario";
//echo $sql; exit;
$global_conn->query($sql);
while($tupla = $global_conn->fetch_row()) $usuarios[] = $tupla;
//echo "<pre>"; print_r($usuarios); echo "</pre>";exit;
?>
<script language="JavaScript" type="text/javascript" src="../../js/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="../../css/dlf.css">
<script type="text/javascript">
    function carregar_usuario(mtr, nome, cpf, email, unidade, posto){
        parent.$('id_mtr_gestor').value = mtr;
        parent.$('nm_usuario').value = nome;
        parent.$('nr_cpf_usuario').value = cpf;
        parent.$('ds_email_usuario').value = email;
        parent.$('nm_unidade').value = unidade;
        parent.$('nm_posto').value = posto;
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
    <th style="cursor:pointer;" onclick="carregar_usuario('<?=$usuario['id_mtr_usuario']?>', '<?=$usuario['nm_usuario']?>', '<?=$usuario['nr_cpf_usuario']?>', '<?=$usuario['ds_email_usuario']?>', '<?=$usuario['nm_unidade']?>', '<?=$usuario['nm_posto']?>')"><img src="../../imagens/combo.gif" title="Carregar Dados de <?=$usuario['nm_usuario']?>" /></th>
</tr>
<?
    }
   }else{ ?>
<tr class="erro"><th colspan="8">Nenhum usuário encontrado</th></tr>
<tr><th colspan="8">&nbsp;</th></tr>
<tr class="justificar"><th colspan="8">O usuário que procura não deve estar cadastrado no sistema.<br>Caso o usuário exista, verifique se não falta inserir os seus dados bancarios.</th></tr>
<? } ?>
</table>
<p align="center"><a href="javascript:fechar()">FECHAR</a></p>
