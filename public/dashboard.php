<?php
require_once '../includes/header.php';

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "../includes/config.php";

$product_list = [];

$sql = "SELECT id, name, description, price FROM products_table";
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_assoc($result)) {
        $product_list[] = $row;
    }
    mysqli_free_result($result);
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Add any custom styles here */
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="view_cart.php">
                        <i class="fas fa-shopping-cart"></i> Cart
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></h2>
    <button class="btn btn-primary mb-3" onclick="location.href='add_product.php'">Add Product</button>
    
    <h3>Product List</h3>
    <div class="row" id="product-list">
        <?php
        $initialLimit = 6;
        $displayedProducts = array_slice($product_list, 0, $initialLimit);
        foreach ($displayedProducts as $product) : ?>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                    <p class="card-text">Price: $<?php echo htmlspecialchars($product['price']); ?></p>
                </div>
                <button class="btn btn-primary add-to-cart-btn" data-product-id="<?php echo $product['id']; ?>">Add to Cart</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <button class="btn btn-secondary" id="load-more-btn">Load More</button>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
$(document).ready(function(){
    var page = 2; 
    var limit = 6;

    $('#load-more-btn').click(function(){
        $.ajax({
            url: 'load_more_products.php',
            type: 'post',
            data: {page: page, limit: limit},
            success: function(response){
                $('#product-list').append(response);
                page++; 
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); 
            }
        });
    });

    $('.add-to-cart-btn').click(function(){
        var productId = $(this).data('product-id');
        
        $.ajax({
            url: 'add_to_cart.php',
            type: 'post',
            data: {product_id: productId},
            success: function(response){
                alert('Product added to cart successfully!');
            },
            error: function(xhr, status, error){
                console.error(xhr.responseText);
                alert('Failed to add product to cart. Please try again.');
            }
        });
    });
});
</script>

</body>
</html>
