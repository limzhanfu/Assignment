<?php
session_start();

// Ensure the cart exists
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Your cart is empty. Please add items to the cart before checking out.");
}

try {
    $_db = new PDO('mysql:host=localhost;dbname=shopping_cart', 'root', '');
    $_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Calculate the total amount for the order
    $total_amount = 0;
    $cart_details = [];

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Fetch product details for each item in the cart
        $stmt = $_db->prepare("SELECT * FROM product WHERE id = :product_id");
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $item_total = $product['price'] * $quantity;
            $cart_details[] = [
                'product_id' => $product_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'item_total' => $item_total,
            ];
            $total_amount += $item_total;
        }
    }

    // Start a transaction to ensure everything is processed correctly
    $_db->beginTransaction();

    // Create the order in the orders table
    $stmt = $_db->prepare("INSERT INTO orders (user_id, total_amount, created_at) VALUES (:user_id, :total_amount, NOW())");
    $stmt->bindParam(':user_id', $_SESSION['user_id']); // Assuming the user_id is stored in session
    $stmt->bindParam(':total_amount', $total_amount); 
    $stmt->execute();
    
    // Get the newly created order's ID
    $order_id = $_db->lastInsertId();

    // Insert each product into the order_details table and update stock
    foreach ($cart_details as $item) {
        // Deduct the stock in the product table
        $stmt = $_db->prepare("UPDATE product SET stock = stock - :quantity WHERE id = :product_id");
        $stmt->bindParam(':quantity', $item['quantity']);
        $stmt->bindParam(':product_id', $item['product_id']);
        $stmt->execute();

        // Insert each product into the order_details table
        $stmt = $_db->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':product_id', $item['product_id']);
        $stmt->bindParam(':quantity', $item['quantity']);
        $stmt->bindParam(':price', $item['price']);
        $stmt->execute();
    }

    // Commit the transaction
    $_db->commit();

    // Clear the cart after successful checkout
    unset($_SESSION['cart']);

    // Redirect to the receipt page
    header("Location: receipt.php?order_id=$order_id");
    exit();

} catch (PDOException $e) {
    // Rollback the transaction in case of an error
    $_db->rollBack();
    die("Error during checkout: " . $e->getMessage());
}

session_start();
include 'db.php';

$user_id = $_SESSION['user_id']; // must be logged in

// 1. Insert into orders table
mysqli_query($conn, "INSERT INTO orders (user_id) VALUES ($user_id)");
$order_id = mysqli_insert_id($conn); // get new order ID

// 2. Insert into order_items table
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity) 
                         VALUES ($order_id, $product_id, $quantity)");
}

// 3. Clear cart after purchase
unset($_SESSION['cart']);

// Redirect to receipt or history
header("Location: receipt.php?order_id=" . $order_id);
exit;

?>
