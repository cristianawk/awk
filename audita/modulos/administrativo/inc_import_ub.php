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
//error_reporting(0);
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

// Arquivos que serão permitidos
$extensoes = array(".xls", ".txt");
$arquivo = $_FILES['upload'];
// Recupera o nome do arquivo
$arquivo_nome = $arquivo['name'] = "Dados_para_importacao.xls";
// Recupera o caminho temporario do arquivo no servidor
$arquivo_caminho = $arquivo['tmp_name'] = "/var/www/audita/modulos/administrativo/";

$ext = validarArquivo($arquivo['name'], $extensoes);
//echo $ext;
$posicao = 0;

if($ext == ".xls"){
	$posicao = 1;
	//$data = new Spreadsheet_Excel_Reader($arquivo_caminho);
	$data = new Spreadsheet_Excel_Reader("/var/www/audita/modulos/administrativo/Dados_para_importacao.xls");

	$linhas = $data->rowcount();
	//$colunas = $data->colcount();
	for($i = 0; $i <= $linhas; $i++){
		$file[$i] = $data->val($i,11);//coluna 11
	}		    
	sort($file);

	//echo "<pre>"; print_r($file); echo "</pre>"; exit;

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
/*
echo strtolower($data->val(1,1));
if(strpos(strtolower($data->val(1,1)), "nome do produto") === false){
	echo "<b style='color: red'>ARQUIVO NÃO VÁLIDO --</b>";
	exit;
}
*/

//echo "<pre>"; print_r($file); echo "</pre>"; exit;
//Classe de conexão com banco de dados
$conn = connection::init();

$aux = null;//auterado para não duplicar

foreach($file AS $key => $linha){

	if($key != $posicao){
        if($linha != ""){
	    if ($aux != $linha)
	    {
		$conta[0]++;
		$aux = $linha;
            	$sqls[] = "INSERT INTO unidades_beneficiadas (nm_unidade) VALUES ('$aux')";
	    }
        }

	}
}

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
