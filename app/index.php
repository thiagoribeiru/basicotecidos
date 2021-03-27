<?require_once('session.php');?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Basico Tecidos</title>
    </head>
    <body>
        <?
            if ($_SESSION['nivel']==0) header("Location: admin/");
            else if ($_SESSION['nivel']==1) header("Location: cliente/");
            else header("Location: logout.php");
        ?>
    </body>
</html>