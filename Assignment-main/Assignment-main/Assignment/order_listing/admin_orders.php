<?php
session_start();
include 'db.php';

if ($_SESSION['role'] !== 'admin') {
    echo "Access Denied.";
    exit;
}

$sql = "SELECT o.id AS order_id, o.created_at, o.total_amount,
               u.username, p.name AS product_name, oi.quantity, oi.price
        FROM orders o
        JOIN user u ON o.user_id = u.id
        JOIN order_items oi ON o.id = oi.order_id
        JOIN product p ON oi.product_id = p.id
        ORDER BY o.created_at DESC";

$result = $conn->query($sql);

$current_order = null;
while ($row = $result->fetch_assoc()) {
    if ($current_order !== $row['order_id']) {
        echo "<h3>Order #{$row['order_id']} - User: {$row['username']} - {$row['created_at']}</h3>";
        $current_order = $row['order_id'];
    }
    echo "<p>{$row['product_name']} - Quantity: {$row['quantity']} - Price: RM{$row['price']}</p>";
}
?>
