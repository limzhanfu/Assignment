<?php
session_start();

// ✅ Database connection
$host = "localhost";
$user = "root";
$password = ""; // default XAMPP password
$database = "shopping_cart"; // <-- change this to your actual DB name

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ✅ Check if user is logged in
/*if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view your purchase history.");
}

$user_id = $_SESSION['user_id'];
*/
// ✅ SQL query to fetch purchase history
$sql = "SELECT o.id AS order_id, o.total_amount, o.created_at, 
               p.name AS product_name, p.price, oi.quantity
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id  -- using `o.id` for orders table
        JOIN products p ON oi.product_id = p.product_id
        WHERE o.user_id = $user_id
        ORDER BY o.created_at DESC";

// ✅ Debug: Print the final SQL query to check it
echo $sql; // This will print the SQL query in the browser for debugging purposes

// ✅ Execute the query
$result = mysqli_query($conn, $sql);

// ✅ Display history
$current_order = 0;
while ($row = mysqli_fetch_assoc($result)) {
    if ($current_order != $row['order_id']) {
        $current_order = $row['order_id'];
        echo "<hr>";
        echo "<h3>🧾 Order #{$row['order_id']} | Date: {$row['created_at']}</h3>";
        echo "<strong>Total: ₱" . number_format($row['total_amount'], 2) . "</strong><br>";
    }
    echo "• Product: {$row['product_name']} | Quantity: {$row['quantity']} | Price: ₱{$row['price']}<br>";
}
?>
