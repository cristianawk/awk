<?php
	
/**
 * Corpo de Bombeiros Militar de Santa Catarina
 *
 * Projeto Sigat Sistema de Gerenciamento de Atividades Tecnicas
 *
 * @categoria  Classes
 * @pacote     HtmlPDF
 * @autor      Edson Orivaldo Lessa Junior <edsonagem@cb.sc.gov.br>
 * @creditos   Agem Informatica  
 * @versao     1.0
 * @data       14/07/2005 as 11:17:44
 * @atualiza   22/10/2005 as 15:45:29
 * @arquivo    lib/class/class_htmlpdf.php
 */

/** 
 * Agradeco pela ajuda, ao meu amigo Marcelo Cendron  
 */
 
// Requerer a classe PEAR para geracao de erros
require_once './class_pear_erro.php';

class HTML_ToPdf {

    /**
     * Trajeto do arquivo html
     * @var string
     */
    var $htmlFile = '';

    /**
     * Trajeto dos arquivos pdf   
     * @var string
     */
    var $pdfFile = '';

    /**
     * Definir diretorio temporario
     * @var string
     */
    var $tmpDir = '/tmp';

    /**
     * Mostrar debugacao do script PERL na tela 
     * @var booleano
     */
    var $debug = true;

    /**
     * Mostrar erros de html
     * @var booleano
     */
    var $htmlErrors = false;

    /**
     * Definir um dominio relativo as imagens 
     * @var string
     */
    var $defaultDomain = '';

    /**
     * Caminho para a execusao do script PERL "htmlpdf.pl"
     * @var string
     */
    var $html2psPath = '/usr/bin/html2ps';

    /**
     * Caminho para execusao do script SHELL "ps2pdf" 
     * @var string
     */
    var $ps2pdfPath = '/usr/bin/ps2pdf';

    /**
     * Caminho para execusao do "CURL" programa de get URL 
     * @var string
     */
    var $getUrlPath = '/usr/bin/local/curl -i';

    /**
     * Fazer uso de estilos CSS para gerar os 
     * arquivos pdf
     * @var booleano
     */
    var $useCSS = true;

    /**
     * Definindo outros tipo de estilo
     * @var string
     */
    var $additionalCSS = '';

    /**
     * Gerar os arquivos pdfs com cores?
     * @var booleano
     */
    var $pageInColor = true;

    /**
     * Gerar imagens de escala de cinza nos arquivos pdfs?
     * @var booleano
     */
    var $grayScale = false;

    /**
     * Fator de escala para as paginas
     * @var inteiro 
     */
    var $scaleFactor = 1;

    /**
     * Sublinhar o que e link
     * @var booleano 
     */
    var $underlineLinks = null;

    /**
     * Ler informacoes de cabecalho
     * @var array
     */
    var $headers = array('left' => '$T', 'right' => '$[autor]');

    /**
     * Informacoes do footer
     * @var array
     */
    var $footers = array('center' => '- $N -');

    /**
     * Analisa a configuracao do script PERL
     * @var string
     */
    var $html2psrc = '
        option {
          titlepage: 0;                /* nao gerar o titulo da pagina */
          toc: 0;                      /* nao gerar tabelas */
          colour: %pageInColor%;       /* criar paginas com cores */
          underline: %underlineLinks%; /* sublinhar links */
          grayscale: %grayScale%;      /* imagens com escala de cinza */
          scaledoc: %scaleFactor%;     /* scala do documento pagina */
        }
        package {
          geturl: %getUrlPath%;        /* caminho para o programa de geturl */
        }
        showurl: 0;                    /* mostrar urls para links */';
    
    /**
     * Substituicao dos trajetos relativos a imagem, definir
     * o dominio "cb.sc.gov.br"
     * @var booleano
     */
    var $makeAbsoluteImageUrls = true;

    /**
     * Aqui podemos especificar a opcao (-I) para efetuar
     * procurar de arquivos e fonts
     * @var string
     */
    var $ps2pdfIncludePath = '';

    /**
     * Maniulacao dos arquivos de html
     * @var string
     */
    var $_htmlString = '';
    
    /** 
     * Construtor 
     */

    /**
     * Inicializando a Classe
     *
     * Variaveis utilizadas:
     *
     * @parametro string $in_htmlFile - Caminho para a convercao do html
     * @parametro string $in_domain   - Dominio relativo as imagens "cb.sc.gov.br"
     * @parametro string $in_pdfFile  - Caminho completo dos arquivos pdfs 
     *              
     *
     * @acesso public
     * @return void
     */
    function HTML_ToPdf($in_htmlFile, $in_domain, $in_pdfFile = null)
    { 
        $this->htmlFile = $in_htmlFile;
        $this->defaultDomain = $in_domain;
        $this->htmlErrors = (php_sapi_name() != 'cli' && !(substr(php_sapi_name(),0,3)=='cgi' && 
                    !isset($_SERVER['GATEWAY_INTERFACE'])));
        
        if (is_null($in_pdfFile)) {
            $this->pdfFile = tempnam($this->tmpDir, 'PDF-');
        }
        else {
            $this->pdfFile = $in_pdfFile;
        }
    }

    /* Configuracoes do HtmlPdf */

    /**
     * Configuracoes adicionais do HtmlPdf
     *
     * @parametros string $in_settings - Configuracoes Adicionais 
     *
     * @acesso publico
     * @retorno void
     */
    function addHtml2PsSettings($in_settings) {
        $this->html2psrc .= "\n" . $in_settings;
    }
   
    /* Configuracoes do Debugador */

    /**
     * Ajustes do debugador 
     *
     * @parametros booleano $in_debug - Debugador ligado desligado?
     *
     * @acesso publico
     * @retorno void
     */
    function setDebug($in_debug)
    {
        $this->debug = $in_debug;
    }

    /* Configuracoes do Cabecalho */

    /**
     * Setando configuracoes do cabecalho
     *
     * @parametros string $in_attribute - Atributos aceitos pelo HtmlPdf como,
     *               (direita esquerda centralizar), (tipo da fonte), (tamanho da fonte),                      
     *               (cores).
     *
     * @parametros string $in_value - Ajuste de valores especiais como,
     *               (titulo do documento), (numero de pagina), (data/hora), (url).
     *
     * @acesso publico
     * @retorno void
     */
    function setHeader($in_attribute, $in_value)
    {
        $this->headers[$in_attribute] = $in_value;
    }

    /* Configuracoes do Footer */

    /**
     * Setando configuracoes do footer
     *
     * @parametros string $in_attribute - Atributos aceitos pelo HtmlPdf como,
     *               (direita esquerda centralizar), (tipo de fonte), (tamanho da fonte),
     *               (cores).
     *               
     * @param string $in_value - Ajuste de valores especiais como,
     *               (titulo do documento), (numero de pagina), (data/hora), (url).
     *
     * @acesso publico
     * @retorno void
     */
    function setFooter($in_attribute, $in_value)
    {
        $this->footers[$in_attribute] = $in_value;
    }

    /* Configuracoes do Diretorio Temporario */

    /**
     * Setando configuracoes do diretorio temporario
     *
     * @param string $in_path - Caminho completo do diretorio 
     *
     * @acesso publico
     * @returno void
     */
    function setTmpDir($in_path) {
        $this->tmpDir = $in_path;
    }

    /* Configuracoes de Cores */

    /**
     * Setando as configuracoes de cores para as paginas
     *
     * @param booleano $in_useColor - Uso de cores?
     *
     * @acesso publico
     * @retorno void
     */
    function setUseColor($in_useColor) {
        $this->pageInColor = $in_useColor;
    }

    /* Configuracoes do CSS */

    /**
     * Setando as configuracoes de estilo CSS para as paginas, que
     * gera o arquivo em pdf
     *
     * @param booleano $in_useCSS - Use estilo CSS para gerar o pdf?
     *
     * @acesso publico
     * @retorno void
     */
    function setUseCSS($in_useCSS) {
        $this->useCSS = $in_useCSS;
    }

    /* Configurações Adicionais de CSS */

    /**
     * Setando configuracoes adicionais de estilos CSS  
     *
     * @param string $in_css - Configuracoes Adicionais de CSS
     *
     * @accesso publico
     * @return void
     */
    function setAdditionalCSS($in_css) {
        $this->additionalCSS = $in_css;
    }

    /* Configuracoes do GetUrl */

    /**
     * Setando configuracoes para o programa de execucao curl de GetUrl 
     *
     * @parametros string $in_getUrl - Programa de GetUrl 
     *
     * @acesso publico
     * @retorno void
     */
    function setGetUrl($in_getUrl) {
        $this->getUrlPath = $in_getUrl;
    }

    /* Configuracoes de Imagem escala de cinza */

    /**
     * Setando configuracoes de imagem de escala de cinza
     *
     * @parametros booleano $in_grayScale - Imagens de escala de cinza  
     *
     * @acesso publico
     * @retorno void
     */
    function setGrayScale($in_grayScale) {
        $this->grayscale = $in_grayScale;
    }

    /* Configuracoes dos links */

    /**
     * Setando configuracoes dos links
     *
     * @parametros booleano $in_underline - Sublinhar o que é link? 
     *
     * @acesso publico
     * @retorno void
     */
    function setUnderlineLinks($in_underline) {
        $this->underlineLinks = $in_underline;
    }

    /* Configuracoes de escala para as paginas */

    /**
     * Setando os fatores de escala para as paginas
     *
     * @parametros inteiro $in_scale - Scala de fatores
     *
     * @acesso public
     * @retorno void
     */
    function setScaleFactor($in_scale) {
        $this->scaleFactor = $in_scale;
    }

    /* Configuracoes do Html2Ps */

    /**
     * Setando caminho para execução do script htmlpdf 
     *
     * @parametros string $in_html2ps - Script PERL htmlpdf
     *
     * @acesso publico
     * @retorno void
     */
    function setHtml2Ps($in_html2ps) {
        $this->html2psPath = $in_html2ps;
    }

    /* Configuracoes do Ps2Pdf */

    /**
     * Setando caminho para execusao do script Ps2Pdf 
     *
     * @parametros string $in_ps2pdf - Script PERL Ps2Pdf
     *
     * @acesso public
     * @retorno void
     */
    function setPs2Pdf($in_ps2pdf) {
        $this->ps2pdfPath = $in_ps2pdf;
    }

    /* Configuracoes do MakeAbsoluteImageUrls */

    /**
     * Setando configuracoes para makeAbsoluteImageUrls
     *
     * @parametros booleano $in_makeAbsoluteImageUrls - Relativo a imagens
     *                                       URLs saida html
     *                                       arquivo para o dominio padrao?
     *
     * @acesso publico
     * @retorno void
     */
    function setMakeAbsoluteImageURLs($in_makeAbsoluteImageURLs)
    {
        $this->makeAbsoluteImageURLs = $in_makeAbsoluteImageURLs;
    }

    /* Configuracoes Ps2pdfIncludePath */

    /**
     * Setando configuracoes para ps2pdfIncludePath
     *
     * @parametros string $in_ps2pdfIncludePath - Caminho para ps2pdf
     *
     * @acesso publico
     * @retorno void
     */
    function setPs2pdfIncludePath($in_ps2pdfIncludePath)
    {
        $this->ps2pdfIncludePath = $in_ps2pdfIncludePath;
    }

    /* Efetuando as Conversoes */

    /**
     * Converte um arquivo html para um arquivo pdf 
     *
     * @acesso publico
     * @retorno string - Caminho do arquivo pdf 
     */
    function convert()
    {
        /* Verifica se o arquivo HTML existe */
        if (!file_exists($this->htmlFile) && !preg_match(':^(f|ht)tps?\://:i', $this->htmlFile)) {
            return new HTML_ToPDFException("Erro: O arquivo HTML não existe: $this->htmlFile");
        }

        /* Verifica primeiramente se podemos executar o script htmlpdf
	   para windows deve-se gerar o certificado da PERL */
        if (!OS_WINDOWS && !@is_executable($this->html2psPath)) {
            return new HTML_ToPDFException("Erro: html2ps [$this->html2psPath] não executa");
        }

        if (!@is_executable($this->ps2pdfPath)) {
            return new HTML_ToPDFException("Erro: ps2pdf [$this->ps2pdfPath] não executa");
        }

        /* Faz uma verificacao em arquivos grandes */
        set_time_limit(160);

        /* Efetua a leitura do arquivo html */
        $this->_htmlString = @implode('', @file($this->htmlFile));
        /* Arquivos adicionais de CSS */
        $this->additionalCSS .= $this->_getCSSFromFile();
        /* Modifica o arquivo de configuração */
        $this->_modifyConfFile();
        $paperSize = $this->_getPaperSize();
        $orientation = $this->_getOrientation();

        if ($this->makeAbsoluteImageUrls) {
            /* Substitui as imagens relativas ao dominio */
            $this->_htmlString = preg_replace(':<img (.*?)src=["\']((?!http\://).*?)["\']:i', '<img \\1 src="http://'.$this->defaultDomain.'/\\2"', $this->_htmlString);
        }

        /* Referente aos formularios do script htmlpdf */
        $this->_htmlString = preg_replace(':<input (.*?)type=["\']?(hidden|submit|button|image|reset|file)["\']?.*?>:i', '<input />', $this->_htmlString);

        $a_tmpFiles = array();
        /* Diretorio temporario deve existir */
        $a_tmpFiles['config'] = tempnam($this->tmpDir, 'CONF-');

        if (!@is_writable($a_tmpFiles['config'])) {
            return new HTML_ToPDFException("Erro: O diretório temporário não existe ou está sem permisões de escrita.");
        }

        $fp = fopen($a_tmpFiles['config'], 'w');
        fwrite($fp, $this->html2psrc);
        fclose($fp);
        $this->_dumpDebugInfo("html2ps config: $this->html2psrc");

        /* Arquivo HTML provisorio deve existir */
        $a_tmpFiles['html'] = tempnam($this->tmpDir, 'HTML-');
        while (is_file($a_tmpFiles['html'] . '.html')) {
            unlink($a_tmpFiles['html']);
            $a_tmpFiles['html'] = tempnam($this->tmpDir, 'HTML-');
        }

        $a_tmpFiles['html'] .= '.html';
        $fp = fopen($a_tmpFiles['html'], 'w');
        fwrite($fp, $this->_htmlString);
        fclose($fp);

        /* Arquivo postscript provisorio deve existir */
        $a_tmpFiles['ps'] = tempnam($this->tmpDir, 'PS-');

        $tmp_result = array();
        $cmd = $this->html2psPath . ' ' . $orientation . ' -f ' . $a_tmpFiles['config'] . ' -o ' . 
                $a_tmpFiles['ps'] . ' ' . $a_tmpFiles['html'] .  ' 2>&1'; 
        exec($cmd, $tmp_result, $retCode);
        $this->_dumpDebugInfo("html2ps command run: $cmd");
        $this->_dumpDebugInfo("html2ps output: " . @implode("\n", $tmp_result));

        if ($retCode != 0) {
            $this->_cleanup($a_tmpFiles);
            return new HTML_ToPDFException("Erro: Problemas ao executar o script PERL htmlpdf. Erro de conversão, debugador retornou: $retCode. Abilite a opção do debug para ver as informações de execução na tela.");
        }

        $tmp_result = array();
        $cmd = $this->ps2pdfPath . ' -sPAPERSIZE=' . $paperSize . ' -I' . $this->ps2pdfIncludePath . ' ' .
            ' -dAutoFilterColorImages=false -dColorImageFilter=/FlateEncode ' . 
            $a_tmpFiles['ps'] .  ' \'' . escapeshellcmd($this->pdfFile) .  '\' 2>&1';
        exec($cmd, $tmp_result, $retCode);

        $this->_dumpDebugInfo("Rodando o ps2pdf: $cmd");
        $this->_dumpDebugInfo("Saída do ps2pdf: " . @implode("\n", $tmp_result));
        if ($retCode != 0) {
            $this->_cleanup($a_tmpFiles);
            return new HTML_ToPDFException("Erro: Problemas ao executar o script ps2pdf. Erro de conversão, debugador retornou: $retCode. Abilite a opção do debug para ver as informações de execução na tela.");
        }

        $this->_cleanup($a_tmpFiles);
        return $this->pdfFile;
    }

    /* Configuracoes _modifyConfFile */

    /**
     * Modificacao da linha do config 
     *
     * @acesso privado
     * @retorno void
     */

    function _modifyConfFile()
    {
        /* Configuracao do links para os estilos CSS */
        if (is_null($this->underlineLinks)) {
            if (preg_match(':a\:link {.*?text-decoration\: (.*?);.*?}:is', $this->additionalCSS, $matches) &&
                is_int(strpos($matches[1], 'none'))) {
                $this->underlineLinks = false;
            }
            else {
                $this->underlineLinks = true;
            }
        }

        $this->html2psrc = str_replace('%scaleFactor%', $this->scaleFactor, $this->html2psrc);
        $this->html2psrc = str_replace('%getUrlPath%', $this->getUrlPath, $this->html2psrc);
        /* Convertendo boleanos em numeros */
        $this->html2psrc = str_replace('%pageInColor%', (int) $this->pageInColor, $this->html2psrc);
        $this->html2psrc = str_replace('%grayScale%', (int) $this->getUrlPath, $this->html2psrc);
        $this->html2psrc = str_replace('%underlineLinks%', (int) $this->underlineLinks, $this->html2psrc);

        /* Adicionado cabecalhos e os footers */
        $this->html2psrc .= "\nheader {\n" . $this->_processHeaderFooter($this->headers);
        $this->html2psrc .= "}\nfooter {\n" . $this->_processHeaderFooter($this->footers);
        $this->html2psrc .= '}';

        /* Adicione o tamanho do papel para assegurar que, */ 
	/* headers/footer sejam mostrados */
        if (!preg_match('/@page.*?{.*?size:\s*(.*?);/is', $this->additionalCSS)) {
            $this->additionalCSS .= "\n@page {\n";
            $this->additionalCSS .= "  size: 8.5in 11in;\n";
            $this->additionalCSS .= "}\n";
        }

        /* Container Global */
        $this->html2psrc = '
        @html2ps {
          ' . $this->html2psrc . '
        }
        ' . $this->additionalCSS;
    }

    /* Configuracoes do _getCSSFromFile */

    /**
     * Tenta iniciar o CSS do HTML e usa-lo para criar o pdf
     * caso encontre o CSS adicione no arquivo pdf.
     *
     * @acesso privado
     * @retorno string Achei o CSS  
     */
    function _getCSSFromFile()
    {
        if ($this->useCSS) {
            $cssFound = '';
            /* Primeira tentativa de procura do estilo CSS */
            if (preg_match(':<style.*?>(.*?)</style>:is', $this->_htmlString, $matches)) {
                $cssFound = $matches[1];
                /* Substitua com o nada se não encontrar o CSS no HTML */
                $this->_htmlString = preg_replace(':<style.*?>.*?</style>:is', '', $this->_htmlString);
            }
            elseif (preg_match(':<link .*? href=["\'](.*?)["\'].*?text/css.*?>:i', $this->_htmlString, $matches)) {
                $cssFound = preg_replace(':(^(?!http\://).*):i', 'http://'.$this->defaultDomain.'/\\1', $matches[1]);
                $cssFound = implode('', file($cssFound));
            }

            /* Somente atributo das tomadas a:link */
            $cssFound = preg_replace(':a +{:i', 'a:link {', $cssFound);

            /* Tamanho da font */
            $cssFound = preg_replace(':font-size\: *(\w*);:ie', '$this->_convertFontSize("\\1")', $cssFound);

            return $cssFound;
        }
        else {
            return '';
        }
    }

    /* Configuracoes do _convertFontSize */

    /**
     * Converte o tamanho textual a uma respresentacao numerica.
     *
     * @parametros string $in_fontString Tamanho da font
     *
     * @acesso privado
     * @retorno string Tamanho da font
     */
    function _convertFontSize($in_fontString)
    {
        switch ($in_fontString) {
            case 'xx-small':
                $size = 6; 
                break;
            case 'x-small':
                $size = 8;
                break;
            case 'small':
                $size = 10;
                break;
            case 'medium':
                $size = 12;
                break;
            case 'large':
                $size = 14;
                break;
            case 'x-large':
                $size = 16;
                break;
            case 'xx-large':
                $size = 18;
                break;
            default:
                $size = 12;
                break;
        }

        return 'font-size: ' . $size . 'pt;';
    }

    /* Configuracoes do _getPaperSize */

    /**
     * Tenta determinar o tamanho do papel especificado.
     *
     * @acesso privado
     * @retorno string Tamanho do papel
     */
    function _getPaperSize()
    {
        /* OBS: Nao suporta o bloco de papel (script PERL htmlpdf)
         *      sendo que a maneira mais correta para fazer isto é pelo bloco @p         *      age. 
	 */
        preg_match('/@page.*?{.*?size:\s*(.*?);/is', $this->html2psrc, $matches);
        if (!isset($matches[1])) {
            $matches[1] = '8.5in 11in';
        }

        /* Remove os espacos extras */
        $matches[1] = str_replace(' ', '', $matches[1]);
        switch ($matches[1]) {
            case '8.5in14in':
                $size = 'legal';
            break;
            case '11in17in':
                $size = '11x17';
            break;
            case '17in11in':
                $size = 'ledger';
            break;
            case 'a4':
                $size = 'a4';
            break;
            case '8.5in11in':
            default:
                $size = 'letter';
            break;
        }

        return $size;
    }
    
    /* Configuracoes do _getOrientation */

    /**
     * Determina orientacao a pagina
     *
     * @acesso privado
     * @retorno string Pagina orientada
     */
    
    function _getOrientation() 
    {
        preg_match('/@page.*?{.*?orientation:\s*(.*?);/is', $this->html2psrc, $matches);
        if (!isset($matches[1])) {
            $matches[1] = 'portrait';
        }
        
        switch ($matches[1]) {
            case 'landscape':
                $orientation = '--landscape';
            break;
            default:
                $orientation = '';
            break;
        }

        return $orientation;
    }

    /* Configuracao do _processHeaderFooter */

    /**
     * Processo dos heads e footers
     *
     * @parametros array $in_data Cabeçalhos e footers
     *
     * @acesso privado
     * @retorno string Script PERL htmlpdf
     */
    function _processHeaderFooter($in_data)
    {
        $s_data = '';
        /* Seta atributos para heads e footers */
        foreach (array('left', 'right', 'center') as $s_key) {
            if (isset($in_data[$s_key])) {
                if (!isset($in_data["odd-$s_key"])) {
                    $in_data["odd-$s_key"] = $in_data[$s_key];
                }
                if (!isset($in_data["even-$s_key"])) {
                    $in_data["even-$s_key"] = $in_data[$s_key];
                }
            }
        }

        foreach ($in_data as $s_key => $s_val) {
            $s_data .= "  $s_key: \"$s_val\"\n";
        }

        return $s_data;
    }

    /* Configuracao do _cleanup */

    /**
     * Limpa todos os arquivos que foram criados durante a operacao
     * de execusao da classe
     *
     * @parametros array $in_files Limpa arquivos
     *
     * @acesso privado
     * @retorno void
     */
    function _cleanup($in_files)
    {
        foreach ($in_files as $key => $file) {
            if ($this->debug) {
                $this->_dumpDebugInfo("$key file: $file (Arquivos não Removidos)");
            }
            else {
                unlink($file);
            }
        }
    }

    /* Configuracao do _dumpDebugInfo */

    /**
     * Eliminar erros do debug na tela
     *
     * @parametros string $in_info Informacao da debugacao
     *
     * @acesso publico
     * @retorno void
     */
    function _dumpDebugInfo($in_info)
    {
        if ($this->debug) {
            if ($this->htmlErrors) {
                echo "<pre><span style=\"color: red;\">DEBUGANDO</span>: $in_info</pre>";
            }
            else {
                echo "DEBUG: $in_info\n";
            }
        }
    }

}

/** 
 * Configuracao do HTML_ToPDFException extendendo,
 * para classe de erro da PEAR 
 */
class HTML_ToPDFException extends PEAR_Error {
    var $classname             = 'HTML_ToPDF';
    var $error_message_prepend = 'Erro: ';

    function HTML_ToPDFException($message)
    {
        $this->PEAR_Error($message);
    }
}

/* Configuracoes do is_executable */

    if (!function_exists('is_executable')) {
    /**
     * Funcao do is_executable
     *
     * @parametros string $in_filename Arquivo de testes
     *
     * @acesso publico
     * @return booleano Arquivo existe
     */
    function is_executable($in_filename)
    {
        return file_exists($in_filename);
    }
}

?>
