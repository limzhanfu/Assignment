<?php
// Connect to database
$_db = new PDO('mysql:host=localhost;dbname=shopping_cart', 'root', '');

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id > 0) {
    // Delete the product image (optional)
    $stmt = $_db->prepare("SELECT photo FROM product WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product && $product['photo']) {
        $imagePath = '../image/' . $product['photo'];
        if (file_exists($imagePath)) {
            unlink($imagePath); // Delete image file
        }
    }

    // Delete the product from the database
    $stmt = $_db->prepare("DELETE FROM product WHERE id = ?");
    $success = $stmt->execute([$product_id]);

    if ($success) {
        echo "Product deleted successfully.";
    } else {
        echo "Error deleting product.";
    }
} else {
    echo "Invalid product ID.";
}

// Redirect to admin panel
header("Location: ../admin/admin.php");
exit;
?>
