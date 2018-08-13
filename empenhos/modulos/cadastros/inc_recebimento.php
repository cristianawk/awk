<?php
require "../../lib/loader.php";
$msg = null;
$flg = null;

    //echo "<pre>"; print_r($_POST); echo "</pre>"; exit;

    $id_empenho	= $_POST['id_empenho'];
    $id_matricula = $_POST['id_matricula'];

    $sql = array();
    $erro = false;

	$sql[] = "BEGIN";

    if($_POST['dt_recebimento'] != ""){

        $dt_recebimento = formataCampo($_POST['dt_recebimento'], 'DT');

        $sql[] = "INSERT INTO ".TBL_EMP_RECEBIMENTO." (id_empenho, dt_recebimento, id_mtr_usuario) VALUES ($id_empenho, $dt_recebimento, $id_matricula)";

        $sql[] = "UPDATE ".TBL_EMPENHO." SET ts_empenho=$dt_recebimento WHERE id_empenho=$id_empenho";


    }elseif($_POST['dt_devolucao'] != ""){

        $dt_devolucao = formataCampo($_POST['dt_devolucao'], 'DT');
        $id_recebimento = $_POST['id_recebimento'];
        $arquivo = $_POST['arquivo'];

        $sql[] = "INSERT INTO ".TBL_EMP_DEVOLUCAO." (id_empenho, dt_devolucao, id_mtr_usuario, ds_arquivo, id_recebimento) VALUES ('$id_empenho', $dt_devolucao, $id_matricula, '$arquivo', $id_recebimento)";

        $sql[] = "UPDATE ".TBL_EMPENHO." SET ts_empenho=$dt_devolucao WHERE id_empenho=$id_empenho";


   }elseif($_POST['dt_aprovacao']!=""){

        $dt_aprovacao = formataCampo($_POST['dt_aprovacao'], 'DT');

        $sql[] = "UPDATE ".TBL_EMPENHO." SET dt_aprovacao = $dt_aprovacao, ts_empenho = $dt_aprovacao, cd_situacao_empenho = 'AP', id_mtr_usuario=$id_matricula WHERE id_empenho = '$id_empenho'";

    }elseif($_POST['dt_contabilidade']!=""){

        $dt_contabilidade = formataCampo($_POST['dt_contabilidade'], 'DT');

        $sql[] = "UPDATE ".TBL_EMPENHO." SET dt_contabilidade = $dt_contabilidade, ts_empenho = $dt_contabilidade, cd_situacao_empenho = 'PC', id_mtr_usuario=$id_matricula WHERE id_empenho = '$id_empenho'";

    }


	if($_POST['dt_entrega_tc28']!=""){

		$dt_entrega_tc28 = formataCampo($_POST['dt_entrega_tc28'], 'DT');

		$sql[] = "UPDATE ".TBL_EMPENHO." SET dt_entrega_tc28 = $dt_entrega_tc28, id_mtr_usuario=$id_matricula WHERE id_empenho = '$id_empenho'";
	}

	if($_POST['ch_enviar_email']!=""){

		$ch_enviar_email = formataCampo($_POST['ch_enviar_email']);

		$sql[] = "UPDATE ".TBL_EMPENHO." SET ch_enviar_email = $ch_enviar_email, id_mtr_usuario=$id_matricula WHERE id_empenho = '$id_empenho'";
	}


	//echo "<pre>"; print_r($sql); echo "</pre>"; exit;

	foreach($sql AS $s){
		$global_conn->query($s);
		if($global_conn->get_status()==false) $erro = true;
	}


	/*
	 * $msg = $sql;
	 * $flg = 0;
	 */

	if($erro){
		//echo ($global_conn->get_msg());
		$global_conn->query("ROLLBACK");
		$msg = "Aconteceu algum erro na inserção dos dados!!";
		$flg = 0;

	}else{
		$global_conn->query("COMMIT");
		$msg = "O novo registro foi inserido com sucesso!";
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

?>
