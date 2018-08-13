<?
require "../../lib/loader.php";
//echo "<pre>"; print_r($_GET); echo "</pre>"; exit;
$msg = null;
$flg = null;

	$filtro = $_GET['filtro'];
	//echo "filtro: ".$filtro;exit;
	$colunas = null;
	$valores = null;
	$arrColunas = null;
	$arrValores = null;

	if($_GET['hdn_acao'] == 'inc'){
	  foreach($_GET AS $key => $valor){
		if($valor != ""){//echo $key;
			if(formataColuna($key, $filtro)){
                    $arrColunas[] = $key;
                    $arrValores[] = formataValor($key, $valor);
			}
		}
	  }
	}else{
		foreach($_GET AS $key => $valor){
			if(formataColuna($key, $filtro)){
				if($key != 'id_mtr_usuario') $arrValores[] = " ".$key." = ".formataValor($key, $valor)." ";
			}
		}
    }


/*	foreach($_GET AS $key => $valor){
		if($valor != ""){//echo $key;
			if(formataColuna($key, $filtro)){
				if($_GET['hdn_acao'] == 'inc'){
                    $arrColunas[] = $key;
                    $arrValores[] = formataValor($key, $valor);
                }else{
                    if($key != 'id_mtr_usuario') $arrValores[] = " ".$key." = ".formataValor($key, $valor)." ";
                }
			}
		}
	}
	*/


if($_GET['hdn_acao'] == 'inc'){
	$colunas     = implode(", ", $arrColunas);//echo $colunas;
	$valores     = implode(", ", $arrValores);//echo $valores;
	//$valores = strtolower($val);
	$sql = "INSERT INTO ".TBL_USUARIO." ($colunas) VALUES ($valores) ";
}else{
    $valores     = implode(", ", $arrValores);//echo $valores;
    $sql = "UPDATE ".TBL_USUARIO." SET $valores WHERE id_mtr_usuario = ".$_GET['id_mtr_usuario'] ;
}
	//echo $sql; exit;
	$global_conn->query($sql);
	if($global_conn->get_status()==false){
		//echo ($global_conn->get_msg());
		$msg = "Aconteceu algum erro na inserção dos dados!!";
		$flg = 0;

	}else{
		if($_GET['hdn_acao'] == 'inc'){
            $msg = "O novo registro foi inserido com sucesso!";
        }else{
            $msg = "O registro foi alterado com sucesso!";
        }
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
