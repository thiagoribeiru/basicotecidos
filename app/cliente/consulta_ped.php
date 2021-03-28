<?
require_once("../configapp.php");

if($_SERVER['REQUEST_METHOD']=="GET") {
    $cod = $_GET['cod'];
    $codQuery = $sql->query("select cod_ped, finalizado from pedidos_dados where cod_ped = '$cod' and ativo = 1") or die(mysqli_error($sql));
    if (mysqli_num_rows($codQuery)>0) {
        $pedido = mysqli_fetch_array($codQuery);
        $retorno['cod'] = $pedido['cod_ped'];
        $retorno['finalizado'] = $pedido['finalizado'];
        $tabelaQuery = $sql->query("select cod_prod, descricao, uni, quant, valor, desconto, ipi, entrega, oc, subtotal from pedidos_itens where ativo = 1 and cod_ped = '$cod'") or die(mysqli_error($sql));
        if (mysqli_num_rows($tabelaQuery)>0) {
            for ($i=0;$i<mysqli_num_rows($tabelaQuery);$i++) {
                $tabela[$i] = mysqli_fetch_row($tabelaQuery);
            }
            $retorno['tabela'] = $tabela;
            
            $retorno['error'] = 0;
            echo json_encode($retorno);    
        } else {
            $retorno['error'] = 1;
            $retorno['mensagem'] = "Pedido sem nenhum item.";
            echo json_encode($retorno);
        }
    } else {
        $retorno['error'] = 1;
        $retorno['mensagem'] = "Este pedido não consta mais na base de dados.";
        echo json_encode($retorno);
    }
} else {
    $retorno['error'] = 1;
    $retorno['mensagem'] = "Error: REQUEST_METHOD.";
    echo json_encode($retorno);
}
?>