<?
require_once("configapp.php");

$sql->query("CREATE TABLE IF NOT EXISTS `pos_pedidos` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY) ENGINE = InnoDB") or die(mysqli_error($sql));
	addCol("nome","pos_pedidos","VARCHAR(30) not null","id");
    addCol("ativo","pos_pedidos","tinyint(1) not null","nome");
    addCol("data","pos_pedidos","datetime","ativo");
    addCol("usuario","pos_pedidos","INT not null","data");
    if (mysqli_num_rows($sql->query("select * from pos_pedidos"))==0) {
		$sql->query("INSERT INTO `pos_pedidos`(`nome`, `ativo`, `data`, `usuario`) VALUES ('Aguardando Pagamento',1,now(),'1')") or die(mysqli_error($sql));
		$sql->query("INSERT INTO `pos_pedidos`(`nome`, `ativo`, `data`, `usuario`) VALUES ('Em análise',1,now(),'1')") or die(mysqli_error($sql));
		$sql->query("INSERT INTO `pos_pedidos`(`nome`, `ativo`, `data`, `usuario`) VALUES ('Aprovado',1,now(),'1')") or die(mysqli_error($sql));
		$sql->query("INSERT INTO `pos_pedidos`(`nome`, `ativo`, `data`, `usuario`) VALUES ('Disponível',1,now(),'1')") or die(mysqli_error($sql));
		$sql->query("INSERT INTO `pos_pedidos`(`nome`, `ativo`, `data`, `usuario`) VALUES ('Em disputa',1,now(),'1')") or die(mysqli_error($sql));
		$sql->query("INSERT INTO `pos_pedidos`(`nome`, `ativo`, `data`, `usuario`) VALUES ('Devolvido',1,now(),'1')") or die(mysqli_error($sql));
		$sql->query("INSERT INTO `pos_pedidos`(`nome`, `ativo`, `data`, `usuario`) VALUES ('Cancelado',1,now(),'1')") or die(mysqli_error($sql));
		$sql->query("INSERT INTO `pos_pedidos`(`nome`, `ativo`, `data`, `usuario`) VALUES ('Despachado',1,now(),'1')") or die(mysqli_error($sql));
    }

$sql->query("CREATE TABLE IF NOT EXISTS `pedidos_dados` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY) ENGINE = InnoDB") or die(mysqli_error($sql));
	addCol("cod_ped","pedidos_dados","INT not null","id");
	addCol("id_cli","pedidos_dados","INT not null","cod_ped");
	addCol("id_pos","pedidos_dados","INT not null","id_cli");
	addCol("finalizado","pedidos_dados","INT not null DEFAULT '0'","id_pos");
	addCol("codepagseg","pedidos_dados","VARCHAR(50) NULL DEFAULT ''","finalizado");
    addCol("ativo","pedidos_dados","tinyint(1) not null","codepagseg");
    addCol("data","pedidos_dados","datetime","ativo");
    addCol("usuario","pedidos_dados","INT not null","data");
    
$sql->query("CREATE TABLE IF NOT EXISTS `pedidos_itens` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY) ENGINE = InnoDB") or die(mysqli_error($sql));
	addCol("cod_ped","pedidos_itens","INT not null","id");
	addCol("cod_prod","pedidos_itens","INT not null","cod_ped");
	addCol("descricao","pedidos_itens","VARCHAR(50) not null","cod_prod");
	addCol("uni","pedidos_itens","VARCHAR(5) not null","descricao");
	addCol("quant","pedidos_itens","FLOAT not null","uni");
	addCol("valor","pedidos_itens","FLOAT not null","quant");
	addCol("desconto","pedidos_itens","FLOAT not null","valor");
	addCol("ipi","pedidos_itens","FLOAT not null","desconto");
	addCol("entrega","pedidos_itens","DATE not null","ipi");
	addCol("oc","pedidos_itens","VARCHAR(10)","entrega");
	addCol("subtotal","pedidos_itens","FLOAT not null","oc");
    addCol("ativo","pedidos_itens","tinyint(1) not null","subtotal");
    addCol("data","pedidos_itens","datetime","ativo");
    addCol("usuario","pedidos_itens","INT not null","data");

$sql->query("CREATE TABLE IF NOT EXISTS `dados_apipag` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY) ENGINE = InnoDB") or die(mysqli_error($sql));
	addCol("environment","dados_apipag","VARCHAR(20)","id");
	addCol("email","dados_apipag","VARCHAR(50)","environment");
	addCol("token","dados_apipag","VARCHAR(50)","email");
	addCol("tokensandbox","dados_apipag","VARCHAR(50)","token");
	addCol("ativo","dados_apipag","tinyint(1) not null","tokensandbox");
    addCol("data","dados_apipag","datetime","ativo");
    addCol("usuario","dados_apipag","INT not null","data");
    
$sql->query("CREATE TABLE IF NOT EXISTS `pagseg_meio_pag` (`cod` INT NOT NULL PRIMARY KEY) ENGINE = InnoDB") or die(mysqli_error($sql));
	addCol("descricao","pagseg_meio_pag","VARCHAR(40)","cod");
	if (mysqli_num_rows($sql->query("select * from pagseg_meio_pag"))==0) {
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('101','Cartão de crédito Visa')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('102','Cartão de crédito MasterCard')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('103','Cartão de crédito American Express')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('104','Cartão de crédito Diners')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('105','Cartão de crédito Hipercard')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('106','Cartão de crédito Aura')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('107','Cartão de crédito Elo')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('108','Cartão de crédito PLENOCard')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('109','Cartão de crédito PersonalCard')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('110','Cartão de crédito JCB')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('111','Cartão de crédito Discover')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('112','Cartão de crédito BrasilCard')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('113','Cartão de crédito FORTBRASIL')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('114','Cartão de crédito CARDBAN')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('115','Cartão de crédito VALECARD')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('116','Cartão de crédito Cabal')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('117','Cartão de crédito Mais!')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('118','Cartão de crédito Avista')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('119','Cartão de crédito GRANDCARD')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('120','Cartão de crédito Sorocred')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('201','Boleto Bradesco')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('202','Boleto Santander')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('301','Débito online Bradesco')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('302','Débito online Itaú')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('303','Débito online Unibanco')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('304','Débito online Banco do Brasil')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('305','Débito online Banco Real')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('306','Débito online Banrisul')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('307','Débito online HSBC')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('401','Saldo PagSeguro')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('501','Oi Paggo')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('701','Depósito em conta - Banco do Brasil')") or die(mysqli_error($sql));
		$sql->query("insert into pagseg_meio_pag (cod,descricao) values ('702','Depósito em conta - HSBC')") or die(mysqli_error($sql));
	}
$sql->query("CREATE TABLE IF NOT EXISTS `notificacoes` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY) ENGINE = InnoDB") or die(mysqli_error($sql));
	addCol("tipo","notificacoes","VARCHAR(20)","id");
	addCol("email","notificacoes","TEXT","tipo");
	addCol("ativo","notificacoes","tinyint(1) not null","email");
    addCol("data","notificacoes","datetime","ativo");
    addCol("usuario","notificacoes","INT not null","data");
?>





