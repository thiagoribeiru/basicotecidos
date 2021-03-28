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
        if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1)==1 or (isset($_SESSION['autoriza']['editar_permissoes']) and $_SESSION['autoriza']['editar_permissoes']==1)) {
            if ($_SERVER['REQUEST_METHOD']=="POST") {
                if (isset($_POST['controle_total']) and $_POST['controle_total']==1) {
                    $array = array('controle_total'=>'1');
                    $sql->query("update usuarios set permissoes = '".serialize($array)."' where id = '".$_GET['user']."'") or die (mysqli_error($sql));
                } else {
                    $array = $_POST;
                    $sql->query("update usuarios set permissoes = '".serialize($array)."' where id = '".$_GET['user']."'") or die (mysqli_error($sql));
                }
            }
            
            $permisPesq = $sql->query("select nome, permissoes as permi from usuarios where id = '".$_GET['user']."' and nivel = '0' and id != '0'") or die (mysqli_error($sql));
            if (mysqli_num_rows($permisPesq)>0) {
                $permisFetch = mysqli_fetch_array($permisPesq);
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
                        <li><input type="checkbox" name="controle_total" value="1"<?if (isset($permis['controle_total']) and $permis['controle_total']==1) echo $checked;?>>Controle Total</input></li>
                        <li><input type="checkbox" name="configuracoes" value="1"<?if (isset($permis['configuracoes']) and $permis['configuracoes']==1) echo $checked;?>>Configurações</input></li>
                        <ul>
                            <li><input type="checkbox" name="usuarios" value="1"<?if (isset($permis['usuarios']) and $permis['usuarios']==1) echo $checked;?>>Usuários</input></li>
                            <ul>
                                <li><input type="checkbox" name="adicionar_usuario" value="1"<?if (isset($permis['adicionar_usuario']) and $permis['adicionar_usuario']==1) echo $checked;?>>Adicionar usuário</input></li>
                                <li><input type="checkbox" name="editar_usuario" value="1"<?if (isset($permis['editar_usuario']) and $permis['editar_usuario']==1) echo $checked;?>>Editar usuário</input></li>
                                <ul>
                                    <li><input type="checkbox" name="editar_dados" value="1"<?if (isset($permis['editar_dados']) and $permis['editar_dados']==1) echo $checked;?>>Editar dados</input></li>
                                    <li><input type="checkbox" name="ativar_desativar" value="1"<?if (isset($permis['ativar_desativar']) and $permis['ativar_desativar']==1) echo $checked;?>>Ativar/Desativar</input></li>
                                    <li><input type="checkbox" name="editar_permissoes" value="1"<?if (isset($permis['editar_permissoes']) and $permis['editar_permissoes']==1) echo $checked;?>>Editar permissões</input></li>
                                </ul>
                                <!--<li><input type="checkbox" name="resetar_senha" value="1"<?if (isset($permis['resetar_senha']) and $permis['resetar_senha']==1) echo $checked;?>>Resetar senha</input></li>-->
                            </ul>
                            <li><input type="checkbox" name="parametros" value="1"<?if (isset($permis['parametros']) and $permis['parametros']==1) echo $checked;?>>Parâmetros</input></li>
                            <ul>
                                <li><input type="checkbox" name="pagseguro" value="1"<?if (isset($permis['pagseguro']) and $permis['pagseguro']==1) echo $checked;?>>PagSeguro</input></li>
                                <ul>
                                    <li><input type="checkbox" name="editar_operacao" value="1"<?if (isset($permis['editar_operacao']) and $permis['editar_operacao']==1) echo $checked;?>>Editar operação</input></li>
                                    <li><input type="checkbox" name="editar_email_vendedor" value="1"<?if (isset($permis['editar_email_vendedor']) and $permis['editar_email_vendedor']==1) echo $checked;?>>Editar e-mail vendedor</input></li>
                                    <li><input type="checkbox" name="exibir_dados_confidenciais" value="1"<?if (isset($permis['exibir_dados_confidenciais']) and $permis['exibir_dados_confidenciais']==1) echo $checked;?>>Exibir dados confidenciais</input></li>
                                    <ul>
                                        <li><input type="checkbox" name="editar_token" value="1"<?if (isset($permis['editar_token']) and $permis['editar_token']==1) echo $checked;?>>Editar token</input></li>
                                        <li><input type="checkbox" name="editar_token_sandbox" value="1"<?if (isset($permis['editar_token_sandbox']) and $permis['editar_token_sandbox']==1) echo $checked;?>>Editar token Sandbox</input></li>
                                    </ul>
                                </ul>
                                <li><input type="checkbox" name="notificacoes_pos_email" value="1"<?if (isset($permis['notificacoes_pos_email']) and $permis['notificacoes_pos_email']==1) echo $checked;?>>Notificações por E-mail</input></li>
                                <ul>
                                    <li><input type="checkbox" name="editar_remetente" value="1"<?if (isset($permis['editar_remetente']) and $permis['editar_remetente']==1) echo $checked;?>>Editar remetente</input></li>
                                    <li><input type="checkbox" name="editar_emails_de_notificacoes" value="1"<?if (isset($permis['editar_emails_de_notificacoes']) and $permis['editar_emails_de_notificacoes']==1) echo $checked;?>>Editar e-mails de notificações</input></li>
                                </ul>
                            </ul>
                        </ul>
                        <li><input type="checkbox" name="vendas" value="1"<?if (isset($permis['vendas']) and $permis['vendas']==1) echo $checked;?>>Vendas</input></li>
                        <ul>
                            <li><input type="checkbox" name="pedidos" value="1"<?if (isset($permis['pedidos']) and $permis['pedidos']==1) echo $checked;?>>Pedidos</input></li>
                            <ul>
                                <li><input type="checkbox" name="adicionar_pedidos" value="1"<?if (isset($permis['adicionar_pedidos']) and $permis['adicionar_pedidos']==1) echo $checked;?>>Adicionar pedidos</input></li>
                                <li><input type="checkbox" name="remover_pedidos" value="1"<?if (isset($permis['remover_pedidos']) and $permis['remover_pedidos']==1) echo $checked;?>>Remover pedidos</input></li>
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