<?require_once('../session.php');?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="../styleapp.css">
        <script src="../js/jquery-1.8.2.js"></script>
        <script src="../js/ajaxes.js"></script>
        <title>Basico Tecidos - Administração</title>
    </head>
    <body>
        <?
        // require_once('../session.php');
        require_once('../barra_menu.php');
        ?>
        <div id="div_corpo">
        <? if((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['usuarios']) and $_SESSION['autoriza']['usuarios']==1)) {
        $users_search = $sql->query("select id, nome, email, nivel, ativo, primeiro_acesso from usuarios where nivel <> '2'");
        $users_online = $sql->query("select id_user as id from users_logados") or die (mysqli_error($sql));
        if (mysqli_num_rows($users_online)>0) {
            for ($i=1;$i<=mysqli_num_rows($users_online);$i++) {
                $id_vet = mysqli_fetch_array($users_online);
                // $id[$i] = $id_vet['id'];
                $id[$id_vet['id']] = $id_vet['id'];
            }
        } else $id[0] = 0;
        // var_dump($id);
        echo "<form id=\"form_users\">\n";
        if (mysqli_num_rows($users_search)>0) {
            echo "<span id=\"titulo\">Usuários";
                if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['adicionar_usuario']) and $_SESSION['autoriza']['adicionar_usuario']==1))
                echo "<div class=\"caixa_corte\"><img src=\"../images/icons-1-18x18.png\" id=\"adc_cli\"></div>";
            echo "</span>\n";
            echo "<div id=\"div_janela\" style=\"min-height: 200px; min-width: 536px;\">\n";
                echo "<table class=\"tabela\">\n";
                    echo "<thead>\n";
                        echo "<tr>\n";
                            echo "<td width=\"200px\">Nome</td>\n";
                            echo "<td width=\"300px\">E-mail</td>\n";
                            if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['editar_usuario']) and $_SESSION['autoriza']['editar_usuario']==1))
                            echo "<td width=\"18px\"></td>\n";
                            if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['resetar_senha']) and $_SESSION['autoriza']['resetar_senha']==1))
                            echo "<td width=\"18px\"></td>\n";
                            echo "<td width=\"18px\"></td>\n";
                        echo "</tr>\n";
                    echo "</thead>\n";
                    echo "<tbody>\n";
                    for ($i=0;$i<mysqli_num_rows($users_search);$i++) {
                        $users_linha = mysqli_fetch_array($users_search);
                        echo "<tr>\n";
                            if ($users_linha['primeiro_acesso']=='1') $destaca_amarelo = "bgcolor=\"#F2F5A9\""; else $destaca_amarelo = "";
                            echo "<td name=\"nome\" idd=\"".$users_linha['id']."\" $destaca_amarelo>".$users_linha['nome']."</td>\n";
                            echo "<td name=\"email\" idd=\"".$users_linha['id']."\" $destaca_amarelo>".$users_linha['email']."</td>\n";
                            if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['editar_usuario']) and $_SESSION['autoriza']['editar_usuario']==1)) {
                                echo "<td d_click=\"no\" $destaca_amarelo>\n";
                                    echo "<div class=\"caixa_corte\"><img src=\"../images/icons-1-18x18.png\" id=\"editar\" usr_id=\"".$users_linha['id']."\"></div>\n";
                                echo "</td>\n";
                            }
                            if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['resetar_senha']) and $_SESSION['autoriza']['resetar_senha']==1)) {
                                if ($users_linha['primeiro_acesso']=='1') {
                                    echo "<td d_click=\"no\" $destaca_amarelo><div class=\"caixa_corte\"><img src=\"../images/icons-1-18x18.png\" id=\"senha_inicial\"></div></td>\n";
                                } else {
                                    echo "<td d_click=\"no\" $destaca_amarelo><div class=\"caixa_corte\"><img src=\"../images/icons-1-18x18.png\" id=\"alt_senha\"></div></td>\n";
                                }
                            }
                            if (isset($id[$users_linha['id']]) and $users_linha['id']==$id[$users_linha['id']]) {
                                echo "<td d_click=\"no\" $destaca_amarelo id=\"status\"><div class=\"caixa_corte\"><img src=\"../images/status_icon.png\" id=\"status_on\"></div></td>\n";
                            } else {
                                echo "<td d_click=\"no\" $destaca_amarelo id=\"status\"><div class=\"caixa_corte\"><img src=\"../images/status_icon.png\" id=\"status_off\"></div></td>\n";
                            }
                        echo "</tr>\n";
                    }
                    echo "</tbody>\n";
                echo "</table>\n";
            echo "</div>\n";
        }
        echo "</form>\n";
        ?>
            <?if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['editar_usuario']) and $_SESSION['autoriza']['editar_usuario']==1)) {?>
            <div id="janela_edit_user">
                <div class="caixa_corte">
                    <img src="../images/icons-1-18x18.png" id="fechar">
                </div>
                <form id="form_editUsers">
                    <fieldset><legend>Edição de Usuários:</legend>
                        <input type="hidden" name="id" id="id" value="" />
                        <label for="nome">Nome:</label>
                        <input type="text" name="nome" id="nome" /><br>
                        <label for="email">E-mail:</label>
                        <input type="text" name="email" id="email" /><br>
                        <label for="nivel">Nivel:</label>
                        <div class="radio">
                            <input type="radio" name="nivel" id="nivel" value="0">Administrador</input>
                            <input type="radio" name="nivel" id="nivel" value="1">Cliente</input>
                        </div>
                        
                        <div class="user_on_off">
                            <img src="../images/buttons.png" class="usr_pas" usr_id="">
                        </div>
                        
                        <br><br>
                        <?if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['editar_permissoes']) and $_SESSION['autoriza']['editar_permissoes']==1)) {?>
                        <span id="permispermis" style="float: right;"></span><br><br>
                        <?}?>
                        <input type="submit" value="Saltar Alterações">
                    </fieldset>
                </form>
            </div>
            <?}?>
        <?}?></div>
    </body>
</html>