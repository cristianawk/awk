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
	$msg = null;
	$flg = null;

	$colunas = null;
	$valores = null;
	$arrColunas = null;
	$arrValores = null;

	foreach($_POST AS $key => $valor){

		if($valor != ""){

			if(formata::validaColuna($key, array('btn_incluir', 'btn_limpar', 'hdn_acao', 'btn_cons'))){

				if(($_POST['id_produto'] != "")&&($_POST['hdn_acao'] == 'alt')){

					$id_produto = $_POST['id_produto'];
					if($key != "id_produto") $arrColunas[] = "".$key."=".formata::formataValor($key, $valor);

				}else{

					if($key != "cd_produto"){
						$arrColunas[] = $key;
						$arrValores[] = formata::formataValor($key, $valor);
						//echo $key." - ".$valor."\n";
					}
				}
			}
		}
	}

	if(($_POST['id_produto'] != "")&&($_POST['hdn_acao'] == 'alt')){
		$colunas = implode(", ", $arrColunas);//echo $colunas;
		$sql = "UPDATE produtos SET $colunas WHERE id_produto =".$id_produto;
		$msg = "O registro foi alterado com sucesso!";
	}else{
		$colunas = implode(", ", $arrColunas);//echo $colunas;
		$valores = implode(", ", $arrValores);//echo $valores;
		$sql = "INSERT INTO produtos ($colunas) VALUES ($valores) ";
		$msg = "O novo registro foi inserido com sucesso!";
	}

	$conn = connection::init();
	$conn->query($sql);
	if($conn->get_status()==false){
		//echo ($conn->get_msg());
		$msg = "Aconteceu algum erro na inserção/alteração dos dados!!";
		$flg = 0;

	}else{
		$msg = "O registro foi inserido/alterado com sucesso!!";
		$flg = 1;
	}

	connection::close();
	
	
	echo "<script type='text/javascript'>";
        echo "alert('$msg');top.location.href='../../index.php?acesso=3&rotina=2';";
	echo "</script>";


?>
