<?php
session_start();

require_once '../includes/config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$userId = $_SESSION['id'];

// Stripe payment success
if (isset($_GET['session_id'])) {
    require_once '../vendor/autoload.php';
    $stripe = new \Stripe\StripeClient('sk_test_51PJnjqSBVLsZqgniawU7gh7VGtRo5FgGqSiRJIl8HVcZlIlLywPIBQF5YbmFYhN2OP4Ry8rz4KiQ0rJ5m9PI6Ce600Lk3BXcyM');
    $session = $stripe->checkout->sessions->retrieve($_GET['session_id']);

    $transactionId = $session->payment_intent;
    $amount = $session->amount_total / 100;
    $paymentMethod = 'stripe';
    $status = 'completed';

    $sql = "INSERT INTO payments (user_id, transaction_id, amount, payment_method, status) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "isdss", $userId, $transactionId, $amount, $paymentMethod, $status);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    echo "Payment Successful!";
}

mysqli_close($link);
?>
