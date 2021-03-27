<?
if (isset($_GET) and $_GET['id']!='') {
    require_once("../configapp.php");
    $cli_query = mysql_query("select * from usuarios where ativo = 1 and nivel = 1 and id = ".$_GET['id']) or die (mysql_error());
    if (mysql_num_rows($cli_query)==1) {
        $cliente = mysql_fetch_array($cli_query);
        
        $retorno['error'] = 0;
        $retorno['id'] = $cliente['id'];
        $retorno['nome'] = $cliente['nome'];
        $retorno['email'] = $cliente['email'];
        
        echo json_encode($retorno);
        exit;
    } else if (mysql_num_rows($cli_query)==0) {
        $retorno['error'] = 1;
        $retorno['mensagem'] = "Cliente inexistente ou desativado.";
        
        echo json_encode($retorno);
        exit;
    } else {
        $retorno['error'] = 1;
        $retorno['mensagem'] = "Sistema de usuários inconsistente para este id. Favor contactar o administrador do sistema.";
        
        echo json_encode($retorno);
        exit;
    }
} else {
    $retorno['error'] = 1;
    $retorno['mensagem'] = "Erro de request(id).";
    
    echo json_encode($retorno);
    exit;
}
?>