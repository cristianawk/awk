<?
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

	$flg = null;

	$colunas = null;
	$valores = null;
	$arrColunas = null;
	$arrValores = null;

	foreach($_POST AS $key => $valor){

		if($valor != ""){
			$id_empenho = $_POST['id_empenho'];
			$id_item_contratado = $_POST['id_item_contratado'];
			$acao = $_POST['acao'];
			
			if(formata::validaColuna($key, array('btn_incluir', 'btn_limpar', 'hdn_acao'))){

				if($_POST['acao'] == "Adicionar"){
					if($key != "acao") $arrColunas[] = $key;
					if($valor != "Adicionar") $arrValores[] = formata::formataValor($key, $valor);

				}else if($_POST['acao'] == "Alterar"){
					if($key != "acao") $arrColunas[] = "".$key."=".formata::formataValor($key, $valor);					
				}
			}
		}
	}

	$date = date("Y/m/d G:i:s");
	//$substr_count ( string $id_item_contratado , string "," [, int 0 [, int $id_item_contratado.lenght ]] )
	//echo 

	if($acao == ""){ // DELETA ITEMS
		$sql = "DELETE FROM items_contratados WHERE id_empenho = $id_empenho AND id_item_contratado in ($id_item_contratado)";
		$msg = "Os registros foram deletados com sucesso!!!";

	}else if($acao == "Adicionar"){ // ADICIONA ITEM
		$colunas = implode(", ", $arrColunas);//echo $colunas;
		$valores = implode(", ", $arrValores);//echo $valores;
		$sql = "INSERT INTO items_contratados ($colunas) VALUES ($valores)";
		$msg = "O novo registro foi inserido com sucesso!!!";
	
	}else if($acao == "Alterar"){ // ALTERA ITEM
		$colunas = implode(", ", $arrColunas);//echo $colunas;
		$sql = "UPDATE items_contratados set $colunas WHERE id_empenho = $id_empenho AND id_item_contratado = $id_item_contratado";
		$msg = "O registro foi alterado com sucesso!!!";
	}


	$conn = connection::init();
	$conn->query($sql);
	if($conn->get_status()==false){
		//echo ($conn->get_msg());$msg
		if($acao == ""){ // DELETA ITEMS
			$msg = "Aconteceu algum erro ao deletar os itens!";
		}else if($acao == "Adicionar"){ // ADICIONA ITEM
			$msg = "Aconteceu algum erro ao inserir o item!";
		}else if($acao == "Alterar"){ // ALTERA ITEM
			$msg = "Aconteceu algum erro na altera&ccedil;&atilde;o do item!";
		}
		$flg = 0;

	}else{
		//$msg = "O registro foi inserido com suscesso!!";
		$flg = 1;
	}

	connection::close();

?>

<head>
	<link rel="stylesheet" href="../../css/style.css" />

	<link rel="stylesheet" href="../../css/reveal.css" />

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

	<script type="text/javascript" src="../../js/jquery.reveal.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$('#modal').reveal({ // The item which will be opened with reveal
			  	animation: 'fade',                   // fade, fadeAndPop, none
				animationspeed: 450,                       // how fast animtions are
				closeonbackgroundclick: true,              // if you click background will modal close?
				dismissmodalclass: 'close'    // the class of a button or element that will close an open modal						
			});
		});
	</script>

	

</head>

<body>

	<div id="modal">
	
		<div id="content">

			<div id="heading"> <?echo $msg?> </div>

			<form target="_self" enctype="multipart/form-data" method="post" name="quebra_galho" id="quebra_galho" onsubmit="" " action="../../index.php?acesso=3&rotina=10">
				<input type="hidden" name="id_empenho" value="<?php echo $id_empenho?>">
				<table border="0" width="100%" align="center" class="orgTable" id="myTable"> </table>
				<p>
					<input type="submit" name="btn_incluir" id="btn_incluir" class="botao" Value="OK">
				</p>
			</form>
		</div>
	</div>

</body>
