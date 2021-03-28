<?
require_once('../session.php');
require_once('../PagSeguroLibrary/PagSeguroLibrary.php');

if ($_SERVER['REQUEST_METHOD']=="GET" and $_GET['cod_ped']!="") {
    $ped = $_GET['cod_ped'];
    $paymentRequest = new PagSeguroPaymentRequest();  
    $itens_pesq = $sql->query("select * from pedidos_itens where ativo = '1' and cod_ped = '$ped'") or die(mysqli_error($sql));
    for ($i=0;$i<mysqli_num_rows($itens_pesq);$i++) {
        $item = mysqli_fetch_array($itens_pesq);
        $desc = $item['descricao']." (".number_format($item['quant'],2,',','.')." ".$item['uni']." - ".number_format($item['valor'],2,',','.')."/".$item['uni'].")";
        $val = number_format($item['quant'],2,'.','')*number_format($item['valor'],2,'.','');
        $paymentRequest->addItem($item['cod_prod'], $desc, 1, $val);
    }
    // $sedexCode = PagSeguroShippingType::getCodeByType('SEDEX');  
    // $paymentRequest->setShippingType($sedexCode);  
    // $paymentRequest->setShippingAddress(  
    //   '01452002',  
    //   'Av. Brig. Faria Lima',  
    //   '1384',  
    //   'apto. 114',  
    //   'Jardim Paulistano',  
    //   'São Paulo',  
    //   'SP',  
    //   'BRA'  
    // );
    // $ped_pesq = $sql->query("select * from pedidos_dados where cod_ped = '$ped' and ativo = '1'") or die (mysqli_error($sql));
    // $pedido = mysqli_fetch_array($ped_pesq);
    // $clie_pesq = $sql->query("select * from usuarios where ativo = '1' and id = '".$pedido['id_cli']."'") or die (mysqli_error($sql));
    // $cliente = mysqli_fetch_array($clie_pesq);
    // $paymentRequest->setSender(  
    //   $cliente['nome'].$cliente['nome']."".$cliente['nome'],  
    //   $cliente['email'],  
    //   '',  
    //   '',  
    //   '',  
    //   ''  
    // );  
    $paymentRequest->setCurrency("BRL"); 
    // Referenciando a transação do PagSeguro em seu sistema  
    $paymentRequest->setReference($ped);  
    // $paymentRequest->addPaymentMethodConfig('CREDIT_CARD', 1.00, 'DISCOUNT_PERCENT');  
    // $paymentRequest->addPaymentMethodConfig('EFT', 2.90, 'DISCOUNT_PERCENT');  
    // $paymentRequest->addPaymentMethodConfig('BOLETO', 10.00, 'DISCOUNT_PERCENT');  
    // $paymentRequest->addPaymentMethodConfig('DEPOSIT', 3.45, 'DISCOUNT_PERCENT');  
    // $paymentRequest->addPaymentMethodConfig('BALANCE', 0.01, 'DISCOUNT_PERCENT');  
    try {  
      
      $credentials = PagSeguroConfig::getAccountCredentials(); // getApplicationCredentials()  
      $checkoutUrl = $paymentRequest->register($credentials);  
      
      header("Location: $checkoutUrl");
      
    } catch (PagSeguroServiceException $e) {  
        die($e->getMessage());  
    }  
}
?>