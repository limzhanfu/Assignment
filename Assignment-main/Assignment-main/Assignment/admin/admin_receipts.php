<?php
session_start();
$_db = new PDO('mysql:host=localhost;dbname=shopping_cart', 'root', '');
$_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch all orders with details
$stmt = $_db->prepare("
    SELECT o.id as order_id, o.user_id, o.created_at, od.product_id, od.quantity, od.price,
           p.name, p.category, p.description, p.photo
    FROM orders o
    JOIN order_details od ON o.id = od.order_id
    JOIN product p ON od.product_id = p.id
    ORDER BY o.created_at DESC
");
$stmt->execute();
$receipts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$current_order = null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Receipts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background: #f4f4f4;
        }
        h2 {
            background-color: #007BFF;
            color: white;
            padding: 10px;
        }
        .receipt {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .product-item {
            margin-left: 20px;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        img {
            width: 100px;
            vertical-align: middle;
        }
    </style>
</head>
<body>

<h1>📜 All Receipts</h1>

<?php foreach ($receipts as $receipt): ?>
    <?php if ($current_order !== $receipt['order_id']): ?>
        <?php if ($current_order !== null): ?>
            </div> <!-- close last receipt -->
        <?php endif; ?>
        <?php $current_order = $receipt['order_id']; ?>
        <div class="receipt">
            <h2>🧾 Order #<?= $receipt['order_id'] ?> | User: <?= $receipt['user_id'] ?> | Date: <?= $receipt['created_at'] ?></h2>
    <?php endif; ?>

    <div class="product-item">
        <img src="/image/<?= htmlspecialchars($receipt['photo']) ?>" alt="<?= htmlspecialchars($receipt['name']) ?>">
        <p><strong>Product:</strong> <?= htmlspecialchars($receipt['name']) ?></p>
        <p><strong>Category:</strong> <?= htmlspecialchars($receipt['category']) ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($receipt['description']) ?></p>
        <p><strong>Quantity:</strong> <?= $receipt['quantity'] ?> × RM <?= number_format($receipt['price'], 2) ?></p>
    </div>

<?php endforeach; ?>
</div> <!-- close final receipt -->

</body>
</html>
