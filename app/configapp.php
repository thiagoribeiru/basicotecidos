<?
	function addCol($nomeCol,$nomeTab,$tipo,$after) {
		// ($nomeCol,$nomeTab,$tipo,$after,[$stringComando])
		$numArg = func_num_args();
	    $qtabs = mysql_query("show columns from $nomeTab like '$nomeCol'") or die(mysql_error());
	    if (mysql_num_rows($qtabs)==0) {
	        mysql_query("ALTER TABLE $nomeTab ADD COLUMN $nomeCol $tipo after $after;") or die(mysql_error());
	        if ($numArg==5) {
	        	$stringComando = func_get_arg(4);
	        	mysql_query($stringComando) or die(mysql_error());
	        }
	    }
	}
	
	function delCol($nomeCol,$nomeTab) {
	    $qtabs = mysql_query("show columns from $nomeTab like '$nomeCol'") or die(mysql_error());
	    if (mysql_num_rows($qtabs)!=0) {
	        mysql_query("ALTER TABLE $nomeTab DROP $nomeCol") or die(mysql_error());
	    }
	}
	
	function horaSQL () {
		$horaSQL = mysql_fetch_row(mysql_query("select now()"));
		$diferenca = time()-$horaSQL[0];
		$hora = $horaSQL[0]+$diferenca;
		
		return date('Y-m-d H:i:s',$hora);
	}
	
if (count(explode("basicotecidos.com.br",$_SERVER['SERVER_NAME']))>1) {
	error_reporting (E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);
	$servidor='localhost';
	$usuariosql='usuariosql';
	$senhasql='senhasql';
	$banco='banco_de_dados_sql';
	$tempoOcioso = 5*60;
	$dir_padrao = "app";
	
	// Tenta se conectar ao servidor MySQL
	$db = mysql_connect($servidor,$usuariosql,$senhasql) or trigger_error(mysql_error());
	// Tenta se conectar a um banco de dados MySQL
	mysql_select_db($banco) or trigger_error(mysql_error());
	mysql_query("SET TIME_ZONE = 'America/Sao_Paulo'");
	date_default_timezone_set('America/Sao_Paulo');
	
	// Check connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    } else {
    	tabelasIniciais();
    }
}
else if (count(explode("c9user",$_SERVER['SERVER_NAME']))>1) {
	// A simple PHP script demonstrating how to connect to MySQL.
    // Press the 'Run' button on the top to start the web server,
    // then click the URL that is emitted to the Output tab of the console.
	
    $servername = getenv('IP');
    $username = getenv('C9_USER');
    $password = "";
    $database = "c9";
    $dbport = 3306;
    $tempoOcioso = 60*60;
    $dir_padrao = "app";

    // Create connection
    $db = mysql_connect($servername.":".$dbport,substr($username,0,16),$password) or trigger_error(mysql_error());
	// Tenta se conectar a um banco de dados MySQL
	mysql_select_db($database) or trigger_error(mysql_error());
	mysql_query("SET TIME_ZONE = 'America/Sao_Paulo'");
	date_default_timezone_set('America/Sao_Paulo');

    // Check connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    } else {
    	tabelasIniciais();
    }
}
else if (count(explode("ribeirodesenvolvimentoweb.com.br",$_SERVER['SERVER_NAME']))>1) {
	error_reporting (E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);
	$servidor='localhost';
	$usuariosql='c3rdw';
	$senhasql='thi102030';
	$banco='c3basicotecidos';
	$tempoOcioso = 5*60;
	$dir_padrao = "app";
	
	// Tenta se conectar ao servidor MySQL
	$db = mysql_connect($servidor,$usuariosql,$senhasql) or trigger_error(mysql_error());
	// Tenta se conectar a um banco de dados MySQL
	mysql_select_db($banco) or trigger_error(mysql_error());
	mysql_query("SET TIME_ZONE = 'America/Sao_Paulo'");
	date_default_timezone_set('America/Sao_Paulo');
	
	// Check connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    } else {
    	tabelasIniciais();
    }
} else if (count(explode("localhost",$_SERVER['SERVER_NAME']))>1) {
	error_reporting (E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);
	$servidor='localhost';
	$usuariosql='thiago';
	$senhasql='thi102030';
	$banco='basicotecidos';
	$tempoOcioso = 5*60;
	$dir_padrao = "app";
	
	// Tenta se conectar ao servidor MySQL
	$db = mysql_connect($servidor,$usuariosql,$senhasql) or trigger_error(mysql_error());
	// Tenta se conectar a um banco de dados MySQL
	mysql_select_db($banco) or trigger_error(mysql_error());
	mysql_query("SET TIME_ZONE = 'America/Sao_Paulo'");
	date_default_timezone_set('America/Sao_Paulo');
	
	// Check connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    } else {
    	tabelasIniciais();
    }
}
else {
	header('Location: ../error.html');
    exit;
}

function tabelasIniciais() {
        mysql_query("
		CREATE TABLE IF NOT EXISTS niveis_nomes (
			`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`nivel` VARCHAR(40) NOT NULL )
		ENGINE = InnoDB") or die(mysql_error());
		if (mysql_num_rows(mysql_query("select * from niveis_nomes"))==0) {
			mysql_query("INSERT INTO niveis_nomes (nivel) VALUES ('Administradores')") or die(mysql_error());
			mysql_query("update niveis_nomes set id='0' where id='1'") or die(mysql_error());
			mysql_query("INSERT INTO niveis_nomes (id,nivel) VALUES ('1','Clientes')") or die(mysql_error());
			mysql_query("INSERT INTO niveis_nomes (id,nivel) VALUES ('2','Sistema')") or die(mysql_error());
		}
        
		mysql_query("
		CREATE TABLE IF NOT EXISTS usuarios (
			`id` INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`nome` VARCHAR(100) NOT NULL ,
			`senha` VARCHAR(50) NOT NULL ,
			`email` VARCHAR(100) NOT NULL UNIQUE ,
			`nivel` INT(1) NOT NULL ,
			`ativo` TINYINT(1) NOT NULL ,
			`cadastro` DATETIME NOT NULL,
			`primeiro_acesso` TINYINT(1) NOT NULL )
		ENGINE = InnoDB") or die(mysql_error());
		addCol("permissoes","usuarios","TEXT NOT NULL DEFAULT  ''","nivel");
		if (mysql_num_rows(mysql_query("select * from usuarios"))==0) {
			mysql_query("INSERT INTO usuarios(id,nome, senha, email, nivel, ativo, cadastro, primeiro_acesso,permissoes) VALUES ('1','System','77e7e78b05578758626744dcdf57007c71797399','sys','2','0',now(),'0','a:1:{s:14:\"controle_total\";s:1:\"1\";}')") or die(mysql_error());
			mysql_query("INSERT INTO usuarios(nome, senha, email, nivel, ativo, cadastro, primeiro_acesso,permissoes) VALUES ('root','77e7e78b05578758626744dcdf57007c71797399','root','0','1',now(),'1','a:1:{s:14:\"controle_total\";s:1:\"1\";}')") or die(mysql_error());
		}
		
		mysql_query("
		CREATE TABLE IF NOT EXISTS users_logados (
			`id_user` INT NOT NULL ,
			`id_sessao` VARCHAR(40) NOT NULL ,
			`login` DATETIME NOT NULL ,
			`validade` DATETIME NOT NULL )
		ENGINE = InnoDB") or die(mysql_error());
}
function enviaEmail($de,$para,$assunto,$texto) {
    // multiple recipients
    $to  = $para;
    // subject
    $subject = $assunto;
    // message
    $message = "
        <html>
            <head>
                <title></title>
            </head>
            <body style=\"font-family: 'Arial', Georgia, Serif; font-size: 12px;\">
                ".$texto."
            </body>
        </html>
        ";
    
    // To send HTML mail, the Content-type header must be set
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    
    // Additional headers
    // $headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
    $headers .= 'From: Basico Tecidos <'.$de.'>' . "\r\n";
    // $headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
    // $headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
    
    // Mail it
    mail($to, $subject, $message, $headers);
}
?>