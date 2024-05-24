<?php
require_once '../includes/header.php';
session_start();

echo "Payment Cancelled.";

if (isset($_SESSION['payment_data'])) {
    unset($_SESSION['payment_data']);
}
?>

<div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Payment Cancelled</h4>
                    <p>We're sorry, your payment has been cancelled.</p>
                </div>
            </div>
        </div>
    </div>