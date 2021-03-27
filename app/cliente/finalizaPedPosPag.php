<?
require_once('../configapp.php');

if ($_SERVER['REQUEST_METHOD']=="GET" and $_GET['cod_ped']!="" and $_GET['codepagseg']!="") {
    $cod_ped = $_GET['cod_ped'];
    $sql_code = str_replace('-','-',$_GET['codepagseg']);
    $pesq = mysql_query("select * from pedidos_dados where ativo = '1' and finalizado = '0' and cod_ped = '$cod_ped'") or die(mysql_error());
    if (mysql_num_rows($pesq)>0) {
        $pedido = mysql_fetch_array($pesq);
        $sql_id_cli = $pedido['id_cli'];
        $status = $pedido['id_pos'];
        mysql_query("update pedidos_dados set ativo = '0' where cod_ped = '$cod_ped' and ativo = '1' and finalizado = '0'") or die(mysql_error());
        mysql_query("insert into pedidos_dados (cod_ped,id_cli,id_pos,finalizado,codepagseg,ativo,data,usuario) values ('$cod_ped','$sql_id_cli','$status','1','$sql_code','1',now(),'1')") or die(mysql_error());
    }
}
?>