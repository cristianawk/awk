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

$fornecedores = null;

if($_GET['fornecedor']){
    
    $fornecedor = $_GET['fornecedor'];
    $fornecedores = "WHERE tira_acentos(nm_fornecedor) LIKE tira_acentos('%$fornecedor%')";

}
    
    $sql = "SELECT * FROM fornecedores $fornecedores ORDER BY nm_fornecedor";
    
    //Classe de conex„o com banco de dados
    $conn = connection::init();
    $conn->query($sql);
    $fornecedores = $conn->get_tupla();
    connection::close();
    
    //print_r($fornecedores);

?>
<link href="../../css/audita.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/audita.js"></script>
<table class="orgTable" align="center">
    <tr class="cab">
        <th>&nbsp;</th>
        <th>CNPJ</th>
        <th>FORNECEDOR</th>
        <th>RESPONSÁVEL</th>
    </tr>
    <?php if($fornecedores){
            foreach($fornecedores AS $key => $forn){ ?>
        <tr class="lin">
            <th width="1%"><a href="#" onclick="javascript:campo_cnpj('<?php echo $forn['ds_cnpj']?>')" title="Seleciona o Fornecedor <?php echo $forn['nm_fornecedor']?>."><img src="../../imagens/combo.gif"></a></th>
            <td><?php echo $forn['ds_cnpj']?></td>
            <td><?php echo $forn['nm_fornecedor']?></td>
            <td><?php echo $forn['nm_responsavel']?></td>
        </tr>
    <?php      }
       } else { ?>
    <tr><th colspan="4" class="erro">Nenhum Fornecedor Encontrado</th></tr>
    <?php } ?>
</table>
<p align="center"><a href="#" onclick="javascript:fechar_janela()">FECHAR</a></p>
<script type="text/javascript">
function campo_cnpj(valor){
    parent.$('ds_cnpj').value = valor;
    parent.consultaCnpj(parent.$('ds_cnpj'));
    fechar_janela();
}

function fechar_janela(){
   parent.globalWin.hide();
}
</script>
