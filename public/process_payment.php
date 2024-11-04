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
}

?>
