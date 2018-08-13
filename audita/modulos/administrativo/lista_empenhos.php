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
$empenhos = null;
$where = null;
    
    if($_GET['fornecedor']){ $where .= " AND fornecedores.id_fornecedor = ".$_GET['fornecedor']; }
    if($_GET['unidade']){ $where .= " AND unidades_beneficiadas.id_unidade = ".$_GET['unidade']; }
    
    $sql = "SELECT ds_empenho, to_char(dt_empenho, 'DD/MM/YYYY') AS dt_empenho, 
            nm_unidade, nm_fornecedor 
            FROM empenhos, unidades_beneficiadas, fornecedores 
            WHERE empenhos.id_unidade=unidades_beneficiadas.id_unidade 
            AND empenhos.id_fornecedor=fornecedores.id_fornecedor
             $where ORDER BY ds_empenho";
    
    //Classe de conexão com banco de dados
    $conn = connection::init();
    $conn->query($sql);
    $empenhos = $conn->get_tupla();
    connection::close();
    
    //echo "<pre>"; print_r($empenhos); echo "</pre>"; exit;
?>
<link href="../../css/audita.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<table class="orgTable" align="center">
    <tr class="cab">
        <th>&nbsp;</th>
        <th>Empenho</th>
        <th>Data do Empenho</th>
        <th>Fornecedor</th>
        <th>Unidade</th>
    </tr>
    <?php if($empenhos){
            foreach($empenhos AS $key => $emp){ ?>
        <tr class="lin">
            <th width="1%"><a href="#" onclick="javascript:campo_empenho('<?php echo $emp['ds_empenho']?>')"
			title="Seleciona o Empenho <?php echo $emp['ds_empenho']?>."><img src="../../imagens/combo.gif"></a></th>
            <td><?php echo $emp['ds_empenho']?></td>
            <td><?php echo $emp['dt_empenho']?></td>
            <td><?php echo $emp['nm_fornecedor']?></td>
            <td><?php echo $emp['nm_unidade']?></td>
        </tr>
    <?php      }
       } else { ?>
    <tr><th colspan="5" class="erro">Nenhum Empenho Encontrado</th></tr>
    <?php } ?>
</table>
<hr>
<p align="center"><a href="#" onclick="javascript:fechar_janela()">FECHAR</a></p>
<script type="text/javascript">
function campo_empenho(valor){
    parent.$('ds_empenho').value = valor;
    parent.consultaEmpenho(parent.$('ds_empenho'));
    fechar_janela();
}

function fechar_janela(){
   parent.globalWin.hide();
}
</script>
