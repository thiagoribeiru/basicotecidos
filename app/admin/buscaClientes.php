<?
require_once("../configapp.php");

$cli_query = $sql->query("select id, nome, email from usuarios where ativo = 1 and nivel = 1 order by nome") or die(mysqli_error);
if (mysqli_num_rows($cli_query)>0) {
    echo "<input id=\"filtro\" placeholder=\"pesquisar...\" />\n";
    echo "<table id=\"list_cli\">\n";
        echo "<thead>\n";
            echo "<tr>\n";
                echo "<td width=\"70px\">Cód. Cli</td>\n";
                echo "<td width=\"215px\">Nome</td>\n";
                echo "<td width=\"215px\">E-mail/Login</td>\n";
            echo "</tr>\n";
        echo "</thead>\n";
        echo "<tbody>\n";
        for ($i=0;$i<mysqli_num_rows($cli_query);$i++) {
            $cliente = mysqli_fetch_array($cli_query);
            echo "<tr atId=\"".$cliente['id']."\" atNome=\"".$cliente['nome']."\" atEmail=\"".$cliente['email']."\">\n";
                echo "<td class=\"id\">".$cliente['id']."</td>\n";
                echo "<td class=\"nome\">".$cliente['nome']."</td>\n";
                echo "<td class=\"email\">".$cliente['email']."</td>\n";
            echo "</tr>\n";
        }
        echo "</tbody>\n";
    echo "</table>\n";
} else {
    echo "<div id=\"vazio\">Nenhum item encontrado.</div>\n";
}
?>