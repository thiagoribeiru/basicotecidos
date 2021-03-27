<?require_once('session.php');?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="styleapp.css">
        <script src="js/jquery-1.8.2.js"></script>
        <script src="js/ajaxes.js"></script>
        <title>Basico Tecidos - Primeiro Acesso</title>
    </head>
    <body>
        <?
        // require_once('session.php');
        // require_once('barra_menu.php');
        ?>
        <div id="div_corpo">
            <div id="primeiro_acesso">
                <?
                if ($_SERVER['REQUEST_METHOD']=="POST") {
                    $id = $_SESSION['UsuarioID'];
                    $nome = $_POST['nome'];
                    $email = $_POST['email'];
                    $senha = strtoupper($_POST['tok2']);
                    
                    $email_pesq = mysql_query("select email from usuarios where email = '$email' and id != '$id'") or die (mysql_error());
                    if (mysql_num_rows($email_pesq)>0) {
                        echo "<script>alert('Opa! Esse e-mail já existe no sistema!');</script>";
                        echo "<script>window.location.href = 'primeiro_acesso.php';</script>";
                    } else {
                        mysql_query("update usuarios set nome = '$nome', email = '$email', senha = sha1('$senha'), primeiro_acesso = 0 where id = '$id'") or die (mysql_error());
                        $emailFromPesq = mysql_query("select email from notificacoes where tipo = 'remetenteNoti' and ativo = '1'") or die (mysql_error());
                        $emailNotiPesq = mysql_query("select email from notificacoes where tipo = 'config' and ativo = '1'") or die (mysql_error());
                        if (mysql_num_rows($emailFromPesq)>0 and mysql_num_rows($emailNotiPesq)>0) {
                            $de = mysql_fetch_array($emailFromPesq);
                            $para = mysql_fetch_array($emailNotiPesq);
                            $texto = 'O usuario '.$nome.' - '.$email.' finalizou seu primeiro acesso.';
                            enviaEmail($de['email'],$para['email'],'Alteração de primeiro acesso',$texto);
                        }
                        echo "<script>alert('Tudo certo! Favor logar novamente!');</script>";
                        echo "<script>window.location.href = 'logout.php';</script>";
                    }
                }
                if ($_SERVER['REQUEST_METHOD']!="POST") {
                    echo "<center>Olá. Esse é o seu primeiro acesso ao sistema da Basico Tecidos.<br>\n";
                    echo "Para continuar, é necessário preencher o formulário abaixo:<br></center>\n";
                    echo "<br>\n";
                    
                    $user = mysql_fetch_array(mysql_query("select nome, email from usuarios where id = ".$_SESSION['UsuarioID']));
                    $nome = $user['nome'];
                    $email = $user['email'];
                    
                    echo "<form action=\"primeiro_acesso.php\" method=\"POST\">\n";
                        echo "<fieldset>\n";
                            echo "Qual o seu nome? Precisamos saber como chamá-lo...<br>\n";
                            echo "<input type=\"text\" name=\"nome\" id=\"nome\" value=\"$nome\" /><br>\n";
                            echo "<br>\n";
                            echo "O seu e-mail está correto? Ele servirá de login para o sistema...<br>\n";
                            echo "<input type=\"text\" name=\"email\" id=\"email\" value=\"$email\" /><br>\n";
                            echo "<br>\n";
                            echo "Agora precisamos que você insira uma senha nova. A que temos agora é muito fraca...<br>\n";
                            echo "<input type=\"password\" name=\"tok1\" id=\"tok1\" /><br>\n";
                            echo "<br>\n";
                            echo "Só para confirmar, digite a senha novamente...<br>\n";
                            echo "<input type=\"password\" name=\"tok2\" id=\"tok2\" /><br>\n";
                        echo "</fieldset>\n";
                        echo "<div align=\"right\"><input type=\"submit\" value=\"Continuar...\" /></div>\n";
                    echo "</form>\n";
                }
                ?>
            </div>
        </div>
    </body>
</html>