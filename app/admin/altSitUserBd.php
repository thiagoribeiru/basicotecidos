<?
require_once('../configapp.php');
if (!isset($_SESSION)) session_start();

if (isset($_GET) and $_GET['id']!='') {
    if ($_GET['function']=='update') {
        if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['ativar_desativar']) and $_SESSION['autoriza']['ativar_desativar']==1)) {
            $id = $_GET['id'];
            
            $sit_query = mysqli_fetch_array($sql->query("select ativo from usuarios where id = '$id'")) or die (mysqli_error($sql));
            
            if ($sit_query['ativo']=='0') {
                $sql->query("update usuarios set ativo = 1 where id = '$id'") or die (mysqli_error($sql));
                $retorno['autoriza'] = 1;
                $retorno['sit_user'] = 1;
                echo json_encode($retorno);
                exit;
            } else if ($sit_query['ativo']=='1') {
                $sql->query("update usuarios set ativo = 0 where id = '$id'") or die (mysqli_error($sql));
                $retorno['autoriza'] = 1;
                $retorno['sit_user'] = 0;
                echo json_encode($retorno);
                exit;
            }
        } else {
            $retorno['autoriza'] = 0;
            $retorno['mensagem'] = "Não autorizado!";
            echo json_encode($retorno);
            exit;
        }
    } else {
        $retorno['autoriza'] = 0;
        $retorno['mensagem'] = "Method error!";
        echo json_encode($retorno);
        exit;
    }
} else {
    $retorno['autoriza'] = 0;
    $retorno['mensagem'] = "Erro de parâmetro ou id não recebida!";
    echo json_encode($retorno);
    exit;
}
?>