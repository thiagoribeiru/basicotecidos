<?require_once('../session.php');?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="../styleapp.css">
        <script src="../js/jquery-1.8.2.js"></script>
        <script src="../js/ajaxes.js"></script>
        <script src="../js/tablesorter.min.js"></script> 
        <title>Basico Tecidos - Administração</title>
    </head>
    <body>
        <?
        // require_once('../session.php');
        require_once('../barra_menu.php');
        // require_once('../PagSeguroLibrary/PagSeguroLibrary.php');
        ?>
        <div id="div_corpo">
        <?
        $pedidos_search = mysql_query("select cod_ped as cod, DATE_FORMAT(data,'%d/%m/%Y') as data, (select usuarios.nome from usuarios where id = pedidos_dados.id_cli) as cliente, (select usuarios.email from usuarios where id = pedidos_dados.id_cli) as login, (select pos_pedidos.nome from pos_pedidos where id = pedidos_dados.id_pos) as posicao from pedidos_dados where ativo = '1' and id_cli = '".$_SESSION['UsuarioID']."' order by cod_ped");
        echo "<form id=\"form_pedidos\">\n";
        echo "<span id=\"titulo\" style=\"min-width: 300px;\">Pedidos";
            // echo "<div class=\"caixa_corte\"><img src=\"../images/icons-1-18x18.png\" title=\"Adicionar Pedidos\" id=\"adc_ped\"></div>";
            // echo "<div class=\"caixa_corte\"><img src=\"../images/icons-1-18x18.png\" title=\"Remover Pedido\" id=\"rem_ped\"></div>";
        echo "</span>\n";
        if (mysql_num_rows($pedidos_search)>0) {
            echo "<div id=\"div_janela\" style=\"min-height: 400px; min-width: 955px;\">\n";
                echo "<table class=\"tabela\" id=\"tabela_pedidos_cli\">\n";
                    echo "<thead>\n";
                        echo "<tr>\n";
                            echo "<th width=\"55px\">Cód</th>\n";
                            echo "<th width=\"100px\">Data</th>\n";
                            echo "<th width=\"300px\">Cliente</th>\n";
                            echo "<th width=\"300px\">Login</th>\n";
                            echo "<th width=\"150px\">Total</th>\n";
                            echo "<th width=\"150px\">Situação</th>\n";
                        echo "</tr>\n";
                    echo "</thead>\n";
                    echo "<tbody>\n";
                    for ($i=0;$i<mysql_num_rows($pedidos_search);$i++) {
                        $pedidos_linha = mysql_fetch_array($pedidos_search);
                        echo "<tr ped=\"".$pedidos_linha['cod']."\">\n";
                            echo "<td>".mascara_string("####",$pedidos_linha['cod'])."</td>\n";
                            echo "<td>".$pedidos_linha['data']."</td>\n";
                            echo "<td>".$pedidos_linha['cliente']."</td>\n";
                            echo "<td>".$pedidos_linha['login']."</td>\n";
                            echo "<td align=\"right\">R$ ".somaPedido($pedidos_linha['cod'])."</td>\n";
                            echo "<td>".$pedidos_linha['posicao']."</td>\n";
                        echo "</tr>\n";
                    }
                    echo "</tbody>\n";
                echo "</table>\n";
            echo "</div>\n";
        }
        echo "</form>\n";
        ?>
            <div id="fundo_fumace">
                <div id="tela_adc_ped">
                    
                    <div id="adc_ped_h">
                        <div class="titulo">Descrição Pedido</div>
                        <div class="caixa_corte">
                            <img src="../images/icons-1-18x18.png" id="fechar" title="Fechar">
                        </div>
                    </div>
                    
                    <div id="shadow_box"></div>
                    
                    <div id="carregando">
                        <div class="div">
                            <img src="../images/249.png">
                            <span>Carregando</span>
                        </div>
                    </div>
                    
                    <div id="adc_ped_body">
                        <table>
                            <tr>
                                <td class="legenda"><span>Código Pedido</span></td>
                                <td><input type="text" id="cod_ped" class="cod_ped" placeholder="Nº Pedido" readonly="readonly" /></td>
                            </tr>
                            <!--<tr>-->
                            <!--    <td class="legenda"><span>Cliente</span></td>-->
                            <!--    <td>-->
                            <!--        <div class="caixa_input"><input type="text" class="cod_cli" placeholder="Código" /></div>-->
                            <!--        <div class="caixa_corte caixa_proc"><img src="../images/icons-1-18x18.png" id="proc_cli" title="Procurar Cliente"></div>-->
                            <!--        <div class="caixa_input"><input type="text" class="nome_cli" placeholder="Nome do Cliente" readonly="readonly" />-->
                            <!--        <input type="text" class="login_cli" placeholder="E-mail/Login do Cliente" readonly="readonly" /></div>-->
                            <!--    </td>-->
                            <!--</tr>-->
                        </table>
                        <img src="../images/SubDiv.jpg" class="lin_sep">
                        <div id="conteiner_itens">
                            <table class="tabela_itens_head">
                                <thead>
                                    <th>
                                        <td width="62px" class="alfa">Cód. Prod.</td>
                                        <td width="250px">Descrição</td>
                                        <td width="30px">Uni</td>
                                        <td width="70px">Quant.</td>
                                        <td width="60px">Valor R$</td>
                                        <td width="45px">%Desc.</td>
                                        <td width="45px">%IPI</td>
                                        <td width="80px">Entrega</td>
                                        <td width="80px">O.C.</td>
                                        <td width="100px" class="omega">Sub Total</td>
                                        <td width="15px" class="omega"></td>
                                    </th>
                                </thead>
                            </table>
                            <div class="container_tabela_itens_body">
                                <table class="tabela_itens_body" id="tabela_itens">
                                    
                                </table>
                            </div>
                        </div>
                        <!--<div id="caixa_com_itn">-->
                        <!--    <div class="caixa_corte"><img src="../images/icons-1-18x18.png" id="adc_lin" title="Adicionar Item"></div>-->
                        <!--    <div class="caixa_corte"><img src="../images/icons-1-18x18.png" id="rem_lin" title="Remover Item"></div>-->
                            <!--<div class="caixa_corte"><img src="../images/icons-1-18x18.png" id="conf_lin" title="Confirmar Item"></div>-->
                            <!--<div class="caixa_corte"><img src="../images/icons-1-18x18.png" id="canc_lin" title="Limpar Item"></div>-->
                        <!--</div>-->
                        <div id="caixa_com_tot">
                            <span>Sub Total: </span>
                            <span id="subtotal"></span>
                        </div>
                        <img src="../images/SubDiv.jpg" class="lin_sep">
                        <div id="caixa_fin">
                            <!--<input type="button" id="gravar" class="botao" value="Gravar" />-->
                            <!--<input type="button" id="limpar" class="botao" value="Limpar" />-->
                            <input type="button" id="finalizar" class="botaofalse" value="Finalizar Compra" ativo="false" />
                            <input type="button" id="fechar" class="botao" value="Fechar" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <?
    $pesq_api = mysql_query("select * from dados_apipag where ativo = '1'") or die (mysql_query());
    $dados = mysql_fetch_array($pesq_api);
    if ($dados['environment']=='sandbox') {
        echo "<script type=\"text/javascript\" src=\"https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js\"></script>\n";
    } else if ($dados['environment']=='production') {
        echo "<script type=\"text/javascript\" src=\"https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js\"></script>\n";
    }
    ?>
</html>