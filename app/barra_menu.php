<?
// var_dump($_SESSION);
echo "<div id=\"welcome\">\n";
    echo "<div id=\"menu_horizontal\">\n";
        echo "<ul class=\"wmenu\">\n";
            echo "<li><a href=\"../index.php\">Início</a></li>\n";
            //Menu Compras
            if ($_SESSION['UsuarioNivel']==1) {
                echo "<li><a href=#>Compras</a>\n";
                    echo "<ul class=\"wsubmenu-1\">\n";
                        echo "<li><a href=\"cliente_pedidos.php\">Pedidos</a></li>\n";
                    echo "</ul>\n";
                echo "</li>\n";
            }
            //Menu Confirgurações
            if ($_SESSION['UsuarioNivel']==0 and ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['configuracoes']) and $_SESSION['autoriza']['configuracoes']==1))) {
                echo "<li><a href=#>Configurações</a>\n";
                    echo "<ul class=\"wsubmenu-1\">\n";
                        if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['usuarios']) and $_SESSION['autoriza']['usuarios']==1))
                        echo "<li><a href=\"usuarios.php\">Usuários</a></li>\n";
                        if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['parametros']) and $_SESSION['autoriza']['parametros']==1))
                        echo "<li><a href=\"configura.php\">Parâmetros</a></li>\n";
                    echo "</ul>\n";
                echo "</li>\n";
            }
            //Menu Vendas
            if ($_SESSION['UsuarioNivel']==0 and ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['vendas']) and $_SESSION['autoriza']['vendas']==1))) {
                echo "<li><a href=#>Vendas</a>\n";
                    // echo "<ul class=\"wsubmenu-1\">\n";
                    // 	echo "<li class=\"submenu-x\"><a href=#>Pedidos</a>\n";
                       // 	echo "<ul class=\"wsubmenu-2\">\n";
                       // 		echo "<li><a href=\"cad_ped.php\">Cadastro de Pedidos</a></li>\n";
                       // 	echo "</ul>\n";
                       // echo "</li>\n";
                    // echo "</ul>\n";
                    echo "<ul class=\"wsubmenu-1\">\n";
                        if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['pedidos']) and $_SESSION['autoriza']['pedidos']==1))
                        echo "<li><a href=\"admin_pedidos.php\">Pedidos</a></li>\n";
                    echo "</ul>\n";
                echo "</li>\n";
            }
            //Menu Informações Sistema
            if ($_SESSION['UsuarioNivel']==0 and (isset($_SESSION['autoriza']['controle_total']) and (isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1))) {
                echo "<li><a href=#>Info. Sistem.</a>\n";
                    echo "<ul class=\"wsubmenu-1\">\n";
                        echo "<li><a href=\"phpinfo.php\">Informações do PHP</a></li>\n";
                    echo "</ul>\n";
                echo "</li>\n";
            }
        echo "</ul>\n";
    echo "</div>\n";
    echo "<div id=\"welcome_id\" style=\"margin-top: 2px; float: right;\">Olá, ".$_SESSION['UsuarioNome']." - <a href=\"../logout.php\">LogOut</a></div>\n";
echo "</div>\n";
?>