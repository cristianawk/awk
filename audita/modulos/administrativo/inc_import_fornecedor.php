<?php
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
 *  Validação de Arquivo (.xls, .txt)
 */
function validarArquivo($arquivo, $arrExt){
		if(!in_array(strrchr(strtolower($arquivo), "."), $arrExt)){
			return '';
		}else{
			return strrchr(strtolower($arquivo), ".");
		}
	}

/*
 *  Função que preenche com Zero (ZeroFill)
 */
function number_pad($number,$n) {
	if($number != ""){
		return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
	}else{
		return null;
	}
}

//Function para definir a função do responsavel
function IdFuncao($string){

	if($string != ""){

		if(strpos($string, "procur") !== false){
			$ret = 1;
		}elseif(strpos($string, "admin") !== false){
			$ret = 3;
		}elseif(strpos($string, "diret") !== false){
			$ret = 2;
		}elseif(strpos($string, "sóci") !== false){
			$ret = 2;
		}elseif(strpos($string, "soci") !== false){
			$ret = 2;
		}elseif(strpos($string, "superv") !== false){
			$ret = 3;
		}elseif(strpos($string, "propriet") !== false){
			$ret = 3;
		}else{
			$ret = 'NULL';
		}

	}else{

		$ret = 'NULL';

	}

	return $ret;

}

//Classe de conexão com banco de dados
$conn = connection::init();
/*
 *  Consulta estados
 */
$conn->query("SELECT * FROM estados ORDER BY ds_uf");
while($tupla = $conn->fetch_row()) $estados[$tupla['ds_uf']] = $tupla['id_estado'];
//echo "<pre>"; print_r($estados); echo "</pre>"; exit;


// Arquivos que serão permitidos
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
	$data = new Spreadsheet_Excel_Reader($arquivo_caminho);

	$linhas = $data->rowcount();
	$colunas= $data->colcount();

	for($i = 1; $i <= $linhas; $i++){
		for($j = 1; $j <= $colunas; $j++){
			$file[$i][$j] = $data->val($i,$j);
		}
	}

}elseif($ext == ".txt"){
	$posicao = 0;
	$linhas = file($arquivo_caminho);
	foreach($linhas AS $linha){
		$file[] = explode(";", $linha);
	}


}else{
	echo "<b style='color: red'>ARQUIVO NÃO VÁLIDO</b>";
	exit;
}

$sqls = array();
$dados = array();

//echo "<pre>"; print_r($file); echo "</pre>"; exit;

if((strpos(strtolower($file[$posicao][$posicao]), "cnpj") or
   strpos(strtolower($file[$posicao][$posicao]), "cnpj/cpf") or
   strpos(strtolower($file[$posicao][$posicao]), "cpf"))  === false){
	echo "<b style='color: red'>ARQUIVO NÃO VÁLIDO</b>";
	exit;
}

foreach($file AS $key => $linha){

	if($key != $posicao){

		$pos = $posicao;

		$dados[$key][] =	$ds_cnpj 			= formata::formataValor('ds_cnpj', $linha[$pos]);
		$dados[$key][] =	$nm_fornecedor 		= formata::formataValor('nm_fornecedor', $linha[++$pos]);
		$dados[$key][] =	$nm_logradouro 		= formata::formataValor('nm_logradouro', $linha[++$pos]);
		$dados[$key][] =	$nm_bairro 			= formata::formataValor('nm_bairro', $linha[++$pos]);
		$dados[$key][] =	$nm_cidade 			= formata::formataValor('nm_cidade', $linha[++$pos]);
		$dados[$key][] =	$nr_cep_logradouro 	= formata::formataValor('nr_cep_logradouro', $linha[++$pos]);
		$dados[$key][] =	$id_estado 			= formata::formataValor('id_estado', $estados[$linha[++$pos]]);
//		$dados[$key][] =	$nr_telefone1 		= preg_replace("/[^0-9]/", "", format_phone($linha[++$pos]));
//		$dados[$key][] =	$nr_telefone2 		= preg_replace("/[^0-9]/", "", format_phone($linha[++$pos]));
		$dados[$key][] =	$nr_telefone1 		= formata::formataValor('nr_telefone1',$linha[++$pos]);
		$dados[$key][] =	$nr_telefone2 		= formata::formataValor('nr_telefone2',$linha[++$pos]);
		$dados[$key][] =	$nm_responsavel 	= formata::formataValor('nm_responsavel', $linha[++$pos]);
		$dados[$key][] =	$ds_cpf 			= formata::formataValor('ds_cpf', $linha[++$pos]);
		$dados[$key][] =	$id_funcao 			= IdFuncao(formata::fullLower($linha[++$pos]));
		$dados[$key][] =	$ds_email1 			= formata::formataValor('ds_email1', $linha[++$pos]);
		$dados[$key][] =	$ds_email2 			= formata::formataValor('ds_email2', $linha[++$pos]);
		$dados[$key][] =	$id_banco 			= formata::formataValor('id_banco', $linha[++$pos]);
		$aux_agencia   = 	explode("-", $linha[++$pos]);
		$dados[$key][] =	$ds_agencia 		= formata::formataValor('ds_agencia', number_pad($aux_agencia[0], 6));
		$dados[$key][] =	$ds_agencia_dv 		= formata::formataValor('ds_agencia_dv', $aux_agencia[1]);
		$aux_conta     = 	explode("-", $linha[++$pos]);
		$dados[$key][] =	$ds_conta 			= formata::formataValor('ds_conta', number_pad($aux_conta[0], 10));
		$dados[$key][] =	$ds_conta_dv 		= formata::formataValor('ds_conta_dv', $aux_conta[1]);

		if($nm_logradouro != 'NULL'){

		$sqls[] = "INSERT INTO vw_fornecedores (ds_cnpj, nm_fornecedor, nm_logradouro, nm_bairro,
					nm_cidade, nr_cep_logradouro, id_estado, nr_telefone1, nr_telefone2, nm_responsavel, ds_cpf,
					id_funcao, ds_email1, ds_email2, id_banco, ds_agencia, ds_agencia_dv, ds_conta, ds_conta_dv)
					VALUES ($ds_cnpj, $nm_fornecedor, $nm_logradouro, $nm_bairro, $nm_cidade, $nr_cep_logradouro,
					$id_estado, $nr_telefone1, $nr_telefone2, $nm_responsavel, $ds_cpf, $id_funcao, $ds_email1,
					$ds_email2, $id_banco, $ds_agencia, $ds_agencia_dv, $ds_conta, $ds_conta_dv)";

		}else{

		$sqls[] = "INSERT INTO vw_fornecedores (ds_cnpj, nm_fornecedor, nr_telefone1, nr_telefone2, nm_responsavel, ds_cpf,
					id_funcao, ds_email1, ds_email2, id_banco, ds_agencia, ds_agencia_dv, ds_conta, ds_conta_dv)
					VALUES ($ds_cnpj, $nm_fornecedor, $nr_telefone1, $nr_telefone2, $nm_responsavel, $ds_cpf, $id_funcao, $ds_email1,
					$ds_email2, $id_banco, $ds_agencia, $ds_agencia_dv, $ds_conta, $ds_conta_dv)";

		}

	}
}
//echo "<pre>"; print_r($dados); echo "</pre>"; exit;
//echo "<pre>"; print_r($sqls); echo "</pre>"; exit;

$erro = null;
$numero_registros = count($sqls);
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

echo "<p class='acerto'>Numero de Registros Encontrados: <b style='color: blue;'>$numero_registros</b></p>";
echo "<p class='acerto'>Numero de Registros Inseridos: <b style='color: green;'>$numero_inseridos</b></p>";
echo "<p class='erro'>Numero de Registros Não Inseridos: <b style='color: red;'>$numero_naoinseridos</b></p>";
if($erro){
    echo "<p>ERROS ENCONTRADOS:</p>";
    foreach($erro AS $err){
        echo "<p>$err</p>";
    }
}

?>
