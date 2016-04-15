<?php
/**
 * Verify SimplePay transaction
 */

$private_key = 'test_pr_demo';

// Retrieve data returned in payment gateway callback
$token = $_POST['token'];
$payment_id = $_POST['payment_id'];

$data = array (
    'token' => $token
);
$data_string = json_encode($data); 

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://checkout.simplepay.ng/v1/payments/verify/');
curl_setopt($ch, CURLOPT_USERPWD, $private_key . ':');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string)
));

$curl_response = curl_exec($ch);
$curl_response = preg_split("/\r\n\r\n/",$curl_response);
$response_content = $curl_response[1];
$json_response = json_decode(chop($response_content), TRUE);

$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

$status = '';

if ($response_code == '200') {
    // even is http status code is 200 we still need to check transaction had issues or not
    if ($json_response['response_code'] == '20000'){
        $status = 'Payment Completed Successfully';
    }else{
        $status = 'Payment failed verification';
    }
} else {
    $status = 'Payment failed verification';
}
?>


<!doctype html>
<html lang="en">
<head>
    <title>SimplePay Gateway Demo</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <style type="text/css">
        html {
            font-size: 100%;
        }

        h1 {
            color: #77439E;
        }

        h2 {
            color: #77439E;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>My Test Store</h1>
        </div>
    </header>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?php echo $status ?></h2>
                <h3><?php echo "Payment ID was: ".$payment_id ?></h3>
            </div>
        </div>
    </div>
</body>
</html>
