<?php
session_start();

// Ensure order_id is provided
if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    die("Invalid order ID.");
}

$order_id = (int)$_GET['order_id'];

try {
    $_db = new PDO('mysql:host=localhost;dbname=shopping_cart', 'root', '');
    $_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the order details
    $stmt = $_db->prepare("SELECT * FROM orders WHERE id = :order_id");
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();

    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        // Fetch order details
        $stmt = $_db->prepare("SELECT od.quantity, od.price, p.name, p.category, p.description, p.photo FROM order_details od JOIN product p ON od.product_id = p.id WHERE od.order_id = :order_id");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();

        $order_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "<p style='color:red;'>❌ Order not found.</p>";
        exit;
    }

} catch (PDOException $e) {
    die("Error fetching order details: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Order #<?= $order_id ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 30px;
        }
        .receipt-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            background-color: #007BFF;
            color: white;
            padding: 10px;
        }
        .product-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .product-item img {
            width: 100px;
            vertical-align: middle;
        }
        .total {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
        }

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

<div class="receipt-container">
    <h2>Receipt for Order #<?= $order_id ?></h2>

    <p><strong>Date:</strong> <?= $order['created_at'] ?></p>
    <p><strong>Total Amount:</strong> RM <?= number_format($order['total_amount'], 2) ?></p>

    <h3>Order Details:</h3>
    <table border="1" style="width:100%; border-collapse: collapse;">
        <tr>
            <th>Product</th>
            <th>Category</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>

        <?php
        $total_amount = 0;
        foreach ($order_details as $detail):
            $item_total = $detail['quantity'] * $detail['price'];
            $total_amount += $item_total;
        ?>
            <tr>
                <td><?= htmlspecialchars($detail['name']) ?></td>
                <td><?= htmlspecialchars($detail['category']) ?></td>
                <td><?= htmlspecialchars($detail['description']) ?></td>
                <td><?= $detail['quantity'] ?></td>
                <td>RM <?= number_format($detail['price'], 2) ?></td>
                <td>RM <?= number_format($item_total, 2) ?></td>
            </tr>
        <?php endforeach; ?>

    </table>

    <div class="total">
        <p><strong>Total: RM <?= number_format($total_amount, 2) ?></strong></p>
    </div>
</div>
<a href="../index.php" class="go-to-cart-button">Back to home page</a> 
</body>
</html>
