<?require_once('../session.php');?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="../styleapp.css">
        <script src="../js/jquery-1.8.2.js"></script>
        <script src="../js/ajaxes.js"></script>
        <title>Basico Tecidos - Administração</title>
    </head>
    <body popup='sim'>
        <?
        // require_once('../session.php');
        if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['adicionar_usuario']) and $_SESSION['autoriza']['adicionar_usuario']==1)) {
        ?>
        <form id="form_addUsers">
            <fieldset><legend>Cadastro de Usuários:</legend>
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" /><br>
                <label for="email">E-mail:</label>
                <input type="text" name="email" id="email" /><br>
                <label for="nivel">Nivel:</label>
                <div class="radio">
                    <input type="radio" name="nivel" id="nivel" value="0">Administrador</input>
                    <input type="radio" name="nivel" id="nivel" value="1">Cliente</input>
                </div>
                <br>
                <input type="submit" value="Cadastrar">
            </fieldset>
        </form>
        <?}?>
    </body>
</html>