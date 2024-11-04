<?php
require_once "../includes/config.php";

$page = $_POST['page'];
$limit = $_POST['limit'];

$offset = ($page - 1) * $limit;

$sql = "SELECT id, name, description, price FROM products_table LIMIT $limit OFFSET $offset";
$product_list = [];
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_assoc($result)) {
        $product_list[] = $row;
    }
    mysqli_free_result($result);
}

mysqli_close($link);

foreach ($product_list as $product) {
    echo '<div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">' . htmlspecialchars($product['name']) . '</h5>
                    <p class="card-text">' . htmlspecialchars($product['description']) . '</p>
                    <p class="card-text">Price: $' . htmlspecialchars($product['price']) . '</p>
                </div>
                <button class="btn btn-primary add-to-cart-btn" data-product-id="' . htmlspecialchars($product['id']) . '">Add to Cart</button>
            </div>
        </div>';
}
?>
