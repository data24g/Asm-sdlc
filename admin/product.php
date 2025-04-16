<?php
require '../connect.php';

// Handle Add/Update Product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $productName = $conn->real_escape_string($_POST['productName']);
        $description = $conn->real_escape_string($_POST['description']);
        $price = floatval($_POST['price']);
        $category = $conn->real_escape_string($_POST['category']);
        $stockQuantity = intval($_POST['stockQuantity']);
        $imageURL = $conn->real_escape_string($_POST['imageURL']);

        if ($_POST['action'] === 'add') {
            $sql = "INSERT INTO products (ProductName, Description, Price, Category, StockQuantity, ImageURL) 
                    VALUES ('$productName', '$description', $price, '$category', $stockQuantity, '$imageURL')";
        } 
        elseif ($_POST['action'] === 'update') {
            $id = intval($_POST['id']);
            $sql = "UPDATE products SET 
                    ProductName = '$productName', 
                    Description = '$description', 
                    Price = $price, 
                    Category = '$category', 
                    StockQuantity = $stockQuantity, 
                    ImageURL = '$imageURL' 
                    WHERE id = $id";
            
            if ($conn->query($sql)) {
                echo "<div class='success-message'>Product updated successfully!</div>";
            } else {
                echo "<div class='error-message'>Update error: " . $conn->error . "</div>";
            }
        }
    }
}

// Handle Delete Product
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM products WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo "<div class='success-message'>Product deleted successfully!</div>";
    } else {
        echo "<div class='error-message'>Delete error: " . $conn->error . "</div>";
    }
}

// Show Edit Form
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM products WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        
        echo '<div class="form-container">';
        echo '<h2>Edit Product</h2>';
        echo '<form action="product.php" method="post">';
        echo '<input type="hidden" name="action" value="update">';
        echo '<input type="hidden" name="id" value="' . $product['id'] . '">';
        echo '<div class="form-group"><label>Product Name:</label><input type="text" name="productName" value="' . htmlspecialchars($product['ProductName']) . '" required></div>';
        echo '<div class="form-group"><label>Description:</label><textarea name="description" required>' . htmlspecialchars($product['Description']) . '</textarea></div>';
        echo '<div class="form-group"><label>Price:</label><input type="number" step="0.01" name="price" value="' . htmlspecialchars($product['Price']) . '" required></div>';
        echo '<div class="form-group"><label>Category:</label><input type="text" name="category" value="' . htmlspecialchars($product['Category']) . '" required></div>';
        echo '<div class="form-group"><label>Stock Quantity:</label><input type="number" name="stockQuantity" value="' . htmlspecialchars($product['StockQuantity']) . '" required></div>';
        echo '<div class="form-group"><label>Image URL:</label><input type="text" name="imageURL" value="' . htmlspecialchars($product['ImageURL']) . '"></div>';
        echo '<button type="submit" class="submit-btn">Update Product</button>';
        echo '</form>';
        echo '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="user.css">
</head>
<body>
    <div class="main-container">
        <div class="navigation-buttons">
            <a href="ASM1.html" class="nav-btn home-btn">Home</a>
            <a href="form-account.php" class="nav-btn user-btn">User Management</a>
        </div>

        <h1>Product Management</h1>

        <div class="form-container">
            <h2>Add New Product</h2>
            <form action="product.php" method="post">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label>Product Name:</label>
                    <input type="text" name="productName" required>
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label>Price ($):</label>
                    <input type="number" step="0.01" name="price" required>
                </div>
                <div class="form-group">
                    <label>Category:</label>
                    <input type="text" name="category" required>
                </div>
                <div class="form-group">
                    <label>Stock Quantity:</label>
                    <input type="number" name="stockQuantity" required>
                </div>
                <div class="form-group">
                    <label>Image URL:</label>
                    <input type="text" name="imageURL">
                </div>
                <button type="submit" class="submit-btn">Add Product</button>
            </form>
        </div>

        <div class="user-list-container">
            <div class="user-list-header">
                <h2>Product List</h2>
                <form action="product.php" method="get">
                    <button type="submit" class="refresh-btn">Refresh</button>
                </form>
            </div>

            <div class="table-container">
                <?php
                $sql = "SELECT * FROM products";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<table>
                    <thead>
                    <tr>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Stock</th>
                    <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>";

                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                        <td>" . htmlspecialchars($row["ProductName"]) . "</td>
                        <td>" . htmlspecialchars(substr($row["Description"], 0, 50)) . "...</td>
                        <td>$" . number_format($row["Price"], 2) . "</td>
                        <td>" . htmlspecialchars($row["Category"]) . "</td>
                        <td>" . htmlspecialchars($row["StockQuantity"]) . "</td>
                        <td class='action-buttons'>
                            <a href='product.php?action=edit&id=" . $row["id"] . "' class='edit-btn'>Edit</a>
                            <a href='product.php?action=delete&id=" . $row["id"] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this product?\")'>Delete</a>
                        </td>
                        </tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p class='no-users'>No products found</p>";
                }

                $conn->close();
                ?>
            </div>
        </div>
    </div>
</body>
</html>