<?php
session_start();
include_once '../order/cart_functions.php'; // Include your cart functions file

$_db = new PDO('mysql:host=localhost;dbname=shopping_cart', 'root', ''); // DB connection

// Fetch products from the database
$query = $_db->query('SELECT * FROM product');
$products = $query->fetchAll(PDO::FETCH_OBJ);

// Handle Add to Cart Action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    // Add to cart function
    add_to_cart($product_id, $quantity);
    echo '<pre>'; print_r($_SESSION['cart']); echo '</pre>'; // Debugging cart content
}

// Handle Update and Remove Actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $product_id = (int)$_POST['product_id'];

    if ($_POST['action'] == 'update_cart') {
        $quantity = (int)$_POST['quantity'];
        
        // Update the cart session
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = $quantity; // Update quantity
        }
    }

    if ($_POST['action'] == 'remove_from_cart') {
        // Remove product from cart session
        unset($_SESSION['cart'][$product_id]);
    }

    // Redirect back to the cart page after processing
    header("Location: cart.php");
    exit();
}

// Display Cart Content
?>

<h2>Your Shopping Cart</h2>
<a href="../index.php" class="go-to-cart-button">Back to home page</a> 

<?php
$cart = get_cart(); // Fetch the cart from the session
if (empty($cart)): 
?>
    <p>Your cart is empty.</p>
    <a href = "../product/product_list.php" >
        <button style = "background-color: blue; color: white; " > Keep Shop for IT Device </button>
    </a>
<?php else: ?>
    <style>
    .cart-container {
        max-width: 1000px;
        margin: 50px auto;
        padding: 20px;
        background-color: #f5faff;
        border-radius: 15px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        font-family: Arial, sans-serif;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
    }

    th, td {
        padding: 15px;
        text-align: center;
        border-bottom: 1px solid #ccc;
    }

    th {
        background-color: #007bff;
        color: white;
    }

    td {
        font-size: 1em;
    }

    button {
        padding: 8px 12px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
    }

    .update-btn {
        background-color: #28a745;
        color: white;
    }

    .remove-btn {
        background-color: #dc3545;
        color: white;
    }

    .checkout-btn, .shop-btn {
        margin-top: 20px;
        background-color: #007bff;
        color: white;
        font-size: 1em;
        padding: 10px 20px;
        display: inline-block;
        text-decoration: none;
        border-radius: 8px;
    }

    .total-price {
        font-size: 1.2em;
        font-weight: bold;
        margin-top: 15px;
        text-align: right;
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

<div class="cart-container">
    <table>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
        <?php
        $total_price = 0;
        foreach ($cart as $product_id => $quantity):
            $stmt = $_db->prepare('SELECT * FROM product WHERE id = ?');
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_OBJ);
            $product_name = $product->name;
            $product_price = $product->price;
            $total = $product_price * $quantity;
            $total_price += $total;
        ?>
        <tr>
            <td><?= htmlspecialchars($product_name) ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="product_id" value="<?= $product_id ?>">
                    <input type="number" name="quantity" value="<?= $quantity ?>" min="1" />
                    <button type="submit" name="action" value="update_cart" class="update-btn">Update</button>
                </form>
            </td>
            <td>RM <?= number_format($product_price, 2) ?></td>
            <td>RM <?= number_format($total, 2) ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="product_id" value="<?= $product_id ?>">
                    <button type="submit" name="action" value="remove_from_cart" class="remove-btn">Remove</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <p class="total-price">Total Price: RM <?= number_format($total_price, 2) ?></p>

    <?php
// Ensure $product is populated correctly (this may depend on your previous code logic)
if (isset($product->id) && !empty($product->id)) {
if (isset($product->id) && !empty($product->id)) {
}

} else {
    // Handle the case where $product['id'] is not set (either redirect or show an error message)
    $product_id = null;
    // Optionally, you can redirect to another page, or show an error message
    // header('Location: error_page.php');
    // exit;
}
?>

<!-- Use the product ID if valid, otherwise show a fallback or handle the error -->
<a href="checkout.php?id=<?= $product_id ? $product_id : '' ?>" class="checkout-btn">Proceed to Checkout</a>
    <br><br>
    <a href="../product/product_list.php" class="shop-btn">Keep Shopping for IT Devices</a>
</div>




<?php endif; ?>
