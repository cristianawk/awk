<?
require "../../lib/loader.php";

    //echo "<pre>"; print_r($_GET); echo "</pre>"; exit;

    $where = " WHERE ch_visualizar = 'S'";
    $limit = "LIMIT 10";

    switch($_GET['tipo']){

        case 'ds_empenho'       : $where .= " AND ds_empenho LIKE '%".strtolower($_GET['dados'])."%' "; $limit =null; break;
        case 'dt_empenho'       : $where .= " AND dt_empenho = '".$_GET['dados']."'";  $limit =null; break;
        case 'nm_usuario'       : $where .= " AND tira_acentos(UPPER(nm_usuario)) LIKE tira_acentos('%".utf8_decode(strtoupper($_GET['dados']))."%')";  $limit =null; break;
        case 'id_mtr_gestor'    : $where .= " AND id_mtr_gestor = '".$_GET['dados']."'";  $limit =null; break;
        case 'nr_cpf_usuario'   : $where .= " AND nr_cpf_usuario LIKE '%".$_GET['dados']."%'";  $limit =null; break;
        case 'ds_repasse'       : $where .= " AND ds_repasse = '".$_GET['dados']."'";  $limit =null; break;

    }

    $sql = "SELECT id_empenho, UPPER(ds_empenho) AS ds_empenho, to_char(dt_empenho, 'DD/MM/YYYY') AS dt_empenho,
            id_mtr_gestor, b.nm_usuario, ds_repasse, b.nr_cpf_usuario, c.nm_unidade, d.ds_situacao_empenho
            FROM ".TBL_EMPENHO."  AS a JOIN ".TBL_USUARIO." AS b ON (a.id_mtr_gestor=b.id_mtr_usuario)
            JOIN ".TBL_UNIDADE." AS c ON (c.id_unidade=b.id_unidade)
            JOIN ".TBL_TIPO_SITUACAO." AS d USING (cd_situacao_empenho)
            $where
            ORDER BY id_empenho DESC ";

 //echo $sql; exit;

 $global_conn->query($sql);
 while($tupla = $global_conn->fetch_row()) $dados[] = $tupla;

?>
<table border="0" cellspacing="0" cellpadding="4" width="100%" class="orgTable">
<tr class="head">
	<th width="15%">N. Empenho</th>
	<th width="15%">Data do Empenho</th>
	<th width="">Matricula / Nome do Gestor</th>
    <th width="">Valor Repassado</th>
    <th width="">Obm</th>
	<th width="15%">Situação</th>
	<th width="2%">&nbsp;</th>
</tr>
<? if($dados){
	foreach($dados AS $dados){ if($lin == "linA") $lin = "linB"; else $lin = "linA";?>
	<tr class="<?=$lin?>">
		<th><?=$dados['ds_empenho']?></th>
		<td><?=$dados['dt_empenho']?></td>
		<td><?=$dados['id_mtr_gestor']?> - <?=$dados['nm_usuario']?></td>
		<th><?=$dados['ds_repasse']?></th>
        <td><?=$dados['nm_unidade']?></td>
        <td><?=$dados['ds_situacao_empenho']?></td>
		<th style="cursor:pointer;" onclick="javascript:recebimento('<?=$dados['id_empenho']?>')"/><img src="./imagens/combo.gif"/></th>
	</tr>
<? }
 } else { ?>
	<tr class="erro"><th colspan="10">Nenhum Registro foi encontrado</th></tr>
 <?}?>
</table>
