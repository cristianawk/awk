<?
require "../../lib/loader.php";
//echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
$msg = null;
$flg = null;

	$filtro = $_POST['filtro'];
	$id_empenho  = $_POST['id_empenho'];
	//echo "filtro: ".$filtro;exit;
	$colunas = null;
	$valores = null;
	$arrColunas = null;
	$arrValores = null;

	foreach($_POST AS $key => $valor){
		if($valor != ""){//echo $key;
			if(formataColuna($key, $filtro)){
				if(($_POST['id_empenho'] != "")&&($_POST['btn_incluir'] == 'ALTERAR')){
					if($key != "id_empenho") $arrColunas[] = "".$key."=".formataValor($key, $valor);

				}else{
					$arrColunas[] = $key;
					$arrValores[] = formataValor($key, $valor);
					if($key == "dt_empenho"){
    					$arrColunas[] = "ts_empenho";
    					$arrValores[] = formataValor($key, $valor);
					}

				}
			}
		}
	}

	if(($_POST['id_empenho'] != "")&&($_POST['btn_incluir'] == 'ALTERAR')){
		$colunas = implode(", ", $arrColunas);//echo $colunas;
		$sql = "UPDATE ".TBL_EMPENHO." SET $colunas WHERE id_empenho =".$id_empenho;
		$msg = "O registro foi alterado com sucesso!";
	}else{
		$colunas = implode(", ", $arrColunas);//echo $colunas;
		$valores     = implode(", ", $arrValores);//echo $valores;
		$sql = "INSERT INTO ".TBL_EMPENHO." ($colunas) VALUES ($valores) ";
		$msg = "O novo registro foi inserido com sucesso!";
	}

	//echo $sql; exit;
	//$msg = $sql;
	//$flg = 0;


	$global_conn->query($sql);
	if($global_conn->get_status()==false){
		//echo ($global_conn->get_msg());
		$msg = "Aconteceu algum erro na inserção/alteração dos dados!!";
		$flg = 0;

	}else{
		$flg = 1;
	}

	header("Content-type: application/xml");
	$xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
	$xml.= "<root>";
	$xml.= "<flg>$flg</flg>";
	$xml.= "<mesg>$msg</mesg>";
	$xml.="</root>";

	echo $xml;

	exit;
