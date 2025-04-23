<?php
// cart_functions.php

// Function to add or update the cart
function add_to_cart($product_id, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        // If product is already in the cart, update the quantity
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        // Otherwise, add new product to the cart
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Function to get cart items
function get_cart() {
    return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
}

// Function to get cart details (product name, price, quantity, and total)
function get_cart_details($db) {
    $cart_details = [];
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt = $db->prepare("SELECT * FROM product WHERE id = :id");
        $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $cart_details[] = [
                'name' => $product['name'],
                'quantity' => $quantity,
                'price' => $product['price'],
                'total' => $product['price'] * $quantity,
            ];
        }
    }
    return $cart_details;
}

// Function to get the total price of the cart
function get_cart_total($db) {
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt = $db->prepare("SELECT price FROM product WHERE id = :id");
        $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $total += $product['price'] * $quantity;
        }
    }
    return $total;
}

// Function to update the cart (change quantity)
function update_cart($product_id, $quantity) {
    if ($quantity <= 0) {
        // Remove product from cart if quantity is 0 or less
        unset($_SESSION['cart'][$product_id]);
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Function to clear the cart
function clear_cart() {
    unset($_SESSION['cart']);
}
?>
