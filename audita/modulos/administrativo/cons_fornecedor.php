<?php
/*
 * carrega a classe a ser instanciada quando chamada
 */
// echo "<pre><br>post: "; print_r($_POST); echo "</pre>";
 
function __autoload($classe){
    if(file_exists("../../class/{$classe}.class.php")){
		//echo "class/{$classe}.class.php\n";
        include_once "../../class/{$classe}.class.php";
    }else{
		//echo "{$classe}\n";
	}
}
//$cpf = $_POST['id'];
$sql = "SELECT DISTINCT * FROM vw_fornecedores WHERE ds_cnpj ILIKE '".$_POST['id']."'";
/*$sql = 'SELECT "id_fornecedor","nm_fornecedor","ds_cnpj","nm_responsavel","ds_cpf","ds_email1","ds_email2","id_funcao",
"id_banco","ds_agencia","ds_agencia_dv","ds_conta","ds_conta_dv","nr_telefone1","nr_telefone2","id_endereco","nm_funcao",
"nm_banco","nm_logradouro","nm_bairro","nm_cidade","id_estado","nm_estado","nr_cep_logradouro" 
FROM "public"."vw_fornecedores" 
WHERE "ds_cnpj" = ''.$cpf.''';
*/
//echo $sql;
$conn = connection::init();
$conn->query($sql);
$dados = $conn->fetch_row();
connection::close();

if($dados){
	header("Content-type: application/xml");
	$xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
	$xml.= "<root>\n";
	$xml.= "<lib>1</lib>\n";
	foreach($dados AS $id => $ds){
		$xml.= "<{$id}>{$ds}</{$id}>\n";
	}
	$xml.="</root>\n";
}else{
	header("Content-type: application/xml");
	$xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
	$xml.= "<root>\n";
	$xml.= "<lib>0</lib>\n";
	$xml.= "</root>\n";
}

echo $xml;

exit;

?>
