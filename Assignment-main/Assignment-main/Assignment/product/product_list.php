<?php
session_start();
include_once '../order/cart_functions.php'; ; // Include your cart functions file

$_db = new PDO('mysql:host=localhost;dbname=assignment(1)', 'root', ''); // Replace with actual DB connection

// Fetch products from database
$query = $_db->query('SELECT * FROM product');
$products = $query->fetchAll(PDO::FETCH_OBJ);

// Handle Add to Cart Action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    // Add to cart function
    add_to_cart($product_id, $quantity);
    echo '<pre>'; print_r($_SESSION['cart']); echo '</pre>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="../css/navibar.css">
    <link rel="stylesheet" href="../css/product.css">
</head>
<body>
<a href="../index.php" class="go-to-cart-button">Back to home page</a> 

<!-- Top Navigation HTML -->
<div class="nav">
    <!-- Left section (Logo) -->
    <div class="nav-left">
        <a href="../index.php"><img src="../image/TT_LOGO.png" alt="Logo"></a>
    </div>

    <!-- Middle section (Links) -->
    <div class="nav-mid">
        <ul class="nav-main">
            <li><a href="product_list.php">All Products</a></li>
            <li><a href="iphone.php">Iphone</a></li>
            <li><a href="laptop.php">Laptop</a></li>
            <li><a href="accessories.php">Accessories</a></li>
            <li><a href="tablet.php">Tablet</a></li>
            <li><a href="../admin/admin.php">Admin</a></li>
            <li><a href="../order/cart.php">Shopping Cart</a></li>
        </ul>
    </div>

    <!-- Right section (Search Bar) -->
    <div class="nav-right">
        <form class="nav-search" action="/product/search.php" method="GET">
            <input type="text" name="search" placeholder="Search products...">
            <select name="category">
                <option value="">All</option>
                <option value="Laptop">Laptop</option>
                <option value="Phone">Phone</option>
                <option value="Tablet">Tablet</option>
                <option value="Accessory">Accessory</option>
            </select>
            <button type="submit">Search</button>
        </form>
    </div>
</div>

    <div class="product-list">
        <?php foreach ($products as $product): ?>
            <div class="product-item">
                <a href="product_detail.php?id=<?= $product->id ?>">
                    <img src="/image/<?= $product->photo ?>" alt="<?= $product->name ?>">
                    <h3><?= $product->name ?></h3>
                </a>
                    <p>Price: RM <?= $product->price ?></p>
                    <p>Stock: <?=$product->stock ?></p>
                

                <!-- Add to Cart Form -->
                <form method="POST" action="/order/cart.php">
                    <input type="hidden" name="product_id" value="<?= $product->id ?>">
                    <input type="number" name="quantity" value="1" min="1" /> <!-- Input for Quantity -->
                    <button type="submit" name="action" value="add_to_cart">Add to Cart</button> <!-- Add to Cart Button -->
                </form>
            </div>
        <?php endforeach; ?>
    </div>
<!-- Cart Link -->
<a href="/order/cart.php" class="go-to-cart-button">Go to Cart</a>
</div>

</body>
</html>