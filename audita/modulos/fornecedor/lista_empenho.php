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

foreach($_POST AS $key => $valor){
	if($valor != ""){
		$cnpj = $_POST['fornecededor_cnpj'];
		$password = $_POST['fornecededor_password'];
	}
}


$conn = connection::init();

$sql = "SELECT count(id_fornecedor) as cont, id_fornecedor, nm_fornecedor, nm_responsavel, ds_cnpj, ps_senha
	FROM fornecedores
	WHERE ds_cnpj like '$cnpj' and ps_senha like '$password'
	GROUP BY id_fornecedor, nm_fornecedor, nm_responsavel, ds_cnpj, ps_senha";

$conn->query($sql);
$num_row = $conn->num_rows();
$fornecedor = $conn->get_tupla();

if($num_row > 0){
	foreach($fornecedor as $forn){
		$id_fornecedor = $forn['id_fornecedor'];
		$nm_fornecedor = $forn['nm_fornecedor'];
		$ds_cnpj = $forn['ds_cnpj'];
	}

	$sql = "SELECT a.id_requisicao, ds_requisicao, to_char(dt_requisicao, 'DD/MM/YYYY') AS dt_requisicao,
			b.nr_notafiscal,
			(CASE WHEN c.ds_status_nota IS NULL THEN 'AGUARDA NOTA  FISCAL DO FORNECEDOR OU REGISTRO DE FORNECIMENTO'
			ELSE c.ds_status_nota END) AS ds_status_nota,
			u.nm_unidade
			FROM requisicoes AS a LEFT JOIN notas_fiscais AS b ON (a.id_requisicao=b.id_requisicao)
			LEFT JOIN status_notas AS c USING (id_status_nota)
			JOIN empenhos AS e ON (a.id_empenho=e.id_empenho)
			JOIN unidades_beneficiadas AS u ON (u.id_unidade=a.id_unidade AND u.id_unidade=e.id_unidade)
			WHERE e.id_fornecedor = $id_fornecedor ORDER BY 3 DESC";
	//echo $sql;
	$conn->query($sql);
	while($tupla = $conn->fetch_row()) $dados[] = $tupla;
	//echo "<pre>"; print_r($dados); echo "</pre>"; //exit;
}

?>

<link rel="stylesheet" href="../../css/style.css" />
<link href="../../css/audita.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/audita.js"></script>

<script type="text/javascript">

	var num_rows = "<?php echo $num_row; ?>" ;

	if(num_rows < 1){
		alert('Login ou senha errado, tente novamente.');
		location.href='../../index.php?acesso=2'
	}



</script>

</head>



<form target="_self" enctype="multipart/form-data" method="post" name="frm_emp" id="frm_emp" onsubmit="" action="">
<input type="hidden" name="id_requisicao" id="id_requisicao" value="">
</form>
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="2">SISTEMA DE AUDITORIA - TELA DO FORNECEDOR</th>
	<th><a href="../../index.php?acesso=2&logout=1"><img src='../../imagens/sair.jpg' height='40' width='40' title='Sair' ></a></th>
</tr>
<tr><th colspan="2">&nbsp;</th></tr>
<tr>
	<th width="48%" class="rig">CNPJ:</th>
	<td><?php echo $ds_cnpj?></td>
</tr>
<tr>
	<th class="rig">NOME DO FORNECEDOR:</th>
	<td><?php echo $nm_fornecedor?></td>
</tr>
</table>
<hr>
<table border="0" width="80%" align="center" class="orgTable">
<tr class="cab">
	<th width="12%">Nº Requisição</th>
	<th width="10%">Nota Fiscal</th>
	<th width="15%">Data Autorização</th>
	<th class="">Unidade</th>
	<th class="lef">Andamento</th>
	<th class="center">Arquivo</th>
	<th width="20%" class="center">status</th>
</tr>
<?php if($dados){
	foreach($dados AS $dado){
		$sqlleitura = "SELECT * FROM nota_status WHERE id_requisicao = ".$dado['id_requisicao'];
		//echo "<br>sql leitura: ".$sqlleitura;
		$qtde = pg_query($sqlleitura);
		//echo "<br>sql qtd: ".$qtde;
		if (pg_num_rows($qtde) == 0) {
			$leit_st = "Aguardando envio...";
		} else {
			$leit_st_d = pg_fetch_array($qtde);
			$data_ar = explode('-', $leit_st_d['data']);
			$dt_lido = $data_ar[2] . "/" . $data_ar[1] . "/" . $data_ar[0];
			if ($leit_st_d['status'] == 'lido') {
				$leit_st = "Arquivo lido no dia " . $dt_lido . ", às ".$leit_st_d['hora']." horas";
			} elseif ($leit_st_d['status'] == 'enviado') {
				$leit_st = "Arquivo enviado no dia " . $dt_lido . ", às ".$leit_st_d['hora']." horas";
			}
		}

		 
		?>
		<tr class='lin'><td class='cen'><?=$dado['ds_requisicao']?></td><td class='cen'><?=$dado['nr_notafiscal']?></td><td class='cen'><?=$dado['dt_requisicao']?>
				</td><td class='cen'><?=$dado['nm_unidade']?></td><td><?=$dado['ds_status_nota']?></td>
		      <td style='cursor:pointer;' onclick='Gera_pdf1(<?=$dado['id_requisicao']?>, "id_requisicao")' >
		      <img src='../../imagens/070.ico' height='12' width='12' title='Ordem da Requisição<?=$dado['ds_requisicao']?>' ></td><td class='cen'><?=$leit_st?></td></tr>
<?php	}

} else { ?>
 <tr><th colspan="6" class="erro">Nenhuma Ordem de Requisição Encontrada para esse Fornecedor</th></tr>
<?php } ?>
</table>
<p><a href="../../index.php?acesso=2&logout=1">SAIR</a></p>

<script type="text/javascript">





function Gera_pdf(valor, campo){

    alert(valor);
    if(globalWin != ""){ globalWin.destroy(); }
		globalWin = new Window("req", {
        className: "alphacube",
        width:largura,
        height:altura,
        title:"Ordem Requisição",
        url: "./modulos/fornecedor/gerar_nota_requisicao_fornecedor.php?id_requisicao="+valor,
        showEffectOptions: {duration:0.1},
        //destroyOnClose: true,
        minimizable: false
    });
    //globalWin.setDestroyOnClose();
    globalWin.showCenter(true);

}

function Gera_pdf1(valor, campo){
//alert('1');
//alert($('id_requisicao'));
	$('id_requisicao').value = valor;
	//alert('2');
	$('frm_emp').action = "./gerar_nota_requisicao_fornecedor.php"
	//alert($('id_requisicao').value );
	//alert('3');
	$('frm_emp').submit();
}

</script>


</body>
