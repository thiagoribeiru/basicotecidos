<?php
	require_once("configapp.php");
		
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
		
		session_start();
	
		if (!isset($_SESSION['UsuarioID'])) {
			redireciona_logout($dir_padrao);
			exit;
		}
		//pesquisa usuario bd
		$userPesq = $sql->query("select id_user, id_sessao, validade from users_logados where id_user = ".$_SESSION['UsuarioID']);
		$userSit = mysqli_fetch_array($userPesq);
		
		//verifica se ainda tem sessao aberta
		$numIds = mysqli_num_rows($userPesq);
			if (($numIds>0) and ($userSit['id_sessao']==session_id()))
				$sql->query("delete from users_logados where id_user = ".$_SESSION['UsuarioID']) or die(mysqli_error($sql));
			
		session_destroy(); 
	redireciona_login($dir_padrao);
	exit;
?>