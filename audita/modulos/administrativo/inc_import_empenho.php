<?php
//header('Content-Type: text/html; charset=iso-8859-1');
/*
 * carrega a classe a ser instanciada quando chamada
 */
function __autoload($classe){
	$pontos = array("./", "../", "../../");
    foreach($pontos AS $ponto){
		if(file_exists("{$ponto}class/{$classe}.class.php")){
			//echo "class/{$classe}.class.php\n";
			include_once "{$ponto}class/{$classe}.class.php";
		}
	}
}
//Desabilita as mensagens de erro
error_reporting(0);
//Classe para le documentos .xls
include('excel_reader2.php');
/*
 *  Valida��o de Arquivo (.xls, .txt)
 */
function validarArquivo($arquivo, $arrExt){
		if(!in_array(strrchr(strtolower($arquivo), "."), $arrExt)){
			return '';
		}else{
			return strrchr(strtolower($arquivo), ".");
		}
	}

/*
 *  Fun��o que preenche com Zero (ZeroFill)
 */
function number_pad($number,$n) {
	if($number != ""){
		return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
	}else{
		return null;
	}
}

// Arquivos que ser�o permitidos
$extensoes = array(".xls", ".txt");
$arquivo = $_FILES['upload'];
// Recupera o nome do arquivo
$arquivo_nome = $arquivo['name'];
// Recupera o caminho temporario do arquivo no servidor
$arquivo_caminho = $arquivo['tmp_name'];

$ext = validarArquivo($arquivo['name'], $extensoes);
//echo $ext;
$posicao = 0;

if($ext == ".xls"){
	$posicao = 1;
//	$data = new Spreadsheet_Excel_Reader($arquivo_caminho,true,'CP1251');
	$data = new Spreadsheet_Excel_Reader($arquivo_caminho);

	$linhas = $data->rowcount();
	$colunas= $data->colcount();
//	echo $data->raw(2,10);exit;

	for($i = 2; $i <= $linhas; $i++){
		for($j = 1; $j <= $colunas; $j++){
			//$val = $data->raw($i, $j); // Assume we have a number.
			//if (!$val) $val = $data->val($i,$j); //if not a number, get value. 
			//$file[$i][$j] = $val;
			$pos = strpos($data->val($i,$j),'$R$');			
			if ($pos === false)
				$file[$i][$j] = $data->val($i,$j);
			else
				$file[$i][$j] = $data->raw($i,$j);
			
//			$file[$i][$j] = $data->val($i,$j);

		}
	}
//	echo "<pre>"; print_r($file); echo "</pre>"; exit;

}elseif($ext == ".txt"){
	$posicao = 0;
	$linhas = file($arquivo_caminho);
	foreach($linhas AS $linha){
		$file[] = explode(";", $linha);
	}


}else{
	echo "<b style='color: red'>ARQUIVO N�O V�LIDO</b>";
	exit;
}

$sqls = array();
$dados = array();
/*
echo strtolower($data->val(1,1));
if(strpos(strtolower($data->val(1,1)), "nome do produto") === false){
	echo "<b style='color: red'>ARQUIVO N�O V�LIDO --</b>";
	exit;
}
*/

//echo "<pre>"; print_r($file); echo "</pre>"; exit;
//Classe de conex�o com banco de dados
$conn = connection::init();

$conn->query("select id_produto, nm_produto from produtos");
while($tupla = $conn->fetch_row()) 
  $produto[$tupla['nm_produto']] = $tupla['id_produto'];
//echo "<pre>"; print_r($produto); echo "</pre>"; exit;

$conn->query("SELECT ds_cnpj,nm_fornecedor,id_fornecedor FROM fornecedores");
//$conn->query("SELECT ds_cnpj,id_fornecedor FROM fornecedores ORDER BY ds_cnpj");
while($tupla = $conn->fetch_row()) 
  $fornecedor[preg_replace("/\D/","", $tupla['ds_cnpj'])] = $tupla['id_fornecedor'];
//echo "<pre>"; print_r($fornecedor); echo "</pre>"; exit;

$conn->query("select  id_unidade,nm_unidade from unidades_beneficiadas");
while($tupla = $conn->fetch_row()) 
  $unidade_beneficiada[$tupla['nm_unidade']] = $tupla['id_unidade'];
//echo "<pre>"; print_r($unidade_beneficiada); echo "</pre>"; exit;


foreach($file AS $key => $linha){
if($fornecedor[preg_replace("/\D/","", $linha[7])] != ''){
	//echo "<pre>"; print_r($linha); echo "</pre>"; exit;
//	$vl_empenho = $linha[10];
//	$vl_empenho = substr($vl_empenho,strpos($vl_empenho,' '),strpos($vl_empenho,' ')-strlen($vl_emepenho)-1);
	$dados[$key][] =	$ds_empenho 			= formata::formataValor('ds_empenho', 			$linha[21]);
	$dados[$key][] =	$dt_empenho 			= "'".$linha[22]."'";
	$dados[$key][] =	$nr_items_contratados		= $linha[24];
	$dados[$key][] =	$nr_contrato 			= "'".preg_replace("/\D/","", $linha[5])."'";
//	$dados[$key][] =	$vl_empenho 			= formata::formataValor('vl_empenho', 			$vl_empenho);
	$dados[$key][] =	$vl_empenho 			= number_format($linha[10],3, '.', '');
	//$dados[$key][] =	$id_fornecedor 			= ($fornecedor[$linha[7]] == '') ? 'null' : $fornecedor[$linha[7]];//7 cnpj,8 nome
	$dados[$key][] =	$id_fornecedor 			= $fornecedor[preg_replace("/\D/","", $linha[7])];
	$dados[$key][] =	$id_unidade 			= ($unidade_beneficiada[$linha[11]] == '') ? '0' : $unidade_beneficiada[$linha[11]];
	$dados[$key][] =	$dt_contrato 			= "'".$linha[12]."'";
	$dados[$key][] =	$dt_inicio 			= "'".$linha[15]."'";
	$dados[$key][] =	$dt_final 			= "'".$linha[16]."'";
	$dados[$key][] =	$ch_contrato 			= formata::formataValor('$ch_contrato',			substr($linha[18],0,1));
	$dados[$key][] =	$ch_requisicao 			= formata::formataValor('$ch_requisicao',		substr($linha[19],0,1));
	$dados[$key][] =	$ds_cnpj_unidade_orcamentaria	= formata::formataValor('ds_cnpj_unidade_orcamentaria', $linha[17]);
	$dados[$key][] =	$dt_vigencia			= "'".$linha[16]."'";

      	$sqls[] = "INSERT INTO empenhos (ds_empenho,dt_empenho,nr_items_contratados,nr_contrato,vl_empenho,id_fornecedor,
                                         id_unidade,dt_contrato,dt_inicio,dt_final,ch_contrato,ch_requisicao,ds_cnpj_unidade_orcamentaria) 
		   VALUES ($ds_empenho,$dt_empenho,$nr_items_contratados,$nr_contrato,$vl_empenho,$id_fornecedor,
                                         $id_unidade,$dt_contrato,$dt_inicio,$dt_final,$ch_contrato,$ch_requisicao,$ds_cnpj_unidade_orcamentaria)";
	
	for ($i = 25; $i <= count($linha); $i=$i+3) {
	    if 	(($linha[$i] != '')||($linha[$i+1] != ''))
	    {
/*
		if (strpos($data->val(1,$i),',') > 0)
		{
			$id_produto = $produto[formata::fullUpper(substr($data->val(1,$i),0,strpos($data->val(1,$i),',')))];
		}else{
			$id_produto = $produto[formata::fullUpper($data->val(1,$i))];
		}
*/
		if($id_produto = $produto[formata::fullUpper($data->val(1,$i))]){
		$qt_item_contratado = $linha[$i];	
		$vl_item_contratado = $linha[$i+1];
		$sqls_ic[] = "insert into items_contratados (id_empenho,id_produto,id_unidade_medida,qt_item_contratado,vl_item_contratado,dt_vigencia)
			  values ((select id_empenho from empenhos where ds_empenho = $ds_empenho),$id_produto,0,$qt_item_contratado,$vl_item_contratado,$dt_vigencia)";
		}
	    } 
	}
}

}
//echo "<pre>"; print_r($dados); echo "</pre>"; exit;
//echo "<pre>"; print_r($sqls); echo "</pre>"; exit;
//echo "<pre>"; print_r($sqls_ic); echo "</pre>"; exit;

$erro = null;
$numero_registros = count($sqls)+count($sqls_ic);
$numero_inseridos = 0;
$numero_naoinseridos = 0;

foreach($sqls AS $sql){

	$conn->query($sql);
	if($conn->get_status()==false){
		//echo "STATUS: ".$conn->status."<br>";
		$erro[] = $conn->get_msg();
		$numero_naoinseridos++;
		$conn->set_status(true);
	}else{
		$numero_inseridos++;
	}
}

foreach($sqls_ic AS $sql){

	$conn->query($sql);
	if($conn->get_status()==false){
		$erro[] = $conn->get_msg();
		$numero_naoinseridos++;
		$conn->set_status(true);
	}else{
		$numero_inseridos++;
	}
}

echo "<p class='acerto'>Numero de Registros Encontrados: <b style='color: blue;'>$numero_registros</b></p>";
echo "<p class='acerto'>Numero de Registros Inseridos: <b style='color: green;'>$numero_inseridos</b></p>";
echo "<p class='erro'>Numero de Registros N�o Inseridos: <b style='color: red;'>$numero_naoinseridos</b></p>";
if($erro){
    echo "<p>ERROS ENCONTRADOS:</p>";
    foreach($erro AS $err){
        echo "<p>$err</p>";
    }
}

?>
