<?
if (!isset($_SESSION)) session_start();
if (isset($_SESSION) and isset($_SESSION['UsuarioNivel']) and $_SESSION['UsuarioNivel']=='0') {
    header("Location: admin");
    exit;
} else if (isset($_SESSION) and isset($_SESSION['UsuarioNivel']) and $_SESSION['UsuarioNivel']=='1') {
    header("Location: cliente");
    exit;
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="styleapp.css">
        <script src="js/jquery-1.8.2.js"></script>
        <script src="js/ajaxes.js"></script>
        <title>Basico Tecidos</title>
    </head>
    <body onload="document.getElementById('email').focus();">
        <!--<div id="background"></div>-->
        <div id="corpo">
            <form action="validacao.php" id="form_login">
                <img src="images/logobasico.png">
                <label for="email">E-mail:</label>
                <input type="text" id="email" name="email" />
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" />
                <!--<input type="checkbox" value="1"><span class="manterlogado">Manter-me logado</span></input>-->
                <input type="submit" value="Entrar" />
            </form>
            <div class="mensagem">
                <span></span>
            </div>
        </div>
        <div id="carregando">
            <div class="div">
                <img src="images/249.png">
                <span>Carregando</span>
            </div>
        </div>
    </body>
</html>