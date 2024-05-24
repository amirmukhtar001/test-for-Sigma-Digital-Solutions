<?php
session_start();

require_once '../vendor/autoload.php';
require_once '../includes/config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$paymentMethod = $_POST['payment_method'];
$totalAmount = $_POST['total_amount'];
$userId = $_SESSION['id'];

// Stripe API configuration
$stripe = new \Stripe\StripeClient('sk_test_51PJnjqSBVLsZqgniawU7gh7VGtRo5FgGqSiRJIl8HVcZlIlLywPIBQF5YbmFYhN2OP4Ry8rz4KiQ0rJ5m9PI6Ce600Lk3BXcyM');

// PayTabs API configuration
$paytabsProfileID = '145030';
$paytabsServerKey = 'SRJ9RWM22Z-JJ6ZLZHWD2-KNHGDBRD2M';
$paytabsEndpoint = 'https://secure-global.paytabs.com/payment/request';

if ($paymentMethod == 'stripe') {
    // Handle Stripe payment
    try {
        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Total Amount',
                    ],
                    'unit_amount' => $totalAmount * 100, // Amount in cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'http://corephp.test/success.php?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://corephp.test/cancel.php',
        ]);

        header("Location: " . $session->url);
        exit;
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
} elseif ($paymentMethod == 'paytabs') {
    // Handle PayTabs payment
    $data = [
        "profile_id" => $paytabsProfileID,
        "tran_type" => "sale",
        "tran_class" => "ecom",
        "cart_description" => "Total Payment",
        "cart_id" => uniqid(),
        "cart_currency" => "PKR",
        "cart_amount" => $totalAmount,
        "callback" => "http://corephp.test/callback.php",
        "return" => "http://corephp.test/success.php"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $paytabsEndpoint);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'authorization: ' . $paytabsServerKey,
        'content-type: application/json'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result['redirect_url'])) {
        $_SESSION['payment_data'] = [
            'user_id' => $userId,
            'amount' => $totalAmount,
            'payment_method' => 'paytabs',
            'transaction_id' => $result['tran_ref']
        ];
        header("Location: " . $result['redirect_url']);
        exit;
    } else {
        echo 'Error: ' . $result['message'];
    }
}
?>
