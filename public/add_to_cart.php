<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    echo "User is not logged in.";
    exit;
}

require_once "../includes/config.php";

$productId = $_POST['product_id'];

$userId = $_SESSION['id'];

$sql = "SELECT id FROM cart WHERE user_id = ? AND product_id = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "ii", $userId, $productId);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if(mysqli_stmt_num_rows($stmt) > 0){
    echo "Product is already in the cart.";
    exit;
}

$sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "ii", $userId, $productId);
mysqli_stmt_execute($stmt);

echo "Product added to cart successfully.";

mysqli_stmt_close($stmt);
mysqli_close($link);
?>
