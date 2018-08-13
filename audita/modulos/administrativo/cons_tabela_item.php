<?php
	//echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
	$sql = "SELECT a.id_empenho, a.ds_empenho, a.id_unidade, b.nm_unidade, a.dt_inicio,
			to_char(a.dt_empenho, 'DD/MM/YYYY') AS data_empenho, a.id_fornecedor, c.nm_fornecedor,
		nr_items_contratados, ch_bloqueio
			FROM empenhos AS a JOIN unidades_beneficiadas AS b USING (id_unidade)
			JOIN fornecedores AS c USING (id_fornecedor)
			WHERE id_empenho = ".$_POST['id_empenho'];
	//echo $sql; exit;
	$conn->query($sql);
	$dados = $conn->fetch_row();

	$sql = "SELECT * FROM tipo_unidade_medida ORDER BY nm_unidade_medida";
	$conn->query($sql);
	$tipos_unidade_medida = $conn->get_tupla();

	$sql = "SELECT * FROM produtos ORDER BY nm_produto";
	$conn->query($sql);
	$produtos = $conn->get_tupla();

	if($dados['ch_bloqueio'] == 'S'){
	    $edicao = 'false';
	} else {
	    $edicao = 'true';
	}

	//echo "<pre>"; print_r($dados); echo "</pre>"; exit;
?>

<script type="text/javascript">

	var edicao = "<?php echo $edicao; ?>" ;

</script>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Flexigrid</title>
	<link rel="stylesheet" type="text/css" href="css/flexigrid.pack.css" />

	<script type="text/javascript" src="http://code.jquery.com/jquery-1.5.2.min.js"></script>
	<script type="text/javascript" src="js/flexigrid.pack.js"></script>

	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>

<body>



<form target="_self" enctype="multipart/form-data" method="post" name="frm_item" id="frm_item" onsubmit="" onreset="" action="">
<input type="hidden" name="hdn_acao" id="hdn_acao" value="inc">
<input type="hidden" name="id_empenho" id="id_empenho" value="<?php echo $dados['id_empenho']?>">
<table border="0" width="" class="orgTableJanela">
<tr><th colspan="2"><?php echo $modulo_arquivo['nm_rotina']?></th></tr>
<tr>
	<td width="20%">NUMERO EMPENHO:</td>
	<td><?php echo $dados['ds_empenho']?></td>
</tr>
<tr>
	<td>NOME FORNECEDOR:</td>
	<td><?php echo $dados['nm_fornecedor']?></td>
</tr>
<tr>
	<td>UNIDADE BENEFICIADA:</td>
	<td><?php echo $dados['nm_unidade']?></td>
</tr>
<tr>
	<td>DATA DO EMPENHO:</td>
	<td><?php echo $dados['data_empenho']?></td>
</tr>
</table>
</form>



</pre>
	</div>
	<table class="flexme1" style="display: none"></table>

	<script type="text/javascript">

		$(".flexme1").flexigrid({
			url : './modulos/administrativo/cons_items-json.php?id=<?php echo $dados['id_empenho']?>',
			dataType : 'json',
			colModel : [ 
				{display: 'ID Item Contratado', name : 'id_item_contratado', width : 150, sortable : true, align: 'left', hide: true},
				{display: 'ID Produto/Servi&ccedilo', name : 'id_produto', width : 50, sortable : true, align: 'left', hide:true},				
				{display: 'Produto/Servi&ccedilo', name : 'nm_produto', width : 340, sortable : true, align: 'left'},
				{display: 'ID Unidade Medida', name : 'id_unidade_medida', width : 50, sortable : true, align: 'left', hide:true},
				{display: 'Tipo Unidade Medida', name : 'nm_unidade_medida', width : 120, sortable : true, align: 'left'},
				{display: 'Quantidade Contratada', name : 'qt_item_contratado', width : 120, sortable : true, align: 'right'},
				{display: 'Valor Unit&aacuterio', name : 'vl_item_contratado', width : 80, sortable : true, align: 'right'},
				{display: 'Quantidade Requerido', name : 'qt_produto', width : 120, sortable : true, align: 'right'},
				{display: 'Data de Vigencia', name : 'dt_vigencia', width : 120, sortable : true, align: 'right'}
			],


			buttons : [ 
				{ name : 'Adicionar', bclass : 'add', onpress : test }, 
				{ name : 'Deletar', bclass : 'delete', onpress : test }, 
				{ name : 'Alterar', bclass : 'alt', onpress : test },
				//{ separator : true } 
			],

			searchitems : [
				{display: 'Tipo Unidade Medida', name : 'nm_unidade_medida'},
				{display: 'Produto/Servi&ccedilo', name : 'nm_produto', isdefault: true}
			],

			sortname : "nm_produto",
			sortorder : "asc",
			usepager : true,
			title : 'Tabela de Cadastro/Altera&ccedil&atildeo de Itens',
			useRp : true,
			rp : 15,
			showTableToggleBtn : false,
			width : 990,
			height : 200,
			lef : 400
		
		});
 

		function test(com, grid) {	// DELETAR VALORES
			if (com == 'Deletar') {
				if (edicao == 'true'){
					 if($('.trSelected').length>0){
						var itemListStr ='';
						for(i=0;i<$('.trSelected').length;i++){ 
						                itemListStr += ("   ."+$('.trSelected :nth-child(3) div').eq(i).text().trim())+"\n"; 
						}
						itemListStr = (itemListStr.substring(0,(itemListStr.length - 1)));

						if(confirm('Tem certeza que deseja excluir esses items?\n' + itemListStr)){ 
						        var itemlist =''; 
						        for(i=0;i<$('.trSelected').length;i++){ 
						                itemlist+= ($('.trSelected :nth-child(1) div').eq(i).text().trim())+","; 
						        }
							itemlist = (itemlist.substring(0,(itemlist.length - 1)))
							//alert (itemlist);
							location.href='./modulos/administrativo/inc_items.php?id_item_contratado='+itemlist+'&id_empenho='+<?php echo $dados['id_empenho']?>;
						} 

					} else{ 
						alert('Selecione pelo menos uma linha para deletar.'); 
					} 
				}else
					alert('Voc&ecirc; n&atilde;o tem permiss&atilde;o');

			} else if (com == 'Adicionar') {	// INSERIR VALORES
				if (edicao == 'true'){
					location.href='./modulos/administrativo/cons_tabela_item_form.php?id_empenho='+<?php echo $dados['id_empenho']?>+'&acao='+com;
				   						
				}else
					alert('Você n&atildeo tem permiss&atildeo');

			}  else if (com == 'Alterar') {		// ALTERAR VALORES
				if (edicao == 'true'){
					 if($('.trSelected').length == 1){ 

							var id_item_contratado = $('.trSelected :nth-child(1) div').eq(0).text().trim(); 
							var id_prod_alt = $('.trSelected :nth-child(2) div').eq(0).text().trim();
							var nm_prod_alt = $('.trSelected :nth-child(3) div').eq(0).text().trim();
							var id_unidade_alt = $('.trSelected :nth-child(4) div').eq(0).text().trim();
							var nm_unidade_alt = $('.trSelected :nth-child(5) div').eq(0).text().trim();
							var quant_alt = $('.trSelected :nth-child(6) div').eq(0).text().trim();
							var valor_alt = $('.trSelected :nth-child(7) div').eq(0).text().trim();
							var dt_vigencia = $('.trSelected :nth-child(9) div').eq(0).text().trim();

							location.href='./modulos/administrativo/cons_tabela_item_form.php?id_empenho='+<?php echo $dados['id_empenho']?>+'&acao='+com+'&id_item_contratado='+id_item_contratado+'&id_prod_alt='+id_prod_alt+'&nm_prod_alt='+nm_prod_alt+'&id_unidade_alt='+id_unidade_alt+'&nm_unidade_alt='+nm_unidade_alt+'&quant_alt='+quant_alt+'&valor_alt='+valor_alt+'&dt_vigencia='+dt_vigencia;

					} else{ 
						alert('Selecione somente uma linha para alterar.'); 
					} 
				}else
					alert('Você n&atildeo tem permiss&atildeo');
			}
		}

		var frm_itens_empenho = new Validation('frm_usuario');
		
	</script>

</body>

