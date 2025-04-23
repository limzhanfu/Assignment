<?php
// Database connection
$_db = new PDO('mysql:host=localhost;dbname=shopping_cart', 'root', '');

// Fetch only Laptop products
$stmt = $_db->prepare('SELECT * FROM product WHERE category = ?');
$stmt->execute(['Laptop']);
$products = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laptops</title>
    <link rel="stylesheet" href="../css/product.css">
    <link rel="stylesheet" href="../css/navibar.css">
</head>
<body>
    
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
<a href="../index.php" class="go-to-cart-button">Back to home page</a> 
    <h1 style="text-align:center;">Laptop Collection</h1>

    <div class="product-list">
        <?php foreach ($products as $product): ?>
            <div class="product-item">
                <a href="product_detail.php?id=<?= $product->id ?>">
                    <img src="/image/<?= $product->photo ?>" alt="<?= $product->name ?>">
                    <h3><?= $product->name ?></h3>
                </a>
                <p>Price: RM <?= $product->price ?></p>
                <p>Stock: <?= $product->stock ?></p>
                <form method="POST" action="/order/cart.php">
                    <input type="hidden" name="product_id" value="<?= $product->id ?>">
                    <input type="number" name="quantity" value="1" min="1" />
                    <button type="submit" name="action" value="add_to_cart">Add to Cart</button>
                </form>
            </div>
        <?php endforeach; ?>
        </div>

       <!-- Cart Link -->
<a href="/order/cart.php" class="go-to-cart-button">Go to Cart</a>
    </div>
</body>
</html>
