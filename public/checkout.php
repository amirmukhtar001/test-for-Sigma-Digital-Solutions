<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$totalPrice = isset($_GET['totalPrice']) ? $_GET['totalPrice'] : 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Checkout</h2>
    <form action="process_payment.php" method="post">
        <div class="form-group">
            <label for="payment-method">Choose Payment Method:</label>
            <select class="form-control" id="payment-method" name="payment_method" required>
                <option value="stripe">Stripe</option>
            </select>
        </div>
        <div class="form-group">
            <label for="total-amount">Total Amount: $<?php echo $totalPrice; ?></label>
            <input type="hidden" name="total_amount" value="<?php echo $totalPrice; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Proceed to Payment</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
