<?php
$_db = new PDO('mysql:host=localhost;dbname=shopping_cart', 'root', '');

// Get search query from input
$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM product WHERE 1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR category LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$stmt = $_db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_OBJ);
?>



<!DOCTYPE html>
<html>
<head>
    <title>Search Products</title>
    <style>
        body { font-family: Arial; background: #f1f5f9; }
        .search-bar {
            text-align: center;
            margin: 20px;
        }
        .product-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            padding: 40px;
        }
        .product-item {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .product-item img {
            width: 100%;
            height: 200px;
            object-fit: contain;
            margin-bottom: 10px;
        }
        .product-item h3 { color: #007bff; }
        .product-item form { margin-top: 10px; }
        .go-to-cart-button {
        display: inline-block;
        background-color: #4CAF50; /* Green background */
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        text-align: center;
        font-size: 16px;
        text-decoration: none; /* Remove underline from link */
        margin-top: 20px;
        transition: background-color 0.3s;
        }   

    </style>
</head>
<body>
<a href="../index.php" class="go-to-cart-button">Back to home page</a> 
<div class="search-bar">
    <form method="GET" action="search.php">
        <input type="text" name="search" placeholder="Search product name" value="<?= htmlspecialchars($search) ?>">
        <select name="category">
            <option value="">All Categories</option>
            <option value="Phone" <?= $category == 'Phone' ? 'selected' : '' ?>>Phone</option>
            <option value="Laptop" <?= $category == 'Laptop' ? 'selected' : '' ?>>Laptop</option>
            <option value="Tablet" <?= $category == 'Tablet' ? 'selected' : '' ?>>Tablet</option>
            <option value="Accessory" <?= $category == 'Accessory' ? 'selected' : '' ?>>Accessory</option>
        </select>
        <button type="submit">Search</button>
    </form>
</div>

<div class="product-list">
    <?php foreach ($products as $product): ?>
        <div class="product-item">
            <a href="product_detail.php?id=<?= $product->id ?>">
                <img src="/image/<?= $product->photo ?>" alt="<?= $product->name ?>">
                <h3><?= $product->name ?></h3>
            </a>
            <p>Price: RM <?= $product->price ?></p>
            <p>Stock: <?= $product->stock ?></p>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
