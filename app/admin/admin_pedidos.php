<?require_once('../session.php');?>
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
        ?>
        <div id="div_corpo">
        <?
        if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['pedidos']) and $_SESSION['autoriza']['pedidos']==1)) {
        $pedidos_search = $sql->query("select cod_ped as cod, DATE_FORMAT(data,'%d/%m/%Y') as data, (select usuarios.nome from usuarios where id = pedidos_dados.id_cli) as cliente, (select usuarios.email from usuarios where id = pedidos_dados.id_cli) as login, (select pos_pedidos.nome from pos_pedidos where id = pedidos_dados.id_pos) as posicao from pedidos_dados where ativo = '1' order by cod_ped");
        echo "<form id=\"form_pedidos\">\n";
        echo "<span id=\"titulo\" style=\"min-width: 300px;\">Pedidos";
            if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['adicionar_pedidos']) and $_SESSION['autoriza']['adicionar_pedidos']==1))
            echo "<div class=\"caixa_corte\"><img src=\"../images/icons-1-18x18.png\" title=\"Adicionar Pedidos\" id=\"adc_ped\"></div>";
            if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['remover_pedidos']) and $_SESSION['autoriza']['remover_pedidos']==1))
            echo "<div class=\"caixa_corte\"><img src=\"../images/icons-1-18x18.png\" title=\"Remover Pedido\" id=\"rem_ped\"></div>";
        echo "</span>\n";
        if (mysqli_num_rows($pedidos_search)>0) {
            echo "<div id=\"div_janela\" style=\"min-height: 400px; min-width: 955px;\">\n";
                echo "<table class=\"tabela\" id=\"tabela_pedidos\">\n";
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
                    for ($i=0;$i<mysqli_num_rows($pedidos_search);$i++) {
                        $pedidos_linha = mysqli_fetch_array($pedidos_search);
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
            <?if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['adicionar_pedidos']) and $_SESSION['autoriza']['adicionar_pedidos']==1)) {?>
            <div id="fundo_fumace">
                <div id="tela_adc_ped">
                    
                    <div id="adc_ped_h">
                        <div class="titulo">Lançamento de Pedido</div>
                        <div class="caixa_corte">
                            <img src="../images/icons-1-18x18.png" id="fechar" title="Fechar">
                        </div>
                    </div>
                    
                    <div id="shadow_box"></div>
                    
                    <div id="box_proc_cli">
                        <div id="box_body"></div>
                        <img src="../images/SubDiv.jpg" class="lin_sep">
                        <div id="box_rodape">
                            <input type="button" id="atualizar_proc_cli" value="Atualizar" />
                            <input type="button" id="fechar_proc_cli" value="Fechar" />
                        </div>
                    </div>
                    
                    <div id="box_edit_item">
                        <div id="adc_item_h">
                            <div class="titulo">Lançamento de Item</div>
                            <!--<div class="caixa_corte">-->
                            <!--    <img src="../images/icons-1-18x18.png" id="fechar" title="Fechar">-->
                            <!--</div>-->
                        </div>
                        <table>
                            <tr>
                                <td class="c1">Código do produto:</td>
                                <td>
                                    <input type="text" class="in1" id="cod_item"/>
                                    <span id="cod_item_span"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="c1">Descrição:</td>
                                <td><input type="text" class="in2" id="tab1"/></td>
                            </tr>
                            <tr>
                                <td class="c1">Unidade:</td>
                                <td>
                                    <input type="text" class="in3" id="tab2"/>
                                    <span>Quantidade:</span>
                                    <input type="text" class="in4" id="tab3"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="c1">Valor:</td>
                                <td>
                                    <input type="text" class="in5" id="tab4"/>
                                    <span>% Desc.:</span>
                                    <input type="text" class="in6" id="tab5"/>
                                    <span>% IPI:</span>
                                    <input type="text" class="in7" id="tab6"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="c1">Entrega:</td>
                                <td>
                                    <input type="text" class="in8" id="tab7" maxlength="10"/>
                                    <span>Ordem de Compra:</span>
                                    <input type="text" class="in9" id="tab8"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="c1"></td>
                                <td class="subtotal"><span id="subsub"></span></td>
                            </tr>
                        </table>
                        <img src="../images/SubDiv.jpg" class="lin_sep">
                        <div id="box_rodape">
                            <input type="button" id="salvar" value="Adicionar" />
                            <input type="button" id="fechar_edit_cli" value="Fechar" />
                        </div>
                    </div>
                    
                    <div id="adc_ped_body">
                        <div id="cabecalho_dados">
                            <!--<tr>-->
                                <div class="legenda"><span>Código Pedido</span></div>
                                <div id="legenda"><input type="text" id="cod_ped" class="cod_ped" placeholder="Nº Pedido" readonly="readonly" /></div>
                            <!--</tr>-->
                            <!--<tr>-->
                                <div class="legenda"><span>Cliente</span></div>
                                <div id="caixas">
                                    <div class="caixa_input" id="cx1"><input type="text" class="cod_cli" placeholder="Código" id="cx1.1"/></div>
                                    <div class="caixa_corte caixa_proc" id="cx2"><img src="../images/icons-1-18x18.png" id="proc_cli" title="Procurar Cliente"></div>
                                    <div class="caixa_input" id="cx3"><input type="text" class="nome_cli" placeholder="Nome do Cliente" readonly="readonly" id="cx3.3"/>
                                    <input type="text" class="login_cli" placeholder="E-mail/Login do Cliente" readonly="readonly" id="cx3.4"/></div>
                                </div>
                            <!--</tr>-->
                        </div>
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
                        <div id="caixa_com_itn">
                            <div class="caixa_corte"><img src="../images/icons-1-18x18.png" id="adc_lin" title="Adicionar Item"></div>
                            <div class="caixa_corte"><img src="../images/icons-1-18x18.png" id="rem_lin" title="Remover Item"></div>
                            <!--<div class="caixa_corte"><img src="../images/icons-1-18x18.png" id="conf_lin" title="Confirmar Item"></div>-->
                            <!--<div class="caixa_corte"><img src="../images/icons-1-18x18.png" id="canc_lin" title="Limpar Item"></div>-->
                        </div>
                        <img src="../images/SubDiv.jpg" class="lin_sep">
                        <div id="caixa_fin">
                            <input type="button" id="gravar" class="botao" value="Gravar" />
                            <input type="button" id="limpar" class="botao" value="Limpar" />
                            <input type="button" id="fechar" class="botao" value="Fechar" />
                        </div>
                    </div>
                </div>
            </div>
            <?}?>
        <?}?>
        </div>
    </body>
</html>