<?
require_once('../configapp.php');
if (!isset($_SESSION)) session_start();

if (isset($_GET) and $_GET['nome']!='' and $_GET['email']!='' and $_GET['nivel']!='') {
    $name = preg_replace('/\d/', '', $_GET['nome']);
    $name = preg_replace('/[\n\t\r]/', ' ', $name);
    $name = preg_replace('/\s(?=\s)/', '', $name);
    $name = trim($name);
    $nameEx = explode(' ', $name);
     
    if(count($nameEx) > 1 ) {
        if ($_GET['function']=='insert') {
            if ($_SESSION['autoriza']['controle_total']==1 or $_SESSION['autoriza']['adicionar_usuario']==1) {
                $nome = $name;
                $email = strtolower($_GET['email']);
                $nivel = $_GET['nivel'];
                
                mysql_query("insert into usuarios (nome, senha, email, nivel, ativo, cadastro, primeiro_acesso) values ('$nome','".sha1("12345")."','$email','$nivel','1',now(),'1')") or die(mysql_error());
                
                $retorno['autoriza'] = 1;
                $retorno['mensagem'] = "Cadastro efetuado! Primeiro acesso, senha: 12345";
                echo json_encode($retorno);
                
                $emailFromPesq = mysql_query("select email from notificacoes where tipo = 'remetenteNoti' and ativo = '1'") or die (mysql_error());
                $emailNotiPesq = mysql_query("select email from notificacoes where tipo = 'novoCliente' and ativo = '1'") or die (mysql_error());
                if (mysql_num_rows($emailFromPesq)>0 and mysql_num_rows($emailNotiPesq)>0) {
                    $de = mysql_fetch_array($emailFromPesq);
                    $para = mysql_fetch_array($emailNotiPesq);
                    $texto = 'Foi cadastrado um usuário '.$nome.' com e-mail '.$email.'.';
                    enviaEmail($de['email'],$para['email'],'Novo Cadastro de Usuario',$texto);
                }
                
                exit;
            } else {
                $retorno['autoriza'] = 0;
                $retorno['mensagem'] = "Não autorizado!";
                echo json_encode($retorno);
                exit;
            }
        } else if ($_GET['function']=='update') {
            if ($_SESSION['autoriza']['controle_total']==1 or $_SESSION['autoriza']['editar_dados']==1) {
                $id = $_GET['id'];
                $nome = $name;
                $email = strtolower($_GET['email']);
                $nivel = $_GET['nivel'];
                
                mysql_query("update usuarios set nome='$nome', email='$email', nivel='$nivel' where id = '$id'") or die(mysql_error());
                
                $retorno['autoriza'] = 1;
                $retorno['mensagem'] = "Alteração efetuada!";
                echo json_encode($retorno);
                
                $emailFromPesq = mysql_query("select email from notificacoes where tipo = 'remetenteNoti' and ativo = '1'") or die (mysql_error());
                $emailNotiPesq = mysql_query("select email from notificacoes where tipo = 'config' and ativo = '1'") or die (mysql_error());
                if (mysql_num_rows($emailFromPesq)>0 and mysql_num_rows($emailNotiPesq)>0) {
                    $de = mysql_fetch_array($emailFromPesq);
                    $para = mysql_fetch_array($emailNotiPesq);
                    $texto = 'O usuario '.$nome.' - '.$email.' sofreu alterações.';
                    enviaEmail($de['email'],$para['email'],'Alteração de Usuario',$texto);
                }
                
                exit;
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
        $retorno['mensagem'] = "Por favor. Preencha nome e sobrenome!";
        echo json_encode($retorno);
        exit;
    }
} else {
    $retorno['autoriza'] = 0;
    $retorno['mensagem'] = "Preencha todos os campos!";
    echo json_encode($retorno);
    exit;
}
?>