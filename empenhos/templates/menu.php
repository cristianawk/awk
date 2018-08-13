 <?php

    if($perfil_usuario == 1){

        $menu['Geral']['P&aacute;gina inicial'] = '1';
        $menu['Geral']['Sair'] = 'logout';
        $menu['Cadastros']['Usuarios'] = '2';
        $menu['Cadastros']['Empenhos'] = '3';
        $menu['Cadastros']['Recebimento'] = '4';
        $menu['Relatórios']['Empenhos Emitidos']= '9';
        $menu['Relatórios']['Gestores Em Atraso']= '5';
        $menu['Relatórios']['Gestores Liberados']= '7';
        $menu['Relatórios']['Gestores por OBM']= '8';

    }elseif($perfil_usuario == 2){

        $menu['Geral']['P&aacute;gina inicial'] = '1';
        $menu['Geral']['Sair'] = 'logout';
        $menu['Relatórios']['Empenhos Emitidos']= '9';
        $menu['Relatórios']['Gestores Em Atraso']= '5';
        $menu['Relatórios']['Gestores Liberados']= '7';
        $menu['Relatórios']['Gestores por OBM']= '8';

    }elseif($perfil_usuario == 3){

        $menu['Geral']['P&aacute;gina inicial'] = '1';
        $menu['Geral']['Sair'] = 'logout';
        $menu['Cadastros']['Usuarios'] = '2';
        $menu['Cadastros']['Empenhos'] = '3';
        $menu['Cadastros']['Recebimento'] = '4';
        $menu['Relatórios']['Empenhos Emitidos']= '9';
        $menu['Relatórios']['Gestores Em Atraso']= '5';
        $menu['Relatórios']['Gestores Liberados']= '7';
        $menu['Relatórios']['Gestores por OBM']= '8';

    }elseif($perfil_usuario == 4){

        $menu['Geral']['P&aacute;gina inicial'] = '1';
        $menu['Geral']['Sair'] = 'logout';
        $menu['Cadastros']['Usuarios'] = '2';
        $menu['Cadastros']['Empenhos'] = '3';
        $menu['Cadastros']['Recebimento'] = '4';
        $menu['Relatórios']['Empenhos Emitidos']= '9';
        $menu['Relatórios']['Gestores Em Atraso']= '5';
        $menu['Relatórios']['Gestores Liberados']= '7';
        $menu['Relatórios']['Gestores por OBM']= '8';

    }elseif($perfil_usuario == 5){

        $menu['Geral']['P&aacute;gina inicial'] = '1';
        $menu['Geral']['Sair'] = 'logout';
        $menu['Relatórios']['Empenhos Emitidos']= '9';
        $menu['Relatórios']['Gestores Em Atraso']= '5';
        $menu['Relatórios']['Gestores Liberados']= '7';
        $menu['Relatórios']['Gestores por OBM']= '8';

    }

        //echo "<pre>"; print_r($menu); echo "</pre>";

?>
<script language="javascript" type="text/javascript">
    function submeter(op) {
        //alert(op);
        if(op){
            var frm = document.getElementById('frm_menu');
            document.getElementById('op_menu').value = op;
            frm.submit();
        }
    }
</script>
<form name="frm_menu" id="frm_menu" method="post" action="index.php" >
    <input type="hidden" name="op_menu" id="op_menu" value="">
</form>
<table align="center" width="100%" border="0"  cellspacing="1" cellpadding="1">
    <tr>
        <td>
            <ul class="menu2">
                <? if ($menu) foreach ($menu as $menu1 => $arr) if (!is_array($arr)) { ?>
                    <li class="top">
                        <a href="#" id="home" class="top_link"><span><?=$menu1?></span></a>
                    </li>
                <? } else { ?>
                    <li class="top">
                        <a href="#" class="top_link"><span class="down"><?=$menu1?></span></a>
                        <ul class="sub">
                            <? if (is_array($arr)) foreach ($arr as $menu2 => $arr2) if (!is_array($arr2)) { ?>
                                    <li><a onclick="submeter('<?=$arr2?>');" style="cursor:pointer"><?=$menu2?></a></li>
                            <? } else { ?>
                                <li><a href="#" class="fly"><?=$menu2?></a>
                                    <ul>
                                        <? if (is_array($arr2)) foreach ($arr2 as $menu3 => $valor) { ?>
                                            <li <? if ($valor) echo 'style="cursor:pointer"'; ?> >
                                                <a onclick="submeter('<?=$valor?>');"><?=$menu3?></a>
                                            </li>
                                        <? } ?>
                                    </ul>
                                </li>
                            <? } ?>
                        </ul>
                    </li>
                <? } ?>
            </ul>
        </td>
    </tr>
</table>
