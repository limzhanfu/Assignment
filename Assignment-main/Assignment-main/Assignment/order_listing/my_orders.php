<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT o.id AS order_id, o.created_at, o.total_amount, 
               p.name AS product_name, oi.quantity, oi.price
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN product p ON oi.product_id = p.id
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$current_order = null;
while ($row = $result->fetch_assoc()) {
    if ($current_order !== $row['order_id']) {
        echo "<h3>Order #{$row['order_id']} - {$row['created_at']}</h3>";
        $current_order = $row['order_id'];
    }
    echo "<p>{$row['product_name']} - Quantity: {$row['quantity']} - Price: RM{$row['price']}</p>";
}
?>
