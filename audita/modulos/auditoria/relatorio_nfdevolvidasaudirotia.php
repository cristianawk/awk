<?php header("Content-Type: text/html; charset=ISO-8859-1",true);
/*
 * carrega a classe a ser instanciada quando chamada
 */
function __autoload($classe){
	$pontos = array("./", "../", "../../");
    foreach($pontos AS $ponto){
		if(file_exists("{$ponto}class/{$classe}.class.php")){
			//echo "{$ponto}class/{$classe}.class.php\n";
			include_once "{$ponto}class/{$classe}.class.php";
		}
	}
}
//echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
$dados = null;
$where = null;
if($_POST['dt_inicial'] != "" && $_POST['dt_final'] != ""){

	$dt_inicial = formata::formataValor('dt_', $_POST['dt_inicial']);
	$dt_final = formata::formataValor('dt_', $_POST['dt_final']);

    $where .= " AND dt_notafiscal BETWEEN {$dt_inicial} AND {$dt_final} ";
}

if($_POST['id_fornecedor']){
    $where .= " AND f.id_fornecedor =".$_POST['id_fornecedor']." ";
}

	$sql = "select nf.nr_notafiscal,to_char(nf.dt_notafiscal, 'DD/MM/YYYY') AS data,nf.vl_notafiscal,qn.ds_questao
	from notas_fiscais nf
	inner join questoes_notas_fiscais qnf on (nf.id_notafiscal = qnf.id_notafiscal)
	inner join questoes_notas qn on (qnf.id_questao = qn.id_questao)
	where id_status_nota = 6 
	$where";
	//echo $sql; exit;
	$conn = connection::init();
	$conn->query($sql);
	$dados = $conn->get_tupla();
        connection::close();

	//echo "<pre>"; print_r($dados); echo "</pre>"; exit;
?>
<hr>
<table border="0" width="80%" align="center" class="orgTable">
<tr class="cab">
	<th>N° Nota Fiscal</th>
	<th>Data</th>
	<th>Valor</th>
	<th>Questão</th>
</tr>
<?php if($dados){
    foreach($dados AS $dado){ ?>
    <tr class="lin">
        <td align = center><?php echo $dado['nr_notafiscal']?></td>
        <td align = center><?php echo $dado['data']?></td>
        <td align = right><?php echo $dado['vl_notafiscal']?></td>
        <td align = left><?php echo $dado['ds_questao']?></td>
    </tr>
<?php } } else { ?>
<tr class="erro">
    <td colspan="6">Nenhuma Requisição Encontrada</td>
</tr>
<?php } ?>
</table>
