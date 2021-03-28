<?require_once('../session.php');?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="../styleapp.css">
        <script src="../js/jquery-1.8.2.js"></script>
        <script src="../js/ajaxes.js"></script>
        <script src="../js/tablesorter.min.js"></script>
        <title>Basico Tecidos - Administração</title>
        <style>
            fieldset {
                margin-bottom: 10px;
            }
            fieldset table {
                font-size: 12px;
            }
            fieldset legend {
                font-weight: bold;
                font-size: 13px;
            }
            fieldset label {
                font-weight: bold;
            }
            input[type="submit"] {
                float: right;
            }
            a {
                color: #333;
            }
            a:hover {
                text-decoration: underline;
            }
            textarea {
                resize: none;
                font-family: Arial,Helvetica,Tahoma,sans-serif;
                font-size: 12px;
            }
        </style>
    </head>
    <body>
        <?
        // require_once('../session.php');
        require_once('../barra_menu.php');
        
        if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['parametros']) and $_SESSION['autoriza']['parametros']==1)) {
        
        $alertpagseg = "";
        if ($_SERVER['REQUEST_METHOD']=='POST') {
            $sql_env = (isset($_POST['environment'])) ? $_POST['environment'] : "";
            $sql_email = (isset($_POST['e-mail'])) ? $_POST['e-mail'] : "";
            $sql_token = (isset($_POST['token'])) ? $_POST['token'] : "";
            $sql_tokensandbox = (isset($_POST['tokensandbox'])) ? $_POST['tokensandbox'] : "";
            $sql_usuario = (isset($_SESSION['UsuarioID'])) ? $_SESSION['UsuarioID'] : "";
            $sql_statusPagSeg = (isset($_POST['statusPagSeg'])) ? $_POST['statusPagSeg'] : "";
            $sql_novoCliente = (isset($_POST['novoCliente'])) ? $_POST['novoCliente'] : "";
            $sql_config = (isset($_POST['config'])) ? $_POST['config'] : "";
            
            $statusPagSegPesq = $sql->query("select * from dados_apipag where ativo = '1'") or die (mysqli_error($sql));
            if (mysqli_num_rows($statusPagSegPesq)>0) {
                $statusPagSeg = mysqli_fetch_array($statusPagSegPesq);
                $ant_env = $statusPagSeg['environment'];
                $ant_email = $statusPagSeg['email'];
                $ant_token = $statusPagSeg['token'];
                $ant_tokensandbox = $statusPagSeg['tokensandbox'];
                $pagcompleto = 0;
            } else {
                $ant_env = "";
                $ant_email = "";
                $ant_token = "";
                $ant_tokensandbox = "";
                $pagcompleto = 1;
            }
            
            $pscampos = "";
            $psvalues = "";
            $alteracoes = 0;
            $campovazio = 0;
            $unauthorized = 0;
            if ($sql_env!=$ant_env and $sql_env!="") {
                $pscampos .= "environment";
                $psvalues .= "'$sql_env'";
                $alteracoes ++;
                if ((!isset($_SESSION['autoriza']['controle_total']) or $_SESSION['autoriza']['controle_total']!=1) and (!isset($_SESSION['autoriza']['editar_operacao']) or $_SESSION['autoriza']['editar_operacao']!=1)) $unauthorized ++;
            } else if ($sql_env=="") {
                $campovazio ++;
            } else {
                $pscampos .= "environment";
                $psvalues .= "'$ant_env'";
            }
            if ($sql_email!=$ant_email and $sql_email!="") {
                if ($pscampos!="") {$pscampos.=", "; $psvalues.=", ";}
                $pscampos .= "email";
                $psvalues .= "'$sql_email'";
                $alteracoes ++;
                if ((!isset($_SESSION['autoriza']['controle_total']) or $_SESSION['autoriza']['controle_total']!=1) and (!isset($_SESSION['autoriza']['editar_email_vendedor']) or $_SESSION['autoriza']['editar_email_vendedor']!=1)) $unauthorized ++;
            } else if ($sql_email=="") {
                $campovazio ++;
            } else {
                if ($pscampos!="") {$pscampos.=", "; $psvalues.=", ";}
                $pscampos .= "email";
                $psvalues .= "'$ant_email'";
            }
            if ($sql_token!=$ant_token and $sql_token!="") {
                if ($pscampos!="") {$pscampos.=", "; $psvalues.=", ";}
                $pscampos .= "token";
                $psvalues .= "'$sql_token'";
                $alteracoes ++;
                if ((!isset($_SESSION['autoriza']['controle_total']) or $_SESSION['autoriza']['controle_total']!=1) and (!isset($_SESSION['autoriza']['editar_token']) or $_SESSION['autoriza']['editar_token']!=1)) $unauthorized ++;
            } else if (isset($_POST['token']) and $sql_token=="") {
                $campovazio ++;
            } else {
                if ($pscampos!="") {$pscampos.=", "; $psvalues.=", ";}
                $pscampos .= "token";
                $psvalues .= "'$ant_token'";
            }
            if ($sql_tokensandbox!=$ant_tokensandbox and $sql_tokensandbox!="") {
                if ($pscampos!="") {$pscampos.=", "; $psvalues.=", ";}
                $pscampos .= "tokensandbox";
                $psvalues .= "'$sql_tokensandbox'";
                $alteracoes ++;
                if ((!isset($_SESSION['autoriza']['controle_total']) or $_SESSION['autoriza']['controle_total']!=1) and (!isset($_SESSION['autoriza']['editar_token_sandbox']) or $_SESSION['autoriza']['editar_token_sandbox']!=1)) $unauthorized ++;
            } else if (isset($_POST['tokensandbox']) and $sql_tokensandbox=="") {
                $campovazio ++;
            } else {
                if ($pscampos!="") {$pscampos.=", "; $psvalues.=", ";}
                $pscampos .= "tokensandbox";
                $psvalues .= "'$ant_tokensandbox'";
            }
            if ($alteracoes>0 and $campovazio==0 and $unauthorized==0) {
                $sql->query("update dados_apipag set ativo = '0' where ativo = '1'") or die(mysqli_error($sql));
                $sql->query("insert into dados_apipag ($pscampos,ativo,data,usuario) values($psvalues,'1',now(),$sql_usuario)") or die (mysqli_error($sql));
                
                $emailFromPesq = $sql->query("select email from notificacoes where tipo = 'remetenteNoti' and ativo = '1'") or die (mysqli_error($sql));
                $emailNotiPesq = $sql->query("select email from notificacoes where tipo = 'config' and ativo = '1'") or die (mysqli_error($sql));
                if (mysqli_num_rows($emailFromPesq)>0 and mysqli_num_rows($emailNotiPesq)>0) {
                    $de = mysqli_fetch_array($emailFromPesq);
                    $para = mysqli_fetch_array($emailNotiPesq);
                    $texto = 'As configurações do PagSeguro foram alteradas.';
                    enviaEmail($de['email'],$para['email'],'Alteração Configurações PagSeguro',$texto);
                }
            } else if ($campovazio!=0 or $unauthorized>0) {
                if ($unauthorized > 0) $alertpagseg .= "*Alteração não permitida!<br>";
                if ($campovazio != 0) $alertpagseg .= "*Preencher todos os campos!<br>";
            }
            
            if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['editar_remetente']) and $_SESSION['autoriza']['editar_remetente']==1)) {
                $notir[] = $_POST['remetenteNoti'];
                $tipr[] = 'remetenteNoti';
                
                for ($i=0;$i<count($notir);$i++) {
                    $postdavez = $notir[$i];
                    $tipodavez = $tipr[$i];
                    $updates=0;
                    $statusPagSegPesq = $sql->query("select * from notificacoes where tipo = '$tipodavez' and ativo = '1'") or die (mysqli_error($sql));
                    if (mysqli_num_rows($statusPagSegPesq)>0) {
                        $ant_post = mysqli_fetch_array($statusPagSegPesq);
                        if ($postdavez!="" and $postdavez!=$ant_post['email']) {
                            $sql->query("update notificacoes set ativo = '0' where tipo = '$tipodavez' and ativo = '1'") or die (mysqli_error($sql));
                            $sql->query("insert into notificacoes (tipo,email,ativo,data,usuario) values ('$tipodavez','$postdavez','1',now(),'$sql_usuario')") or die (mysqli_error($sql));
                            $updates++;
                        } else if ($postdavez=="") {
                            $sql->query("update notificacoes set ativo = '0' where tipo = '$tipodavez' and ativo = '1'") or die (mysqli_error($sql));
                            $updates++;
                        }
                    } else {
                        if ($postdavez!="") {
                            $sql->query("insert into notificacoes (tipo,email,ativo,data,usuario) values ('$tipodavez','$postdavez','1',now(),'$sql_usuario')") or die (mysqli_error($sql));
                            $updates++;
                        }
                    }
                    if ($i==(count($notir)-1) and $updates>0) {
                        $emailFromPesq = $sql->query("select email from notificacoes where tipo = 'remetenteNoti' and ativo = '1'") or die (mysqli_error($sql));
                        $emailNotiPesq = $sql->query("select email from notificacoes where tipo = 'config' and ativo = '1'") or die (mysqli_error($sql));
                        if (mysqli_num_rows($emailFromPesq)>0 and mysqli_num_rows($emailNotiPesq)>0) {
                            $de = mysqli_fetch_array($emailFromPesq);
                            $para = mysqli_fetch_array($emailNotiPesq);
                            $texto = 'O remetente padrão do sistema foi alterado.';
                            enviaEmail($de['email'],$para['email'],'Alteração de Remetente Padrão',$texto);
                        }
                    }
                }
            }
            
            if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['editar_emails_de_notificacoes']) and $_SESSION['autoriza']['editar_emails_de_notificacoes']==1)) {
                //tipos de notificação, adicionar no vetor $noti e $tip
                // $noti[] = $_POST['postDaNotificação'];
                // $tip[] = 'tipoDaNotificação';
                $noti[] = $_POST['statusPagSeg'];
                $tip[] = 'statusPagSeg';
                $noti[] = $_POST['novoCliente'];
                $tip[] = 'novoCliente';
                $noti[] = $_POST['config'];
                $tip[] = 'config';
                
                for ($i=0;$i<count($noti);$i++) {
                    $postdavez = $noti[$i];
                    $tipodavez = $tip[$i];
                    $updates=0;
                    $statusPagSegPesq = $sql->query("select * from notificacoes where tipo = '$tipodavez' and ativo = '1'") or die (mysqli_error($sql));
                    if (mysqli_num_rows($statusPagSegPesq)>0) {
                        $ant_post = mysqli_fetch_array($statusPagSegPesq);
                        if ($postdavez!="" and $postdavez!=$ant_post['email']) {
                            $sql->query("update notificacoes set ativo = '0' where tipo = '$tipodavez' and ativo = '1'") or die (mysqli_error($sql));
                            $sql->query("insert into notificacoes (tipo,email,ativo,data,usuario) values ('$tipodavez','$postdavez','1',now(),'$sql_usuario')") or die (mysqli_error($sql));
                            $updates++;
                        } else if ($postdavez=="") {
                            $sql->query("update notificacoes set ativo = '0' where tipo = '$tipodavez' and ativo = '1'") or die (mysqli_error($sql));
                            $updates++;
                        }
                    } else {
                        if ($postdavez!="") {
                            $sql->query("insert into notificacoes (tipo,email,ativo,data,usuario) values ('$tipodavez','$postdavez','1',now(),'$sql_usuario')") or die (mysqli_error($sql));
                            $updates++;
                        }
                    }
                    if ($i==(count($noti)-1) and $updates>0) {
                        $emailFromPesq = $sql->query("select email from notificacoes where tipo = 'remetenteNoti' and ativo = '1'") or die (mysqli_error($sql));
                        $emailNotiPesq = $sql->query("select email from notificacoes where tipo = 'config' and ativo = '1'") or die (mysqli_error($sql));
                        if (mysqli_num_rows($emailFromPesq)>0 and mysqli_num_rows($emailNotiPesq)>0) {
                            $de = mysqli_fetch_array($emailFromPesq);
                            $para = mysqli_fetch_array($emailNotiPesq);
                            $texto = 'Alguns e-mails de notificação foram alterados.';
                            enviaEmail($de['email'],$para['email'],'Alteração de Notificações',$texto);
                        }
                    }
                }
            }
        }
        
        $pesq_query = $sql->query("select * from dados_apipag where ativo = '1'") or die (mysqli_error($sql));
        if (mysqli_num_rows($pesq_query)>0) {
            $pesq = mysqli_fetch_array($pesq_query);
            $environment = $pesq['environment'];
            $email = $pesq['email'];
            $token = $pesq['token'];
            $tokensandbox = $pesq['tokensandbox'];
        } else {
            $environment = "";
            $email = "";
            $token = "";
            $tokensandbox = "";
            $exibeDados = 1;
            $alertpagseg .= "**O PagSeguro não está configurado!<br>Favor preencher os dados abaixo para evitar erros nos pagamentos.<br>";
        }
        ?>
        <div id="div_corpo">
            <form method="POST" action="">
            <?if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['pagseguro']) and $_SESSION['autoriza']['pagseguro']==1)) {?>
            <fieldset>
                <legend>Configurações PagSeguro</legend>
                    <table>
                        <?if ($alertpagseg!="") {
                            echo "<tr><td colspan='2'>".$alertpagseg."</td></tr>";
                        }?>
                        <tr>
                            <!--environment: aceita os valores production e sandbox.-->
                            <td><label>Operação:</label></td>
                            <?if ($environment=="production") {$selec1 = "checked"; $selec2="";}
                            else if ($environment=="sandbox") {$selec1 = ""; $selec2 = "checked";}
                            else {$selec1 = ""; $selec2 = "";}
                            if ((!isset($_SESSION['autoriza']['controle_total']) or $_SESSION['autoriza']['controle_total']!=1) and (!isset($_SESSION['autoriza']['editar_operacao']) or $_SESSION['autoriza']['editar_operacao']!=1)) $disab = "disabled='true'";
                            else $disab = "";?>
                            <td><input type="radio" name="environment" value="production" <?echo $selec1;?> <?echo $disab;?>>Produção</input>
                            <input type="radio" name="environment" value="sandbox" <?echo $selec2;?> <?echo $disab;?>>Teste</input></td>
                        </tr>
                        <tr>
                            <!--email: e-mail cadastrado.-->
                            <td><label>E-mail Vendedor:</label></td>
                            <?if ((!isset($_SESSION['autoriza']['controle_total']) or $_SESSION['autoriza']['controle_total']!=1) and (!isset($_SESSION['autoriza']['editar_email_vendedor']) or $_SESSION['autoriza']['editar_email_vendedor']!=1)) $disab2 = "disabled='true'";
                            else $disab2 = "";?>
                            <td><input type="email" name="e-mail" maxlength="40" size="50" value="<?echo $email?>" <?echo $disab2;?> /></td>
                        </tr>
                        <?if ((!isset($exibeDados) or $exibeDados!=1) and ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['exibir_dados_confidenciais']) and $_SESSION['autoriza']['exibir_dados_confidenciais']==1))) {?>
                        <tr><td align="right" colspan="2" id="linps1"><a href="#" id="mostrar_dados_conf">Exibir dados confidenciais ▼</a></td></tr>
                        <?}?>
                        <?if (((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['exibir_dados_confidenciais']) and $_SESSION['autoriza']['exibir_dados_confidenciais']==1))) {?>
                        <tr<?if ((!isset($exibeDados) or $exibeDados!=1)) echo " style='display: none;' id=\"linps2\"";?>>
                            <!--token production: token gerado no PagSeguro.-->
                            <td><label>Token:</label></td>
                            <?if ((!isset($_SESSION['autoriza']['controle_total']) or $_SESSION['autoriza']['controle_total']!=1) and (!isset($_SESSION['autoriza']['editar_token']) or $_SESSION['autoriza']['editar_token']!=1)) $disab3 = "disabled='true'";
                            else $disab3 = "";?>
                            <td><input type="text" name="token" maxlength="32" size="50" value="<?echo $token?>" <?echo $disab3;?> /></td>
                        </tr>
                        <tr<?if ((!isset($exibeDados) or $exibeDados!=1)) echo " style='display: none;' id=\"linps3\"";?>>
                            <!--token sandbox: token gerado no Sandbox.-->
                            <td><label>Token Sandbox:</label></td>
                            <?if ((!isset($_SESSION['autoriza']['controle_total']) or $_SESSION['autoriza']['controle_total']!=1) and (!isset($_SESSION['autoriza']['editar_token_sandbox']) or $_SESSION['autoriza']['editar_token_sandbox']!=1)) $disab4 = "disabled='true'";
                            else $disab4 = "";?>
                            <td><input type="text" name="tokensandbox" maxlength="32" size="50" value="<?echo $tokensandbox?>" <?echo $disab4;?> /></td>
                        </tr>
                        <?}?>
                            <!--appId production: ID da aplicação criada no PagSeguro.-->
                            <!--appKey production: Chave da aplicação criada no PagSeguro.-->
                            <!--appId sandbox: ID da aplicação criada no Sandbox.-->
                            <!--appKey sandbox: Chave da aplicação criada no Sandbox.-->
                            <!--charset: codificação do seu sistema (ISO-8859-1 ou UTF-8).-->
                            <!--log: ativa/desativa a geração de logs.-->
                            <!--fileLocation: local onde o arquivo de log será gravado. Ex.: ../PagSeguroLibrary/logs.txt-->
                    </table>
            </fieldset>
            <?}?>
            <?if ((isset($_SESSION['autoriza']['controle_total']) and $_SESSION['autoriza']['controle_total']==1) or (isset($_SESSION['autoriza']['notificacoes_pos_email']) and $_SESSION['autoriza']['notificacoes_pos_email']==1)) {?>
            <fieldset>
                <legend>Notificações por e-mail</legend>
                    <table>
                        <tr>
                            <?$remetenteNoti_query = $sql->query("select * from notificacoes where tipo = 'remetenteNoti' and ativo = '1'") or die (mysqli_error($sql));
                            if (mysqli_num_rows($remetenteNoti_query)>0) {
                                $vlr = mysqli_fetch_array($remetenteNoti_query);
                                $vlr_remetenteNoti = $vlr['email'];
                            } else {
                                $vlr_remetenteNoti = "";
                            }?>
                            <td><label>Remetente:</label></td>
                            <?if ((!isset($_SESSION['autoriza']['controle_total']) or $_SESSION['autoriza']['controle_total']!=1) and (!isset($_SESSION['autoriza']['editar_remetente']) or $_SESSION['autoriza']['editar_remetente']!=1)) $disab5 = " disabled='true'";
                            else $disab5 = "";?>
                            <td><input type="email" name="remetenteNoti" size="44" maxlength="40" value="<?echo $vlr_remetenteNoti;?>" <?echo $disab5;?> /></td>
                        </tr>
                        <tr><td colspan="2"><img src="../images/SubDiv.jpg" style="width: 100%; height: 3px;"></td></tr>
                        <tr><td colspan="2" style="font-size: 9px;">Padrão: Nome Sobrenome &lt;email@dominio.com.br&gt;, email2@dominio.com (sem quebra de linha)</td></tr>
                        <tr>
                            <?$statusPagSeg_query = $sql->query("select * from notificacoes where tipo = 'statusPagSeg' and ativo = '1'") or die (mysqli_error($sql));
                            if (mysqli_num_rows($statusPagSeg_query)>0) {
                                $vlr = mysqli_fetch_array($statusPagSeg_query);
                                $vlr_statusPagSeg = $vlr['email'];
                            } else {
                                $vlr_statusPagSeg = "";
                            }?>
                            <?if ((!isset($_SESSION['autoriza']['controle_total']) or $_SESSION['autoriza']['controle_total']!=1) and (!isset($_SESSION['autoriza']['editar_emails_de_notificacoes']) or $_SESSION['autoriza']['editar_emails_de_notificacoes']!=1)) $disab6 = " disabled='true'";
                            else $disab6 = "";?>
                            <td width="128px" style="vertical-align: top;"><label>Mudanças no status de pagamento:</label></td>
                            <td><textarea cols="50" rows="3" name="statusPagSeg" <?echo $disab6;?>><?echo $vlr_statusPagSeg;?></textarea></td>
                        </tr>
                        <tr>
                            <?$novoCliente_query = $sql->query("select * from notificacoes where tipo = 'novoCliente' and ativo = '1'") or die (mysqli_error($sql));
                            if (mysqli_num_rows($novoCliente_query)>0) {
                                $vlr = mysqli_fetch_array($novoCliente_query);
                                $vlr_novoCliente = $vlr['email'];
                            } else {
                                $vlr_novoCliente = "";
                            }?>
                            <td width="128px" style="vertical-align: top;"><label>Novo cadastro de cliente:</label></td>
                            <td><textarea cols="50" rows="3" name="novoCliente" <?echo $disab6;?>><?echo $vlr_novoCliente;?></textarea></td>
                        </tr>
                        <tr>
                            <?$config_query = $sql->query("select * from notificacoes where tipo = 'config' and ativo = '1'") or die (mysqli_error($sql));
                            if (mysqli_num_rows($config_query)>0) {
                                $vlr = mysqli_fetch_array($config_query);
                                $vlr_config = $vlr['email'];
                            } else {
                                $vlr_config = "";
                            }?>
                            <td width="128px" style="vertical-align: top;"><label>Mudanças de configurações no sistema:</label></td>
                            <td><textarea cols="50" rows="3" name="config" <?echo $disab6;?>><?echo $vlr_config;?></textarea></td>
                        </tr>
                    </table>
            </fieldset>
            <?}?>
            <input type="submit" value="Salvar" />
            </form>
        </div>
        <?}?>
    </body>
</html>