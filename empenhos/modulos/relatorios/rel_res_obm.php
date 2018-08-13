<?
// echo "<pre>"; print_r($_POST); echo "</pre>";
// echo "<pre>"; print_r($_GET); echo "</pre>";

require "../../lib/loader.php";

if($_POST["id_unidade"]){
    $where = " WHERE c.id_unidade = ".$_POST["id_unidade"];
}
    $sql = "SELECT DISTINCT a.nm_usuario, a.id_mtr_usuario, a.nr_cpf_usuario, c.id_unidade, c.nm_unidade, b.nm_banco, a.ds_agencia, a.ds_conta, d.nm_posto, ds_email_usuario,
            nr_telefone, nr_celular, ds_agencia_digito, ds_conta_digito
	    FROM ".TBL_BANCOS." AS b JOIN ".TBL_USUARIO." AS a ON (a.id_banco = b.id_banco)".
	    //" JOIN ".TBL_EMPENHO." AS e ON (a.id_mtr_usuario = e.id_mtr_gestor)".
	    "JOIN ".TBL_UNIDADE." AS c USING (id_unidade)
        JOIN ".TBL_POSTO." AS d USING (id_posto)
	    $where ORDER BY c.id_unidade, a.nm_usuario";
    //echo $sql; exit;
    $global_conn->query($sql);
    $num_total = $global_conn->num_rows();
    while($tupla = $global_conn->fetch_row()){
        $dados[$tupla['nm_unidade']][] = $tupla;
        $num[$tupla['nm_unidade']]++;
    }
    //echo "<pre>"; print_r($dados); echo "</pre>"; exit;
?>
<link rel="stylesheet" type="text/css" href="../../css/dlf.css">
<body>
<br>
<form target="_self" enctype="multipart/form-data" method="POST" name="frm_cons_geral_oco" action="">
<table align="center" cellpadding="7" cellspacing="1" border="0" width="95%" class="">
<tr class="head"><th colspan="2">LISTA DE GESTORES POR OBM</th></tr>
<tr class="linA"><th width="20%">TOTAL DE REGISTROS:</th><td align=""><?=$num_total?></td></tr>
</table>
<br>
<hr align="center" width="95%">
<br>
<? foreach($dados AS $unidade => $dado){ ?>
<table align="center" cellpadding="7" cellspacing="1" border="0" width="95%" class="orgTab">
    <tr class="cab"><th colspan="11"><?=$unidade?></th></tr>
    <tr class="head"><td colspan="11" id="left">Numero de Registros Encontrados:&nbsp;<?=$num[$unidade]?></td></tr>
    <tr class="cab">
        <th>Posto/Graduação</th>
        <th>Matrícula / Nome do Gestor</th>
        <th>CPF</th>
        <th>Email</th>
        <th>Telefone</th>
        <th>Celular</th>
        <th>Banco</th>
        <th>Agência</th>
        <th>Conta Corrente</th>
        <th>OBM</th>
    </tr>
<?
if($dado){
	foreach($dado AS $dado){ if($lin == "linA") $lin = "linB"; else $lin = "linA";?>
	<tr class="<?=$lin?>">
	<td id="left"><?=$dado['nm_posto']?></td>
	<td id="left"><?=$dado['id_mtr_usuario']?> - <?=$dado['nm_usuario']?></td>
        <td><?=$dado['nr_cpf_usuario']?></td>
        <td><?=$dado['ds_email_usuario']?></td>
        <td><?=$dado['nr_telefone']?></td>
        <td><?=$dado['nr_celular']?></td>
        <td><?=$dado['nm_banco']?></td>
		<td><?=$dado['ds_agencia']?><?if($dado['ds_agencia_digito'] != "") echo " - ".$dado['ds_agencia_digito']?></td>
        <td><?=$dado['ds_conta']?><?if($dado['ds_conta_digito'] != "") echo " - ".$dado['ds_conta_digito']?></td>
		<td><?=$dado['nm_unidade']?></td>
    </tr>
      <? } ?>
<?}else{?>
<tr><th colspan="11" class="erro">Nenhum registro Encontrado</th></tr>
<?}?>
<tr><th colspan="11">&#160;</th></tr>
</table>
<? } ?>
<p align="center"><a href="javascript:close();">FECHAR</a></p>
</body>
