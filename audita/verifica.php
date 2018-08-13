<?php
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
$msg = "";
$lnk = 0;

if($_GET['h'] != ""){
    //Classe de conexão com banco de dados
    $conn = connection::init();
    $sql = sprintf("SELECT id, fornecedor_email.id_fornecedor, nm_fornecedor, ds_empenho, ds_cnpj, ch_visto, ds_email FROM fornecedor_email LEFT JOIN fornecedores USING (id_fornecedor) WHERE ds_codigo = '%s'", preg_replace("/\W/", "", $_GET['h']));
    $conn->query($sql);
    $tupla = $conn->fetch_row();
    $rows = $conn->num_rows();
    //print_r($tupla);
    if($rows > 0){
        if($tupla['ch_visto'] == 'N'){
            $sql = "UPDATE fornecedor_email SET ts_visto = now(), ch_visto = 'S' WHERE id = ".$tupla['id']." AND id_fornecedor = ".$tupla['id_fornecedor'];
            //echo $sql;
            $conn->query($sql);
            $msg = "ESSE EMAIL FOI VALIDADO COM SUCESSO";
            $lnk = 1;
        }else{
            $msg = "ESSE EMAIL JÁ FOI VALIDADO";
            $lnk = 1;
        }
    }else{
        $msg = "LINK INVÁLIDO";
        $lnk = 0;
    } 


}else{
    $msg = "LINK INVÁLIDO";
    $lnk = 0;
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CORPO DE BOMBEIROS MILITAR DE SANTA CATARINA - SISTEMA AUDITA COMPRAS</title>
<link href="css/audita.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<table cellspacing="0" cellpadding="0">
		<tr>
			<td colspan="2" id="top">
				<div>
					<h1><a href="index.php">CORPO DE BOMBEIROS MILITAR DE SANTA CATARINA<br>SISTEMA AUDITA COMPRAS</a></h1>
				</div>
			</td>
		</tr>
        <tr><td colspan="2">&nbsp;</td></tr>
		<tr>
            <td colspan="2" width="95%" align="center">
            <fieldset>
                <label><b>VERIFICAÇÃO</b></label>
                <hr/>
                <table border="0" width="100%" align="center" class="orgTableJanela">
                    <tr class="erro"><th colspan="2"><?php echo $msg?></th></tr>
                    <?php  if($lnk == 1){ ?>
                    <tr><th width="20%">FORNECEDOR</th><td><?php echo $tupla['nm_fornecedor']?></td></tr>
                    <tr><th width="20%">CNPJ</th><td><?php echo $tupla['ds_cnpj']?></td></tr>
                    <tr><th width="20%">NUNERO EMPENHO</th><td><?php echo $tupla['ds_empenho']?></td></tr>
                    <tr><th width="20%">DESCRIÇÃO EMAIL</th><td><?php echo$tupla['ds_email']?></td></tr>
                    <?php } ?>
                </table>
                <p align="center"><a href="index.php">INICIO DO AUDITA COMPRAS</a></p>
                <br>
            </fieldset>
            </td>
        <tr>
		<tr><td colspan="2">&nbsp;</td></tr>
</table>
<p align="center" id="pe">CBMSC</p>
</body>
</html>

