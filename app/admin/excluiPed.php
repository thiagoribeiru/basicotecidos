<?
ob_start();
require_once('../configapp.php');
if (!isset($_SESSION)) session_start();

    if ($_SERVER["REQUEST_METHOD"]=="GET") {
        if ($_SESSION['autoriza']['controle_total']==1 or $_SESSION['autoriza']['remover_pedidos']==1) {
            $cod_ped = $_GET['cod_ped'];
            $query = mysql_query("select * from pedidos_dados where cod_ped = '$cod_ped' and ativo = '1'") or die(mysql_error());
            $numLinhas = mysql_num_rows($query);
            if ($numLinhas==1) {
                $ped = mysql_fetch_array($query);
                if ($ped['finalizado']=='0') {
                    $id_cli = $ped['id_cli'];
                    $id_pos = $ped['id_pos'];
                    $usuario = $_SESSION['UsuarioID'];
                    mysql_query("update pedidos_dados set ativo = '0' where cod_ped = '$cod_ped'") or die(mysql_query());
                    mysql_query("insert into pedidos_dados (cod_ped,id_cli,id_pos,ativo,data,usuario) values ('$cod_ped','$id_cli','$id_pos','0',now(),'$usuario')") or die(mysql_query());
                    $retorno['success']=1;
                } else {
                    echo "O pedido não pode ser excluido pois já foi finalizado.";
                    $retorno['success']=0;
                }
            } else if ($numLinhas==0) {
                echo "O Sql não retornou nenhum registro. Favor recarregar página e se o item continuar na lista, contacte o administrador.";
                $retorno['success']=0;
            } else {
                echo "O Sql retornou mais de um registro. Favor contactar o administrador.";
                $retorno['success']=0;
            }
        } else {
            echo "Não autorizado!";
            $retorno['success']=0;    
        }
    }
$retorno["html"] = ob_get_clean();
echo json_encode($retorno);
?>