<?php
// KẾT NỐI DATABASE asm
$servername = "localhost";
$username   = "root";   // mặc định XAMPP
$password   = "";       // mặc định để trống
$dbname     = "asm";    // đúng với DB bạn đang dùng

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$message = "";

// XỬ LÝ KHI ADMIN SUBMIT FORM
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $price       = (int)($_POST['price'] ?? 0);
    $image       = trim($_POST['image'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($name === '' || $price <= 0 || $image === '') {
        $message = "Vui lòng nhập TÊN MÓN, GIÁ > 0 và TÊN FILE ẢNH.";
    } else {
        // Dùng prepared statement cho an toàn
        $stmt = $conn->prepare(
            "INSERT INTO products (name, price, image, description) VALUES (?, ?, ?, ?)"
        );
        if ($stmt) {
            $stmt->bind_param("siss", $name, $price, $image, $description);
            if ($stmt->execute()) {
                $message = "Thêm sản phẩm thành công!";
            } else {
                $message = "Lỗi khi thêm sản phẩm: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Không chuẩn bị được câu lệnh SQL: " . $conn->error;
        }
    }
}

// Lấy danh sách sản phẩm để admin dễ kiểm tra
$productResult = $conn->query("SELECT id, name, price, image FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - Thêm sản phẩm</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        h1 {
            margin-bottom: 10px;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
        }
        .message.success {
            background: #d4edda;
            color: #155724;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
        }
        form {
            background: #fff;
            padding: 15px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 500px;
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        button {
            margin-top: 15px;
            padding: 10px 16px;
            background: #007bff;
            border: none;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }
        th, td {
            padding: 8px 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
        .price-cell {
            text-align: right;
        }
    </style>
</head>
<body>
    <h1>Admin - Thêm sản phẩm mới</h1>

    <?php if ($message !== ""): ?>
        <div class="message <?php echo (str_contains($message, 'thành công') ? 'success' : 'error'); ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- FORM THÊM SẢN PHẨM -->
    <form method="POST" action="">
        <label for="name">Tên món ăn:</label>
        <input type="text" id="name" name="name" placeholder="VD: Bún Bò Huế" required>

        <label for="price">Giá (VND):</label>
        <input type="number" id="price" name="price" placeholder="VD: 65000" min="1000" step="1000" required>

        <label for="image">Tên file ảnh (trong thư mục ../img/):</label>
        <input type="text" id="image" name="image" placeholder="VD: bun-bo-hue.jpg" required>

        <label for="description">Mô tả món ăn:</label>
        <textarea id="description" name="description" placeholder="VD: Bún bò Huế chuẩn vị, nước lèo đậm đà."></textarea>

        <button type="submit">Thêm sản phẩm</button>
    </form>

    <!-- DANH SÁCH SẢN PHẨM HIỆN CÓ -->
    <h2>Danh sách sản phẩm hiện tại</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Tên món</th>
            <th>Giá (VND)</th>
            <th>Ảnh</th>
        </tr>
        <?php if ($productResult && $productResult->num_rows > 0): ?>
            <?php while ($p = $productResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo (int)$p['id']; ?></td>
                    <td><?php echo htmlspecialchars($p['name']); ?></td>
                    <td class="price-cell"><?php echo number_format((int)$p['price'], 0, ",", "."); ?>₫</td>
                    <td><?php echo htmlspecialchars($p['image']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Chưa có sản phẩm nào.</td>
            </tr>
        <?php endif; ?>
    </table>

</body>
</html>
<?php
$conn->close();
?>
