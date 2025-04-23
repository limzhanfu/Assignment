<?php
// Get the product ID from the URL
$product_id = $_GET['id'];

// Database connection
$_db = new PDO('mysql:host=localhost;dbname=shopping_cart', 'root', '');

// Fetch product details from the database
$stmt = $_db->prepare('SELECT * FROM product WHERE id = ?');
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_OBJ);

if ($product) {
    // Display product details
    ?>
   <html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - <?= $product->name ?></title>
    <style>
        /* CSS for .product-detail class */
        .product-detail {
            display: flex;
            justify-content: center;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 20px;
        }

        .product-image {
            flex: 1;
            margin-right: 20px;
        }

        .product-img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-info {
            flex: 2;
        }

        .product-name {
            font-size: 2em;
            margin-bottom: 15px;
            color: #333;
        }

        .product-price {
            font-size: 1.5em;
            color: #ff5722;
            margin-bottom: 20px;
        }

        .product-description {
            font-size: 1.1em;
            color: #555;
            margin-bottom: 20px;
        }

        .product-stock {
            font-size: 1.1em;
            margin-bottom: 20px;
            color: #555;
        }

        .quantity-input {
            padding: 10px;
            width: 70px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .add-to-cart-btn {
            padding: 12px 20px;
            background-color: #4CAF50;
            color: white;
            font-size: 1.2em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background-color: #45a049;
        }

        @media (max-width: 768px) {
            .product-detail {
                flex-direction: column;
                align-items: center;
            }

            .product-image {
                margin-right: 0;
                margin-bottom: 20px;
            }

            .product-info {
                text-align: center;
            }

            .quantity-input {
                width: 50px;
            }

            .add-to-cart-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="product-detail">
        <div class="product-image">
            <img src="/image/<?= $product->photo ?>" alt="<?= $product->name ?>" class="product-img">
        </div>
        <div class="product-info">
            <h1 class="product-name"><?= $product->name ?></h1>
            <p class="product-price">Price: RM <?= $product->price ?></p>
            <p class="product-description"><?= $product->description ?></p>
            <p class="product-stock">Stock: <?= $product->stock ?></p>
            
            <form method="POST" action="/order/cart.php">
                <input type="hidden" name="product_id" value="<?= $product->id ?>">
                <input type="number" name="quantity" value="1" min="1" class="quantity-input">
                <button type="submit" name="action" value="add_to_cart" class="add-to-cart-btn">Add to Cart</button>
            </form>
        </div>
    </div>
</body>
</html>

    <?php
} else {
    echo 'Product not found.';
}
?>
