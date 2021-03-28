<?
require_once('../session.php');

if ($_SERVER['REQUEST_METHOD']=="GET" and $_GET['cod_ped']!="") {
    $ped = $_GET['cod_ped'];
    $pesq_api = $sql->query("select * from dados_apipag where ativo = '1'") or die ($sql->query());
    if (mysqli_num_rows($pesq_api)>0) {
        $dados = mysqli_fetch_array($pesq_api);
        if ($dados['environment']=='sandbox') {
            $url = 'https://ws.sandbox.pagseguro.uol.com.br/v2/checkout';
            $token = $dados['tokensandbox'];
        } else if ($dados['environment']=='production') {
            $url = 'https://ws.pagseguro.uol.com.br/v2/checkout';
            $token = $dados['token'];
        }
        
        $data['email'] = $dados['email'];
        $data['token'] = $token;
        $data['currency'] = 'BRL';
        $itens_pesq = $sql->query("select * from pedidos_itens where ativo = '1' and cod_ped = '$ped'") or die (mysqli_error($sql));
        for ($i=1;$i<=mysqli_num_rows($itens_pesq);$i++) {
            $item = mysqli_fetch_array($itens_pesq);
            $data['itemId'.$i] = $item['cod_prod'];
            $data['itemDescription'.$i] = $item['descricao']." (".number_format($item['quant'],2,',','.')." ".$item['uni']." - ".number_format($item['valor'],2,',','.')."/".$item['uni'].")";
            if ($item['desconto']>0) $data['itemDescription'.$i] .= " Desc.: ".number_format($item['desconto'],2,',','.')."%";
            if ($item['ipi']>0) $data['itemDescription'.$i] .= " Ipi: ".number_format($item['ipi'],2,',','.')."%";
            $data['itemAmount'.$i] = number_format(subTotal($item['quant'],$item['valor'],$item['desconto'],$item['ipi']),2,'.','');
            $data['itemQuantity'.$i] = '1';
            // $data['itemWeight1'] = '1000';
        }
        $data['reference'] = 'PED#'.$ped;
        $pedido = mysqli_fetch_array($sql->query("select * from pedidos_dados where cod_ped = '$ped' and ativo = '1'")) or die (mysqli_error($sql));
        $cliente = mysqli_fetch_array($sql->query("select * from usuarios where id = '".$pedido['id_cli']."' and ativo = '1'")) or die (mysqli_error($sql));
        $data['senderName'] = $cliente['nome'];
        // $data['senderAreaCode'] = '11';
        // $data['senderPhone'] = '56273440';
        $data['senderEmail'] = $cliente['email'];
        // $data['shippingType'] = '1';
        // $data['shippingAddressStreet'] = 'Av. Brig. Faria Lima';
        // $data['shippingAddressNumber'] = '1384';
        // $data['shippingAddressComplement'] = '5o andar';
        // $data['shippingAddressDistrict'] = 'Jardim Paulistano';
        // $data['shippingAddressPostalCode'] = '01452002';
        // $data['shippingAddressCity'] = 'Sao Paulo';
        // $data['shippingAddressState'] = 'SP';
        // $data['shippingAddressCountry'] = 'BRA';
        // $data['redirectURL'] = 'http://www.sounoob.com.br/paginaDeAgracedimento';
        
        $data = http_build_query($data);
        
        $curl = curl_init($url);
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $xml= curl_exec($curl);
        
        if($xml == 'Unauthorized'){
            //Insira seu código de prevenção a erros
            // header('Location: erro.php?tipo=autenticacao');
            // exit;//Mantenha essa linha
            $retorno['autoriza'] = 0;
            $retorno['mensagem'] = "Erro de autenticação. Favor contactar o administrador do sistema.";
            echo json_encode($retorno);
            exit;
        }
        curl_close($curl);
        
        $xml= simplexml_load_string($xml);
        if(count($xml -> error) > 0){
            //Insira seu código de tratamento de erro, talvez seja útil enviar os códigos de erros.
            // header('Location: erro.php?tipo=dadosInvalidos');
            // exit;
            $retorno['autoriza'] = 0;
            $retorno['mensagem'] = "Dados inválidos. Favor contactar o administrador do sistema.";
            echo json_encode($retorno);
            exit;
        }
        // header('Location: https://pagseguro.uol.com.br/v2/checkout/payment.html?code=' . $xml -> code);
        $code = $xml->code;
        $retorno['autoriza'] = 1;
        $retorno['code'] = (string) $code;
        // $retorno['code'] = "tudo certo".$code;
        echo json_encode($retorno);
        exit;
    }
}
?>