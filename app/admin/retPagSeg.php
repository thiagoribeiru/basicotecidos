<?
require_once('../configapp.php');
function escreveLog($string) {
    global $sql;
    $name = 'logPagSeg.txt';
    $text = "\n".var_export($string, true);
    $file = fopen($name, 'a');
    fwrite($file, $text);
    fclose($file);
}
function pegaDescPos($codepos) {
    global $sql;
    $descricaoPesq = $sql->query("select nome from pos_pedidos where id = '$codepos' and ativo = '1'") or die (mysqli_error($sql));
    if (mysqli_num_rows($descricaoPesq)>0) {
        $descricao = mysqli_fetch_array($descricaoPesq);
        $descricao = $descricao['nome'];
    } else $descricao = "(Posição não cadastrada)";
    return $descricao;
}
escreveLog($_POST);

if(isset($_POST['notificationType']) && $_POST['notificationType'] == 'transaction'){
    //Todo resto do código iremos inserir aqui.
    $pesq_api = $sql->query("select * from dados_apipag where ativo = '1'") or die ($sql->query());
        $dados = mysqli_fetch_array($pesq_api);
        if ($dados['environment']=='sandbox') {
            $urlp = 'https://ws.sandbox.pagseguro.uol.com.br/v2';
            $tokenp = $dados['tokensandbox'];
        } else if ($dados['environment']=='production') {
            $urlp = 'https://ws.pagseguro.uol.com.br/v2';
            $tokenp = $dados['token'];
        }
    $email = $dados['email'];
    $token = $tokenp;

    $url = $urlp.'/transactions/notifications/' . $_POST['notificationCode'] . '?email=' . $email . '&token=' . $token;

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $transaction= curl_exec($curl);
    curl_close($curl);

    if($transaction == 'Unauthorized'){
        //Insira seu código avisando que o sistema está com problemas, sugiro enviar um e-mail avisando para alguém fazer a manutenção
        escreveLog("Unauthorized");
        exit;//Mantenha essa linha
    }
    $transaction = simplexml_load_string($transaction);
    
    $type = $transaction->type;
    if ($type=='1') {
        $status = $transaction->status;
        $reference = $transaction->reference;
        $reference = str_replace('PED','',$reference);
        $code = $transaction->code;
        $code = str_replace('-','-',$code);
        if ($status=='1' or $status=='2' or $status=='3' or $status=='4' or $status=='5' or $status=='6' or $status=='7') {
            $ped_pesq = $sql->query("select * from pedidos_dados where cod_ped = '$reference' and ativo = '1'") or die (mysqli_error($sql));
            if (mysqli_num_rows($ped_pesq)>0) {
                $pedido = mysqli_fetch_array($ped_pesq);
                $sql_id_cli = $pedido['id_cli'];
                $sql_code = $pedido['codepagseg'];
                if ($sql_code=="") $sql_code = $code;
                $sql->query("update pedidos_dados set ativo = '0' where cod_ped = '$reference' and ativo = '1'") or die(mysqli_error($sql));
                $sql->query("insert into pedidos_dados (cod_ped,id_cli,id_pos,finalizado,codepagseg,ativo,data,usuario) values ('$reference','$sql_id_cli','$status','1','$sql_code','1',now(),'1')") or die(mysqli_error($sql));
            }
        }
    }
    $meioPagPesq = $sql->query("select descricao from pagseg_meio_pag where cod = ".$transaction->paymentMethod->code) or die(mysqli_error($sql));
    if (mysqli_num_rows($meioPagPesq)>0) {
        $meioPag = mysqli_fetch_array($meioPagPesq);
        $meioPag = $meioPag['descricao'];
    } else $meioPag = "(Meio de pagamento não cadastrado)";
    $tabela = "";
    for ($it=0;$it<$transaction->itemCount;$it++) {
        $quantity = (float) $transaction->items[0]->item[$it]->quantity[0];
        $amount = (float) $transaction->items[0]->item[$it]->amount[0];
        $tabela .= "<tr>\n";
            $tabela .= "<td align=\"center\" style=\"border: 1px solid #CDC9C9;\">".$transaction->items[0]->item[$it]->id[0]."</td>\n";
            $tabela .= "<td align=\"left\" style=\"border: 1px solid #CDC9C9;\">".$transaction->items[0]->item[$it]->description[0]."</td>\n";
            $tabela .= "<td align=\"center\" style=\"border: 1px solid #CDC9C9;\">".number_format($quantity,0,"","")."</td>\n";
            $tabela .= "<td align=\"right\" style=\"border: 1px solid #CDC9C9;\">".number_format($amount,2,",","")."</td>\n";
            $tabela .= "<td align=\"right\" style=\"border: 1px solid #CDC9C9;\">".number_format($quantity*$amount,2,",","")."</td>\n";
        $tabela .= "</tr>\n";
    }
    $statusHist = "";
    $estadoPesq = $sql->query("SELECT * FROM pedidos_dados where cod_ped = '$reference'") or die (mysqli_error($sql));
    for ($st=0;$st<mysqli_num_rows($estadoPesq);$st++) {
        $estadoItem = mysqli_fetch_array($estadoPesq);
        $statusHist .= "<p style=\"margin: 0;\">".date("d/m/Y H:i",strtotime($estadoItem['data']))." ".pegaDescPos($estadoItem['id_pos'])."</p>\n";
    }
    
    $emailFromPesq = $sql->query("select email from notificacoes where tipo = 'remetenteNoti' and ativo = '1'") or die (mysqli_error($sql));
    $emailNotiPesq = $sql->query("select email from notificacoes where tipo = 'statusPagSeg' and ativo = '1'") or die (mysqli_error($sql));
    if (mysqli_num_rows($emailNotiPesq)>0 and mysqli_num_rows($emailFromPesq)>0) {
        $emailNoti = mysqli_fetch_array($emailNotiPesq);
        // multiple recipients
        $to  = $emailNoti['email'];
        
        // subject
        $subject = 'Posição PED'.$reference;
        
        //variaveis
        $grossAmount = (float) $transaction->grossAmount;
        $cost = (float) $transaction->shipping->cost;
        
        // message
        $message = "
        <html>
            <head>
                <title>Relatório de Transação de Pedido</title>
            </head>
            <body style=\"font-family: 'Arial', Georgia, Serif; font-size: 12px;\">
                <p style=\"margin: 0;\">Status: <b>".pegaDescPos($transaction->status)."</b></p>
                <p style=\"margin: 0;\">Código da transação: <b>".$code."</b></p>
                <p style=\"margin: 0;\">Código de referência: <b>PED".$reference."</b></p>
                <p style=\"margin: 0;\">Total: <b>R$ ".number_format($grossAmount,2,",","")."</b></p>
                <p style=\"margin: 0;\">Meio de Pagamento: <b>".$meioPag." (pagamento em ".$transaction->installmentCount."x)</b></p>
                <br>
                <p style=\"margin: 0;\"><b>Comprador</b></p>
                <p style=\"margin: 0;\">Nome: <b>".$transaction->sender->name."</b></p>
                <p style=\"margin: 0;\">E-mail: <b>".$transaction->sender->email."</b></p>
                <p style=\"margin: 0;\">Telefone: <b>(".$transaction->sender->phone->areaCode.") ".$transaction->sender->phone->number."</b></p>
                <br>
                <p style=\"margin: 0;\"><b>Itens do carrinho</b></p>
                <table style=\"font-size: 10px; border-collapse: collapse;\">
                    <thead style=\"background: #EEE9E9;\">
                        <tr>
                            <th style=\"border: 1px solid #CDC9C9;\">ID</th>
                            <th style=\"border: 1px solid #CDC9C9;\">PRODUTO</th>
                            <th style=\"border: 1px solid #CDC9C9;\">QUANTIDADE</th>
                            <th style=\"border: 1px solid #CDC9C9;\">VALOR(R$)</th>
                            <th style=\"border: 1px solid #CDC9C9;\">TOTAL(R$)</th>
                        </tr>
                    </thead>
                    <tbody>
                        ".$tabela."
                    </tbody>
                    <tfoot style=\"border: 1px solid #CDC9C9;\">
                        <tr><td colspan=\"5\" align=\"right\">Frete(R$): ".number_format($cost,2,",","")."</td></tr>
                        <tr><td colspan=\"5\" align=\"right\" style=\"font-size: 12px;\"><b>Total do carrinho(R$): ".number_format($grossAmount,2,",","")."</b></td></tr>
                    </tfoot>
                </table>
                <br>
                <p style=\"margin: 0;\"><b>Endereço de entrega</b></p>
                <p style=\"margin: 0;\">Endereço: <b>".$transaction->shipping->address->street."</b></p>
                <p style=\"margin: 0;\">Número: <b>".$transaction->shipping->address->number." ".$transaction->shipping->address->complement."</b></p>
                <p style=\"margin: 0;\">Cidade: <b>".$transaction->shipping->address->city."</b></p>
                <p style=\"margin: 0;\">Bairro: <b>".$transaction->shipping->address->district."</b></p>
                <p style=\"margin: 0;\">CEP: <b>".$transaction->shipping->address->postalCode."</b></p>
                <p style=\"margin: 0;\">Estado: <b>".$transaction->shipping->address->state."</b></p>
                <br>
                <p style=\"margin: 0;\"><b>Histórico de mudanças de status</b></p>
                ".$statusHist."
            </body>
        </html>
        ";
        
        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        
        // Additional headers
        $emailFrom = mysqli_fetch_array($emailFromPesq);
        // $headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
        $headers .= 'From: Basico Tecidos <'.$emailFrom['email'].'>' . "\r\n";
        // $headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
        // $headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
        
        // Mail it
        mail($to, $subject, $message, $headers);
    }
    
}
?>