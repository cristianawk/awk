<?php
	/*
	 * carrega a classe a ser instanciada quando chamada
	 */
	function __autoload($classe){
	    if(file_exists("../../class/{$classe}.class.php")){
			//echo "class/{$classe}.class.php\n";
		include_once "../../class/{$classe}.class.php";
	    }else{
			//echo "{$classe}\n";
		}
	}

	$conn = connection::init();

	$sql = "SELECT * FROM tipo_unidade_medida ORDER BY nm_unidade_medida";
	$conn->query($sql);
	$tipos_unidade_medida = $conn->get_tupla();
	//echo $sql;

	$sql = "SELECT * FROM produtos ORDER BY nm_produto";
	$conn->query($sql);
	$produtos = $conn->get_tupla();

	foreach($_POST AS $key => $valor){
		if($valor != ""){
			$id_empenho = $_POST['id_empenho'];
			$acao = $_POST['acao'];
			$id_item_contratado = $_POST['id_item_contratado'];
			$id_prod_alt = $_POST['id_prod_alt'];
			$nm_prod_alt = $_POST['nm_prod_alt'];
			$id_unidade_alt = $_POST['id_unidade_alt'];
			$nm_unidade_alt = $_POST['nm_unidade_alt'];
			$quant_alt = $_POST['quant_alt'];
			$valor_alt = $_POST['valor_alt'];
			$dt_vigencia = $_POST['dt_vigencia'];
		}
	}
	$dataAtual = date("d/m/Y");
?>

<head>
	<link rel="stylesheet" href="../../css/style.css" />
	<link rel="stylesheet" href="../../css/reveal.css" />
	<link rel="stylesheet" href="../../css/ui/jquery.ui.all.css">
	<link rel="stylesheet" href="../../css/ui/demos.css">

	<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery.reveal.js"></script>	
	<script type="text/javascript" src="../../js/jquery.validate.js" ></script>
	<script type="text/javascript" src="../../js/jquery.maskMoney.js" ></script>
	<script type="text/javascript" src="../../js/jquery.maskedinput.js" ></script>

	<script src="../../js/jquery.ui.core.js"></script>
	<script src="../../js/jquery.ui.widget.js"></script>
	<script src="../../js/jquery.ui.datepicker.js"></script>

	<script src="../../js/jquery.ui.datepicker-pt-BR.js"></script>

	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

	
	<script type="text/javascript">

		// MASCARAS		
		jQuery(function($){
			$("#vl_item_contratado").maskMoney();
			$("#dt_vigencia").mask("99/99/9999");
			$("#dt_vigencia2").mask("99/99/9999");
		});

		// VALIDADOR DE DATA		
		jQuery.validator.addMethod("brDate", function(value, element) { 
			 return (value=="") ? false : isDate(value);
		});
		
		function isDate(txtDate) {
		    var currVal = txtDate;
		    if (currVal == '')
		       return false;

		  var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
		  var dtArray = currVal.match(rxDatePattern);

		  //if (dtArray == null)
		      //return false;

		   var dtDay = dtArray[1];
		   var dtMonth = dtArray[3];
		   var dtYear = dtArray[5];

		  if (dtMonth < 1 || dtMonth > 12)
		      return false;
		  else if (dtDay < 1 || dtDay > 31)
		      return false;
		  else if ((dtMonth == 4 || dtMonth == 6 || dtMonth == 9 || dtMonth == 11) && dtDay == 31)
		      return false;
		  else if (dtMonth == 2) {
		      var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
		      if (dtDay > 29 || (dtDay == 29 && !isleap))
			  return false;
		  }

		  return true;
		}
	
		// VALIDADOR DE TODOS OS CAMPOS
		$(document).ready( function() {
			// FORM 1 - CADASTRO
			$("#frm_itens_empenho").validate({
				rules:{
				    id_produto:{
					required: true
				    },
				    id_unidade_medida:{
					required: true
				    },
				    qt_item_contratado:{
					required: true
				    },
				    vl_item_contratado:{
					required: true
				    },
				    dt_vigencia:{ 
					required: true,
					brDate : true 
				    }
				},
				messages:{
				    id_produto:{
					required: "Selecione o produto/servi&ccedilo",
				    },
				   id_unidade_medida:{
					required: "Selecione o tipo de unidade medida",
				    },
				    qt_item_contratado:{
					required: "Digite a quantidade de produtos/servi&ccedilos",
				    },
				    vl_item_contratado:{
					required: "Digite o valor unit&aacuterio do produto/servi&ccedilo",
				    },
				    dt_vigencia:{
					brDate: "Digite uma data v&aacutelida",
					required: "Digite a data",
				    }
				}
			});
			
			// FORM 2 - ALTERAÇÃO
			$("#frm_itens_empenho2").validate({
				rules:{
				    id_produto:{
					required: true
				    },
				    id_unidade_medida:{
					required: true
				    },
				    qt_item_contratado:{
					required: true
				    },
				    vl_item_contratado:{
					required: true
				    },
				    dt_vigencia:{
					required: true,
					brDate : true 
				    }
				},
				messages:{
				    id_produto:{
					required: "Selecione o produto/servi&ccedilo",
				    },
				   id_unidade_medida:{
					required: "Selecione o tipo de unidade medida",
				    },
				    qt_item_contratado:{
					required: "Digite a quantidade de produtos/servi&ccedilos",
				    },
				    vl_item_contratado:{
					required: "Digite o valor unit&aacuterio do produto/servi&ccedilo",
				    },
				    dt_vigencia:{
					brDate: "Digite uma data v&aacutelida",
					required: "Digite a data",
				    }
				}
			});
		});


	</script>

</head>

<body>

<script type="text/javascript">

	var acao = "<?php echo $acao; ?>" ;

	if (acao == 'Adicionar') {		// FORM 1 - ADICIONAR
		$(document).ready(function() {
		   	$('#modal').reveal({ // The item which will be opened with reveal
			  	animation: 'fade',                   // fade, fadeAndPop, none
				animationspeed: 450,                       // how fast animtions are
				closeonbackgroundclick: true,              // if you click background will modal close?
				dismissmodalclass: 'close'    // the class of a button or element that will close an open modal						
			});
		});
	} else if (acao == 'Alterar') {		// FORM 2 - ALTERAÇÃO
		$(document).ready(function() {
		   	$('#modal2').reveal({ // The item which will be opened with reveal
			  	animation: 'fade',                   // fade, fadeAndPop, none
				animationspeed: 450,                       // how fast animtions are
				closeonbackgroundclick: true,              // if you click background will modal close?
				dismissmodalclass: 'close'    // the class of a button or element that will close an open modal						
			});
		});
	}

	// LIMPAR FORMS
	function Limpar(){
		$('frm_itens_empenho').reset();
		frm_itens_empenho.reset();
		$('hdn_acao').value = 'inc';
		$('btn_incluir').value = 'Cadastrar';
	}

	// CALENDARIO
	$(function() {
		$( "#dt_vigencia" ).datepicker( $.datepicker.regional[ "pt-BR" ] );
		$( "#dt_vigencia2" ).datepicker( $.datepicker.regional[ "pt-BR" ] );
	});
	
	</script>
	
</script>

<div id="modal">
	
	<div id="content">

		<form target="_self" enctype="multipart/form-data" method="post" name="quebraGalho" id="quebraGalho" onsubmit="" action="../../index.php?acesso=3&rotina=10">
			<input type="hidden" name="id_empenho" value="<?php echo $id_empenho?>">
			<table border="0" width="100%" align="center" class="orgTable" id="myTable"> </table>
			<p>
				<input type="submit" class="button green close" Value="x">
			</p>
		</form>		

		<div id="heading"> Cadastro de Itens </div>

		<form target="_self" enctype="multipart/form-data" method="post" name="frm_itens_empenho" id="frm_itens_empenho" onsubmit="submitForm();" return="false;" onreset="Limpar(); return true;" action="./inc_items.php">
			<input type="hidden" name="acao" value="<?php echo $acao?>">
			<input type="hidden" name="id_empenho" value="<?php echo $id_empenho?>">
			<table border="0" width="100%" align="center" class="orgTable" id="myTable">
				<tr><th colspan="2">   </th></tr>

				<tr>
					<td width="20%">Produto/Servi&ccedilo</td>
					<td>
						<select id="id_produto" name="id_produto" class="required">
						<option value="">-------------------------------------------------</option>
						<?php foreach($produtos AS $produto){ ?>
						<option value="<?php echo $produto['id_produto']?>"><?php echo $produto['nm_produto']?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Tipo Unidade Medida</td>
					<td>
						<select id="id_unidade_medida" name="id_unidade_medida" class="required">
						<option value="">-------------------------------------------------</option>
						<?php foreach($tipos_unidade_medida AS $tipo_unidade_medida){ ?>
						<option value="<?php echo $tipo_unidade_medida['id_unidade_medida']?>"><?php echo $tipo_unidade_medida['nm_unidade_medida']?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Quantidade Contratada</td>
					<td><input type="text" name="qt_item_contratado" id="qt_item_contratado" value="" size="9" maxlength="9" class="required"></td>
				</tr>
				<tr>
					<td>Valor Unit&aacuterio Produto/Servi&ccedilo</td>
					<td><input type="text" name="vl_item_contratado" id="vl_item_contratado" value="" size="40" class="required" /></td>
				</tr>
				<tr>
					<td>Data Vig&ecircncia</td>
					<td><input type="text" name="dt_vigencia" id="dt_vigencia" size="30px" maxlength="10" class="datepicker"  value="<?php echo $dataAtual?>"/></td>
				</tr>

			</table>

			<br>
			<p>Todos os campos s&atildeo de preenchimento obrigat&oacuterio</p>
			<hr>
			<p>
				<input type="submit" name="btn_incluir" id="btn_incluir" class="botao" Value="Cadastrar">&nbsp;
				<input type="reset" name="btn_limpar" class="botao" Value="Limpar">
			</p>

		</form>
	</div>
</div>


<div id="modal2">

	<div id="content">

		<form target="_self" enctype="multipart/form-data" method="post" name="quebraGalho" id="quebraGalho" onsubmit="" action="../../index.php?acesso=3&rotina=10">
			<input type="hidden" name="id_empenho" value="<?php echo $id_empenho?>">
			<table border="0" width="100%" align="center" class="orgTable" id="myTable"> </table>
			<p>
				<input type="submit" class="button green close" Value="x">
			</p>
		</form>

		<div id="heading"> Altera&ccedil&atildeo de Itens </div>

		<form target="_self" enctype="multipart/form-data" method="post" name="frm_itens_empenho2" id="frm_itens_empenho2" onsubmit="submitForm();" return="false;" onreset="Limpar(); return true;" action="./inc_items.php">
			<input type="hidden" name="acao" value="<?php echo $acao?>">
			<input type="hidden" name="id_empenho" value="<?php echo $id_empenho?>">
			<input type="hidden" name="id_item_contratado" value="<?php echo $id_item_contratado?>">
			<table border="0" width="100%" align="center" class="orgTable" id="myTable">
				<tr><th colspan="2">   </th></tr>

				<tr>
					<td width="20%">Produto/Servi&ccedilo</td>
					<td>
						<select id="id_produto" name="id_produto" class="required">
						<option value=<?php echo $id_prod_alt?>><?php echo $nm_prod_alt?></option>
						<?php foreach($produtos AS $produto){ ?>
						<option value="<?php echo $produto['id_produto']?>"><?php echo $produto['nm_produto']?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Tipo Unidade Medida</td>
					<td>
						<select id="id_unidade_medida" name="id_unidade_medida" class="required">
						<option value=<?php echo $id_unidade_alt?>><?php echo $nm_unidade_alt?></option>
						<?php foreach($tipos_unidade_medida AS $tipo_unidade_medida){ ?>
						<option value="<?php echo $tipo_unidade_medida['id_unidade_medida']?>"><?php echo $tipo_unidade_medida['nm_unidade_medida']?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Quantidade Contratada</td>
					<td><input type="text" name="qt_item_contratado" id="qt_item_contratado" value=<?php echo $quant_alt?> size="9" maxlength="9" class="required"></td>
				</tr>
				<tr>
					<td>Valor Unit&aacuterio Produto/Servi&ccedilo</td>
					<td><input type="text" name="vl_item_contratado" id="vl_item_contratado" value=<?php echo $valor_alt?> size="40" class="required" /></td>
				</tr>
				<tr>
					<td>Data Vig&ecircncia</td>
					<td><input type="text" name="dt_vigencia" id="dt_vigencia2" value="<?php echo $dt_vigencia?>" size="30px" maxlength="10" class="datepicker" /></td>
				</tr>

			</table>

			<br>
			<p>Todos os campos s&atildeo de preenchimento obrigat&oacuterio</p>
			<hr>
			<p>
				<input type="submit" name="btn_incluir" id="btn_incluir" class="botao" Value="Alterar">&nbsp;
				<input type="reset" name="btn_limpar" class="botao" Value="Voltar Itens">
			</p>

		</form>
	</div>
</div>



</body>
