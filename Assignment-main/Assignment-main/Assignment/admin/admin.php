<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Database connection
try {
    $_db = new PDO('mysql:host=localhost;dbname=shopping_cart', 'root', '');
    $_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database Connection Failed: ' . $e->getMessage());
}

// Fetch product for editing if 'id' is provided via GET
$product = null;
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $edit_id = intval($_GET['id']);
    $stmt = $_db->prepare('SELECT * FROM product WHERE id = ?');
    $stmt->execute([$edit_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Process form submission (add or update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve product ID from POST (if any)
    $product_id = isset($_POST['id']) ? intval($_POST['id']) : null;

    // Sanitize form inputs
    $name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES);
    $price = floatval($_POST['price'] ?? 0);
    $description = htmlspecialchars(trim($_POST['description'] ?? ''), ENT_QUOTES);
    $category = htmlspecialchars(trim($_POST['category'] ?? ''), ENT_QUOTES);
    $stock = intval($_POST['stock'] ?? 0);

    // Validate inputs
    if ($name === '' || $price <= 0 || $description === '' || $category === '' || $stock < 0) {
        echo "<p style='color:red;'>Please fill out all fields correctly.</p>";
    } else {
        // Handle file upload
        $photo = $_POST['old_photo'] ?? '';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photo = basename($_FILES['photo']['name']);
            move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . '/../image/' . $photo);
        }

        // Insert or update
        try {
            if ($product_id) {
                // Update existing product
                $stmt = $_db->prepare(
                    'UPDATE product SET name = ?, price = ?, description = ?, category = ?, stock = ?, photo = ? WHERE id = ?'
                );
                $stmt->execute([$name, $price, $description, $category, $stock, $photo, $product_id]);
            } else {
                // Insert new product
                $stmt = $_db->prepare(
                    'INSERT INTO product (name, price, description, category, stock, photo) VALUES (?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([$name, $price, $description, $category, $stock, $photo]);
            }
        } catch (PDOException $e) {
            echo "<p style='color:red;'>SQL Error: " . $e->getMessage() . "</p>";
        }
    }

    // Redirect back to clear GET parameters and reload list
    header('Location: admin.php');
    exit;
}

// Fetch all products for display
$stmt = $_db->query('SELECT * FROM product');
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Products</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h2, h3 { color: #333; }
        form { margin-bottom: 20px; }
        input[type="text"], input[type="number"], textarea, select { width: 100%; padding: 10px; margin: 10px 0; }
        button { background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        button:hover { background-color: #45a049; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<h2>Manage Products</h2>
<?php if ($product): ?>
    <h3>Update Product</h3>
<?php else: ?>
    <h3>Add New Product</h3>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <?php if ($product): ?>
        <input type="hidden" name="id" value="<?= $product['id'] ?>">
    <?php endif; ?>
    <input type="hidden" name="old_photo" value="<?= htmlspecialchars($product['photo'] ?? '') ?>">

    <label for="name">Product Name:</label>
    <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>

    <label for="price">Price (RM):</label>
    <input type="number" name="price" id="price" step="0.01" value="<?= htmlspecialchars($product['price'] ?? '') ?>" required>

    <label for="description">Description:</label>
    <textarea name="description" id="description" required><?= htmlspecialchars($product['description'] ?? '') ?></textarea>

    <label for="category">Category:</label>
    <select name="category" id="category" required>
        <?php foreach (['Phone', 'Laptop', 'Tablet', 'Accessory'] as $cat): ?>
            <option value="<?= $cat ?>" <?= (isset($product['category']) && $product['category'] === $cat) ? 'selected' : '' ?>><?= $cat ?></option>
        <?php endforeach; ?>
    </select>

    <label for="stock">Stock Quantity:</label>
    <input type="number" name="stock" id="stock" value="<?= htmlspecialchars($product['stock'] ?? '') ?>" required>

    <label for="photo">Product Photo:</label>
    <input type="file" name="photo" id="photo">
    <?php if ($product && $product['photo']): ?>
        <br><img src="../image/<?= htmlspecialchars($product['photo']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" width="100"><br>
    <?php endif; ?>

    <button type="submit"><?= $product ? 'Update' : 'Add' ?> Product</button>
</form>

<h3>All Products</h3>
<table>
    <tr>
        <th>Name</th><th>Price (RM)</th><th>Description</th><th>Category</th><th>Stock</th><th>Photo</th><th>Action</th>
    </tr>
    <?php foreach ($products as $prod): ?>
        <tr>
            <td><?= htmlspecialchars($prod['name']) ?></td>
            <td><?= number_format($prod['price'], 2) ?></td>
            <td><?= htmlspecialchars($prod['description']) ?></td>
            <td><?= htmlspecialchars($prod['category']) ?></td>
            <td><?= $prod['stock'] ?></td>
            <td><img src="../image/<?= htmlspecialchars($prod['photo']) ?>" alt="" width="50"></td>
            <td>
                <a href="admin.php?id=<?= $prod['id'] ?>">Edit</a> |
                <a href="../product/delete_product.php?id=<?= $prod['id'] ?>" onclick="return confirm('Delete this product?');">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="../index.php" class="go-to-cart-button">Back to home page</a> 
</body>
</html>
