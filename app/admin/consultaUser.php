<?
require_once('../configapp.php');
if (isset($_GET) and $_GET['id']!="") {
    $consultaQuery = $sql->query("select * from usuarios where id = '".$_GET['id']."'") or die (mysqli_error($sql));
    if (mysqli_num_rows($consultaQuery)==1) {
        $consultaResult = mysqli_fetch_array($consultaQuery);
        $retorno['id'] = $consultaResult['id'];
        $retorno['nome'] = $consultaResult['nome'];
        $retorno['email'] = $consultaResult['email'];
        $retorno['nivel'] = $consultaResult['nivel'];
        $retorno['ativo'] = $consultaResult['ativo'];
        echo json_encode($retorno);
        exit;
    } else {
        $retorno['error'] = 1;
        $retorno['mensagem'] = "Erro 02 Consulta de Usuário: o número de retornos não é unico.";
        echo json_encode($retorno);
        exit;
    }
} else {
    $retorno['error'] = 1;
    $retorno['mensagem'] = "Erro 01 Consulta de Usuário: número id não informado.";
    echo json_encode($retorno);
    exit;
}
?>