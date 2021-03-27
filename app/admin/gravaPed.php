<?
ob_start();
require_once('../configapp.php');
if (!isset($_SESSION)) session_start();

    if ($_SERVER["REQUEST_METHOD"]=="GET") {
        if ($_SESSION['autoriza']['controle_total']==1 or $_SESSION['autoriza']['adicionar_pedidos']==1) {
            $cod_query = mysql_query("select cod_ped from pedidos_dados order by cod_ped desc") or die(mysql_error());
            if (mysql_num_rows($cod_query)>0) {
                $cod = mysql_fetch_array($cod_query);
                $codPed = $cod['cod_ped']+1;
            } else $codPed = 1;
            $serial = $_GET['serial'];
            $codCli = $_GET['cliente'];
            $linha = json_decode($serial);
            $codUser = $_SESSION['UsuarioID'];
            
            mysql_query("insert into pedidos_dados (cod_ped,id_cli,id_pos,ativo,data,usuario) values ('$codPed','$codCli','1','0',now(),'$codUser')") or die(mysql_error());
            $id = mysql_insert_id();
            
            for ($i=0;$i<count($linha);$i++) {
                $coluna = $linha[$i];
                $codProd = $coluna[0];
                $descr = $coluna[1];
                $uni = $coluna[2];
                $quant = $coluna[3];
                $valor = $coluna[4];
                $desconto = $coluna[5];
                $ipi = $coluna[6];
                $dateOld = strtotime(str_replace('/','-',$coluna[7]));
                $entrega = date('Y-m-d',$dateOld);
                $oc = $coluna[8];
                $subtotal = $coluna[9];
                mysql_query("insert into pedidos_itens (cod_ped,cod_prod,descricao,uni,quant,valor,desconto,ipi,entrega,oc,subtotal,ativo,data,usuario) values ('$codPed','$codProd','$descr','$uni','$quant','$valor','$desconto','$ipi','$entrega','$oc','$subtotal','1',now(),'$codUser')") or die(mysql_error());
            }
            mysql_query("update pedidos_dados set ativo = '1' where id = $id") or die(mysql_error());
            // echo "Pedido: ".$codPed.". Id BD: ".$id.".";
            $retorno['success']=1;
        } else {
            $retorno['success']=0;
            $retorno['mensagem']="Não autorizado!";
        }
    }
$retorno["html"] = ob_get_clean();
echo json_encode($retorno);
?>