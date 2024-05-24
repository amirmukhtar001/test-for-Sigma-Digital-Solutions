<?php
session_start();

require_once "../includes/config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$userId = $_SESSION['id'];

$sql = "SELECT p.name, p.price, c.quantity FROM cart c JOIN products_table p ON c.product_id = p.id WHERE c.user_id = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Calculate total price
$totalPrice = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $totalPrice += $row['price'] * $row['quantity'];
}

mysqli_free_result($result);
mysqli_stmt_close($stmt);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Cart Items</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, "i", $userId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['price']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="row">
    <div class="col-md-6">
        <h4>Total Price: $<?php echo number_format($totalPrice, 2); ?></h4>
    </div>
    <div class="col-md-6 text-right">
        <a href="checkout.php?totalPrice=<?php echo number_format($totalPrice, 2); ?>" class="btn btn-primary">Checkout</a>
    </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($link);
?>
