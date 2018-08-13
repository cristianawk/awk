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

$where = null;

if($_GET['beneficiario'] != ""){
	$where = " WHERE tira_acentos(nm_unidade) LIKE tira_acentos('%".$_GET['beneficiario']."%') ";
}
$sql = "SELECT * FROM vw_beneficiarios {$where} ORDER BY nm_unidade";
//echo $sql;
$conn = connection::init();
$conn->query($sql);
$dados = $conn->get_tupla();
connection::close();

//echo "<pre>"; print_r($dados); echo "</pre>"; exit;
?>
<link href="../../css/audita.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/audita.js"></script>
<table class="orgTable" align="center">
    <tr class="cab">
        <th>&nbsp;</th>
        <th>UNIDADE</th>
        <th>CIDADE</th>
        <th>RESPONSÁVEL</th>
    </tr>
    <?php if($dados){
            foreach($dados AS $key => $dado){ ?>
        <tr class="lin">
            <th width="1%"><a href="#" onclick='javascript:loadCampos(<?=formata::encodeJSON($dado)?>)'><img src="../../imagens/combo.gif"></a></th>
            <td><?php echo $dado['nm_unidade']?></td>
            <td><?php echo $dado['nm_cidade']?></td>
            <td><?php echo $dado['nm_ordenador']?></td>
        </tr>
    <?php      }
       } else { ?>
    <tr><th colspan="4" class="erro">Nenhum Fornecedor Encontrado</th></tr>
    <?php } ?>
</table>
<p align="center"><a href="#" onclick="javascript:fechar_janela()">FECHAR</a></p>
<script type="text/javascript">
function fechar_janela(){
   parent.globalWin.hide();
}

function loadCampos(obj){


	parent.$('id_unidade').value = obj.id_unidade;
    parent.$('nm_unidade').value = obj.nm_unidade;
    parent.$('nm_ordenador').value = obj.nm_ordenador;
    parent.$('ds_cargo_ordenador').value = obj.ds_cargo_ordenador;
    parent.$('nr_telefone_1').value = obj.nr_telefone_1;
    parent.$('nr_telefone_2').value = obj.nr_telefone_2;
    parent.$('ds_email').value = obj.ds_email;
    parent.$('id_endereco').value = obj.id_endereco;
    parent.$('nm_logradouro').value = obj.nm_logradouro;
    parent.$('nm_bairro').value = obj.nm_bairro;
    parent.$('nm_cidade').value = obj.nm_cidade;
    parent.$('id_estado').value = obj.id_estado;
    parent.$('nr_cep_logradouro').value = obj.nr_cep_logradouro;
    parent.$('hdn_acao').value = "alt";
	parent.$('btn_incluir').value = "Alterar";
	fechar_janela();

}
</script>

