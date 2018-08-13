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
//echo "<pre>"; print_r($_GET); echo "</pre>"; exit;
$produtos = null;
$where = null;
    if($_GET['produto']){
	
		$produto = $_GET['produto'];
        $where = " AND tira_acentos(nm_produto) LIKE tira_acentos('%$produto%')";

    }else{

        $where = null;

    }

    $sql = "SELECT id_produto, cd_produto, nm_produto, nm_tipo_produto
            FROM produtos, tipo_produto 
			WHERE produtos.id_tipo=tipo_produto.id_tipo_produto
            $where ORDER BY cd_produto";

    //Classe de conexão com banco de dados
    $conn = connection::init();
    $conn->query($sql);
    $produtos = $conn->get_tupla();
    connection::close();

?>
<link href="../../css/audita.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/audita.js"></script>
<table class="orgTable" align="center">
    <tr class="cab">
        <th>&nbsp;</th>
        <th>Código</th>
        <th>Produto / Serviço</th>
        <th>TIPO</th>
    </tr>
    <?php if($produtos){
            foreach($produtos AS $key => $prod){ ?>
        <tr class="lin">
            <th width="1%"><a href="#" onclick="javascript:campo_codigo('<?php echo $prod['cd_produto']?>')"
			title="Seleciona o Produto/Serviço <?php echo $prod['nm_produto']?>."><img src="../../imagens/combo.gif"></a></th>
            <td><?php echo $prod['cd_produto']?></td>
            <td><?php echo $prod['nm_produto']?></td>
            <td><?php echo $prod['nm_tipo_produto']?></td>
        </tr>
    <?php      }
       } else { ?>
    <tr><th colspan="4" class="erro">Nenhum Produto/Serviço Encontrado</th></tr>
    <?php } ?>
</table>
<hr>
<p align="center"><a href="#" onclick="javascript:fechar_janela()">FECHAR</a></p>
<script type="text/javascript">
function campo_codigo(valor){//alert(valor);
    parent.$('cd_produto').value = valor;
    parent.consultaCodigo(parent.$('cd_produto'));
    fechar_janela();
}

function fechar_janela(){
   parent.globalWin.hide();
}
</script>
