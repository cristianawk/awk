<?php
//echo "<pre>"; print_r($_GET); echo "</pre>";exit;
require "../../lib/loader.php";

    $conexao_ldap = ldap_connect(LDAP_SERVIDOR);

    if($conexao_ldap){

        $bind = ldap_bind($conexao_ldap);

        $search = ldap_search($conexao_ldap, "ou=Users,dc=cbm,dc=sc,dc=gov,dc=br", "cn=*".$_GET['nm_usuario']."*");

        $dados = ldap_get_entries($conexao_ldap, $search);

        //echo "<pre>"; print_r($dados); echo "</pre>"; exit;

        $usuarios = array();

        for ($i=0; $i<$dados["count"]; $i++){

            $usuarios[$i]['nm_usuario'] =  $dados[$i]["cn"][0];
            $usuarios[$i]['nm_login'] =  $dados[$i]["uid"][0];
            $usuarios[$i]['ds_email'] =  $dados[$i]["mail"][0];

        }

        ldap_close($conexao_ldap);

    }

    //ordena os registros
    array_multisort($usuarios);

    //echo "<pre>"; print_r($usuarios); echo "</pre>"; exit;
?>
<script language="JavaScript" type="text/javascript" src="../../js/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="../../css/dlf.css">
<script type="text/javascript">
    function carregar_usuario(obj_usuario){
        //alert(obj_usuario); exit;
        parent.$('nm_usuario').value = obj_usuario.nm_usuario;
        parent.$('nm_login').value = obj_usuario.nm_login;
        parent.$('ds_email_usuario').value = obj_usuario.ds_email;
        fechar();
    }

    function fechar(){
        parent.globalWin.hide();
    }
</script>
<div class="justificar">Atenção! O servidor de LDAP guarda dados como login, email e nome completo para facilitar o cadastro na base de empenhos.<br>
Essa consulta irá trazer poucos dados sobre o usuário. Não utilize essa consulta para realizar alterações de usuários na base de dados de Empenhos.</div>
<br>
<table align="center" border="0" cellpadding="2" cellspacing="2" width="95%" class="orgTable">
<tr class="head">
    <th>Nome</th>
    <th>Email</th>
    <th>Login</th>
    <th>&nbsp;</th>
</tr>
<? if($usuarios){
    foreach($usuarios AS $usuario){
        if($lin == "linA") $lin = "linB"; else $lin = "linA";
?>
<tr class="<?=$lin?>">
    <td><?=$usuario['nm_usuario']?></td>
    <td><?=$usuario['ds_email']?></td>
    <td><?=$usuario['nm_login']?></td>
    <th style="cursor:pointer;" onclick='carregar_usuario(<?=json_encode($usuario)?>)'/><img src="../../imagens/combo.gif" title="Carregar Dados de <?=$usuario['nm_usuario']?>"/></th>
</tr>
<?
    }
   }else{ ?>
<tr class="erro"><th colspan="7">Nenhum registro encontrado</th></tr>
<? } ?>
</table>
<p align="center"><a href="javascript:fechar()">FECHAR</a></p>
