<?
require_once("configapp.php");

// Verifica se houve GET e se o usuário ou a senha é(são) vazio(s)
if (!empty($_GET) AND (empty($_GET['email']) OR empty($_GET['senha']))) {
  $retorno['autoriza'] = 0;
  $retorno['mensagem'] = "Erro! Os dados não foram recebidos pelo servidor. Contate o administrador do sistema!";
  echo json_encode($retorno);
  exit;
}

//variaveis novas recebem o que foi GETado no formulário
$email = mysql_real_escape_string($_GET['email']);
$senha = strtoupper(mysql_real_escape_string($_GET['senha']));

// Validação do usuário/senha digitados
$sql = "SELECT `id`, `nome`, `nivel`, `ativo`, `primeiro_acesso`, `permissoes` FROM `usuarios` WHERE (`email` = '". $email ."') AND (`senha` = '". sha1($senha) ."') AND (`ativo` = 1) LIMIT 1";
$query = mysql_query($sql);
if (mysql_num_rows($query) != 1) {
  // Mensagem de erro quando os dados são inválidos e/ou o usuário não foi encontrados
  $retorno['autoriza'] = 0;
  $retorno['mensagem'] = "Usuário ou senha inválidos! Se achar necessário, entre em contato!";
  echo json_encode($retorno);
  exit;
} else {
  // Salva os dados encontados na variável $resultado
  $resultado = mysql_fetch_assoc($query);
  
   // Se a sessão não existir, inicia uma
  if (!isset($_SESSION)) session_start();
  // Salva os dados encontrados na sessão
  $_SESSION['UsuarioID'] = $resultado['id'];
  $_SESSION['UsuarioNome'] = $resultado['nome'];
  $_SESSION['UsuarioNivel'] = $resultado['nivel'];
  $_SESSION['UsuarioSitu'] = $resultado['ativo'];
  $_SESSION['primeiro_acesso'] = $resultado['primeiro_acesso'];
  $_SESSION['autoriza'] = unserialize($resultado['permissoes']);
  
  //criador de tabelas
  require_once("tabelas.php");
  
  $numIds = mysql_num_rows(mysql_query("select id_user from users_logados where id_user = ".$_SESSION['UsuarioID']));
  if ($numIds==0){
	  mysql_query("insert into users_logados (id_user, id_sessao, login, validade) 
	  values ('".$_SESSION['UsuarioID']."','".session_id()."',now(),date_add(now(), interval $tempoOcioso second))") or die (mysql_error());
  }
  if ($numIds==1) {
	  mysql_query("update users_logados set id_sessao = '".session_id()."', validade = date_add(now(), interval $tempoOcioso second) where id_user = ".$_SESSION['UsuarioID']) or die (mysql_error());
  }
  if ($numIds>1) {header("Location: logout.php"); exit;}
  
  //limpa usuarios com validade vencida a mais de 30minutos
  mysql_query("delete from users_logados where now() > date_add(validade, interval 1 minute)") or die (mysql_error());
  
  // Retorna autorização
  $retorno['autoriza'] = 1;
  $retorno['mensagem'] = "Tudo certo! Aguarde, iremos redirecioná-lo!";
    //verifica nivel do usuario e o direcionamento correto
    if ($_SESSION['UsuarioNivel']==0) {
      $retorno['index'] = "admin/index.php";
    } else if ($_SESSION['UsuarioNivel']==1) {
      $retorno['index'] = "cliente/index.php";
    }
  echo json_encode($retorno);
  exit;
}
?>