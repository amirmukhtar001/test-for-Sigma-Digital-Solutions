<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    echo "User is not logged in.";
    exit;
}

require_once "../includes/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST['product_id'];
    $userId = $_SESSION['id'];

    // Check if the product is already in the cart
    $sql = "SELECT id FROM cart WHERE user_id = ? AND product_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $userId, $productId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            echo "Product is already in the cart.";
            mysqli_stmt_close($stmt);
            exit;
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($link);
        exit;
    }

    // Insert product into the cart
    $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $userId, $productId);
        if (mysqli_stmt_execute($stmt)) {
            echo "Product added to cart successfully.";
        } else {
            echo "Error executing statement: " . mysqli_error($link);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($link);
    }

    mysqli_close($link);
} else {
    echo "Invalid request method.";
}
?>
