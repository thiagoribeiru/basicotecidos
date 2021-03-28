<?
require_once("configapp.php");
//criador de tabelas
//   require_once("tabelas.php");

function redireciona_login($dir_pad) {
    $dir_explode = explode('/',getcwd());
    $posicao = count($dir_explode)-1;
    $diretorio = $dir_explode[$posicao];
    if ($diretorio==$dir_pad) {
        header("Location: login.php");
    } else {
        $num_retornos = $posicao - array_search($dir_pad,$dir_explode);
        $orient = "";
        for ($i=0;$i<$num_retornos;$i++) {
            $orient = $orient."../";
        }
        header("Location: ".$orient."login.php");
    }
}
function redireciona_logout($dir_pad) {
    $dir_explode = explode('/',getcwd());
    $posicao = count($dir_explode)-1;
    $diretorio = $dir_explode[$posicao];
    if ($diretorio==$dir_pad) {
        header("Location: logout.php");
    } else {
        $num_retornos = $posicao - array_search($dir_pad,$dir_explode);
        $orient = "";
        for ($i=0;$i<$num_retornos;$i++) {
            $orient = $orient."../";
        }
        header("Location: ".$orient."logout.php");
    }
}
function somaPedido($cod_ped) {
    global $sql;
    $total = 0;
    $query = $sql->query("select subtotal from pedidos_itens where cod_ped = '$cod_ped'") or die(mysqli_error($sql));
    if (mysqli_num_rows($query)>0) {
        for ($i=0;$i<mysqli_num_rows($query);$i++) {
            $valor = mysqli_fetch_array($query);
            $total += $valor['subtotal'];
        }
    }
    return $total;
}
function mascara_string($mascara,$string) {
	$string = str_replace(" ","",$string);
    $digitos = strlen($mascara);
    $posStr = strlen($string)-1;
    for ($i=$digitos-1;$i>=0;$i--) {
        if ($mascara[$i]=="#" and $posStr>=0) {
            $mascara[$i] = $string[$posStr];
            $posStr--;
        } else if ($mascara[$i]=="#" and $posStr<0) {
            $mascara[$i] = "0";
        }
    }
    if ($posStr>=0) {
        for ($j=$posStr;$j>=0;$j--) {
            $mascara = $string[$j].$mascara;
        }
    }
    return $mascara;
}
function subTotal($quant,$valor,$desc,$ipi) {
    $subtotal = (($quant*$valor)-(($quant*$valor)*($desc/100)))+((($quant*$valor)-(($quant*$valor)*($desc/100)))*($ipi/100));
    return $subtotal;
}

if (!isset($_SESSION)) session_start();
if (count($_SESSION)==0) {
    redireciona_login($dir_padrao);
    exit;
} else {
    $url_vet = explode('/',str_replace('\\','/',getcwd()));
    $pos_pasta = array_search($dir_padrao,$url_vet)+1;
    if ($_SESSION['primeiro_acesso']==1) {
        $pag = explode('/',$_SERVER['REQUEST_URI']);
        $pos = count($pag)-1;
        if ($pag[$pos]!="primeiro_acesso.php" and $pag[$pos-1]=='app') {
            header("Location: primeiro_acesso.php");
            exit;
        } else if ($pag[$pos]!="primeiro_acesso.php" and $pag[$pos-1]!='app') {
            header("Location: ../primeiro_acesso.php");
            exit;
        }
    } else {
        if ($_SESSION['UsuarioNivel']==0 and $url_vet[$pos_pasta]!='admin') {
            redireciona_login($dir_padrao);
            exit;
        } else if ($_SESSION['UsuarioNivel']==1 and $url_vet[$pos_pasta]!='cliente') {
            redireciona_login($dir_padrao);
            exit;
        }
    }
    // $pesq = mysqli_fetch_array($sql->query("select permissoes from usuarios where id = '".$_SESSION['UsuarioID']."'")) or die (mysqli_error($sql));
    // $_SESSION['autoriza'] = unserialize($pesq['permissoes']);
}
?>