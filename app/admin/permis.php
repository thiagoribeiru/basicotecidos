<?require_once('../session.php');?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="../styleapp.css">
        <script src="../js/jquery-1.8.2.js"></script>
        <script src="../js/ajaxes.js"></script>
        <title>Basico Tecidos - Administração</title>
    </head>
    <body popup='sim'>
        <?
        if ($_SESSION['autoriza']['controle_total']==1 or $_SESSION['autoriza']['editar_permissoes']==1) {
            if ($_SERVER['REQUEST_METHOD']=="POST") {
                if ($_POST['controle_total']==1) {
                    $array = array('controle_total'=>'1');
                    mysql_query("update usuarios set permissoes = '".serialize($array)."' where id = '".$_GET['user']."'") or die (mysql_error());
                } else {
                    $array = $_POST;
                    mysql_query("update usuarios set permissoes = '".serialize($array)."' where id = '".$_GET['user']."'") or die (mysql_error());
                }
            }
            
            $permisPesq = mysql_query("select nome, permissoes as permi from usuarios where id = '".$_GET['user']."' and nivel = '0' and id != '0'") or die (mysql_error());
            if (mysql_num_rows($permisPesq)>0) {
                $permisFetch = mysql_fetch_array($permisPesq);
                $permis = unserialize($permisFetch['permi']);
                $checked = " checked='checked'";
            ?>
            <style>
                ul {
                    list-style-type: none;
                }
                ul ul {
                    margin-left: 10px;
                    padding-left: 10px;
                }
                #primeiro_nivel {
                    margin-left: 5px;
                    padding-left: 5px;
                }
            </style>
            <fieldset>
                <legend><b>Permissões de <?echo $permisFetch['nome'];?></b></legend>
                <form action="permis.php?user=<?echo $_GET['user'];?>" method="POST">
                    <ul id="primeiro_nivel">
                        <li><input type="checkbox" name="controle_total" value="1"<?if ($permis['controle_total']==1) echo $checked;?>>Controle Total</input></li>
                        <li><input type="checkbox" name="configuracoes" value="1"<?if ($permis['configuracoes']==1) echo $checked;?>>Configurações</input></li>
                        <ul>
                            <li><input type="checkbox" name="usuarios" value="1"<?if ($permis['usuarios']==1) echo $checked;?>>Usuários</input></li>
                            <ul>
                                <li><input type="checkbox" name="adicionar_usuario" value="1"<?if ($permis['adicionar_usuario']==1) echo $checked;?>>Adicionar usuário</input></li>
                                <li><input type="checkbox" name="editar_usuario" value="1"<?if ($permis['editar_usuario']==1) echo $checked;?>>Editar usuário</input></li>
                                <ul>
                                    <li><input type="checkbox" name="editar_dados" value="1"<?if ($permis['editar_dados']==1) echo $checked;?>>Editar dados</input></li>
                                    <li><input type="checkbox" name="ativar_desativar" value="1"<?if ($permis['ativar_desativar']==1) echo $checked;?>>Ativar/Desativar</input></li>
                                    <li><input type="checkbox" name="editar_permissoes" value="1"<?if ($permis['editar_permissoes']==1) echo $checked;?>>Editar permissões</input></li>
                                </ul>
                                <!--<li><input type="checkbox" name="resetar_senha" value="1"<?if ($permis['resetar_senha']==1) echo $checked;?>>Resetar senha</input></li>-->
                            </ul>
                            <li><input type="checkbox" name="parametros" value="1"<?if ($permis['parametros']==1) echo $checked;?>>Parâmetros</input></li>
                            <ul>
                                <li><input type="checkbox" name="pagseguro" value="1"<?if ($permis['pagseguro']==1) echo $checked;?>>PagSeguro</input></li>
                                <ul>
                                    <li><input type="checkbox" name="editar_operacao" value="1"<?if ($permis['editar_operacao']==1) echo $checked;?>>Editar operação</input></li>
                                    <li><input type="checkbox" name="editar_email_vendedor" value="1"<?if ($permis['editar_email_vendedor']==1) echo $checked;?>>Editar e-mail vendedor</input></li>
                                    <li><input type="checkbox" name="exibir_dados_confidenciais" value="1"<?if ($permis['exibir_dados_confidenciais']==1) echo $checked;?>>Exibir dados confidenciais</input></li>
                                    <ul>
                                        <li><input type="checkbox" name="editar_token" value="1"<?if ($permis['editar_token']==1) echo $checked;?>>Editar token</input></li>
                                        <li><input type="checkbox" name="editar_token_sandbox" value="1"<?if ($permis['editar_token_sandbox']==1) echo $checked;?>>Editar token Sandbox</input></li>
                                    </ul>
                                </ul>
                                <li><input type="checkbox" name="notificacoes_pos_email" value="1"<?if ($permis['notificacoes_pos_email']==1) echo $checked;?>>Notificações por E-mail</input></li>
                                <ul>
                                    <li><input type="checkbox" name="editar_remetente" value="1"<?if ($permis['editar_remetente']==1) echo $checked;?>>Editar remetente</input></li>
                                    <li><input type="checkbox" name="editar_emails_de_notificacoes" value="1"<?if ($permis['editar_emails_de_notificacoes']==1) echo $checked;?>>Editar e-mails de notificações</input></li>
                                </ul>
                            </ul>
                        </ul>
                        <li><input type="checkbox" name="vendas" value="1"<?if ($permis['vendas']==1) echo $checked;?>>Vendas</input></li>
                        <ul>
                            <li><input type="checkbox" name="pedidos" value="1"<?if ($permis['pedidos']==1) echo $checked;?>>Pedidos</input></li>
                            <ul>
                                <li><input type="checkbox" name="adicionar_pedidos" value="1"<?if ($permis['adicionar_pedidos']==1) echo $checked;?>>Adicionar pedidos</input></li>
                                <li><input type="checkbox" name="remover_pedidos" value="1"<?if ($permis['remover_pedidos']==1) echo $checked;?>>Remover pedidos</input></li>
                            </ul>
                        </ul>
                    </ul>
                    <input type="submit" value="Salvar" style="float: right;" />
                </form>
            </fieldset>
            <?}?>
        <?}?>
    </body>
</html>