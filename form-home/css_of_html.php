<?php // KẾT NỐI DATABASE asm
$servername ="localhost";
$username ="root"; // mặc định XAMPP
$password =""; // mặc định để trống
$dbname ="asm"; // DB bạn đang dùng

$conn =new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$message ="";
$isSuccess =false;
$editMode =false;
$editProduct =null;

// XỬ LÝ XOÁ (DELETE)
if (isset($_GET['delete_id'])) {
    $deleteId =(int)$_GET['delete_id'];

    if ($deleteId > 0) {
        $stmt =$conn->prepare("DELETE FROM products WHERE id = ?");

        if ($stmt) {
            $stmt->bind_param("i", $deleteId);

            if ($stmt->execute()) {
                $message ="Xoá sản phẩm ID {$deleteId} thành công.";
                $isSuccess =true;
            }

            else {
                $message ="Lỗi khi xoá sản phẩm: " . $stmt->error;
            }

            $stmt->close();
        }

        else {
            $message ="Không chuẩn bị được câu lệnh xoá: " . $conn->error;
        }
    }
}

// XỬ LÝ THÊM / CẬP NHẬT (CREATE / UPDATE)
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $action =$_POST['action'] ?? 'create';
    $name =trim($_POST['name'] ?? '');
    $price =(int)($_POST['price'] ?? 0);
    $image =trim($_POST['image'] ?? '');
    $description =trim($_POST['description'] ?? '');

    if ($name ==='' || $price <=0 || $image ==='') {
        $message ="Vui lòng nhập đầy đủ: TÊN MÓN, GIÁ > 0 và TÊN FILE ẢNH.";
        $isSuccess =false;
    }

    else {
        if ($action ==='update') {
            // UPDATE
            $id =(int)($_POST['id'] ?? 0);

            if ($id > 0) {
                $stmt =$conn->prepare("UPDATE products SET name = ?, price = ?, image = ?, description = ? WHERE id = ?"
                );

                if ($stmt) {
                    $stmt->bind_param("sissi", $name, $price, $image, $description, $id);

                    if ($stmt->execute()) {
                        $message ="Cập nhật sản phẩm ID {$id} thành công.";
                        $isSuccess =true;
                    }

                    else {
                        $message ="Lỗi khi cập nhật sản phẩm: " . $stmt->error;
                    }

                    $stmt->close();
                }

                else {
                    $message ="Không chuẩn bị được câu lệnh UPDATE: " . $conn->error;
                }
            }

            else {
                $message ="ID sản phẩm không hợp lệ.";
            }
        }

        else {
            // CREATE
            $stmt =$conn->prepare("INSERT INTO products (name, price, image, description) VALUES (?, ?, ?, ?)"
            );

            if ($stmt) {
                $stmt->bind_param("siss", $name, $price, $image, $description);

                if ($stmt->execute()) {
                    $message ="Thêm sản phẩm mới thành công!";
                    $isSuccess =true;
                }

                else {
                    $message ="Lỗi khi thêm sản phẩm: " . $stmt->error;
                }

                $stmt->close();
            }

            else {
                $message ="Không chuẩn bị được câu lệnh INSERT: " . $conn->error;
            }
        }
    }
}

// XỬ LÝ LOAD DATA ĐỂ SỬA (EDIT MODE)
if (isset($_GET['edit_id'])) {
    $editId =(int)$_GET['edit_id'];

    if ($editId > 0) {
        $stmt =$conn->prepare("SELECT id, name, price, image, description FROM products WHERE id = ?");

        if ($stmt) {
            $stmt->bind_param("i", $editId);
            $stmt->execute();
            $result =$stmt->get_result();

            if ($result && $result->num_rows ===1) {
                $editProduct =$result->fetch_assoc();
                $editMode =true;
            }

            $stmt->close();
        }
    }
}

// LẤY DANH SÁCH SẢN PHẨM (READ)
$productResult =$conn->query("SELECT id, name, price, image, description FROM products ORDER BY id DESC");

?>< !DOCTYPE html><html lang="vi"><head><meta charset="UTF-8"><title>Admin - Quản lý sản phẩm (CRUD)</title><style>body {
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
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

button,
.btn-link {
    margin-top: 15px;
    padding: 8px 14px;
    background: #007bff;
    border: none;
    color: #fff;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}

button:hover,
.btn-link:hover {
    background: #0056b3;
}

.btn-secondary {
    background: #6c757d;
}

.btn-secondary:hover {
    background: #565e64;
}

.btn-danger {
    background: #dc3545;
}

.btn-danger:hover {
    background: #b02a37;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
}

th,
td {
    padding: 8px 10px;
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background: #f0f0f0;
}

.price-cell {
    text-align: right;
    white-space: nowrap;
}

.actions {
    white-space: nowrap;
}

</style></head><body><h1>Admin - Quản lý sản phẩm (CRUD)</h1><?php if ($message !==""): ?><div class="message <?php echo $isSuccess ? 'success' : 'error'; ?>"><?php echo htmlspecialchars($message);
?></div><?php endif;
?>< !-- FORM THÊM / SỬA SẢN PHẨM --><form method="POST" action=""><h2><?php echo $editMode ? 'Cập nhật sản phẩm' : 'Thêm sản phẩm mới';
?></h2><?php if ($editMode): ?><input type="hidden" name="id" value="<?php echo (int)$editProduct['id']; ?>"><?php endif;
?><label for="name">Tên món ăn:</label><input type="text"
id="name"
name="name"
placeholder="VD: Bún Bò Huế"
required value="<?php echo $editMode ? htmlspecialchars($editProduct['name']) : ''; ?>"
><label for="price">Giá (VND):</label><input type="number"
id="price"
name="price"
placeholder="VD: 65000"
min="1000"
step="1000"
required value="<?php echo $editMode ? (int)$editProduct['price'] : ''; ?>"
><label for="image">Tên file ảnh (trong thư mục ../img/):</label><input type="text"
id="image"
name="image"
placeholder="VD: bun-bo-hue.jpg"
required value="<?php echo $editMode ? htmlspecialchars($editProduct['image']) : ''; ?>"
><label for="description">Mô tả món ăn:</label><textarea id="description" name="description" placeholder="VD: Bún bò Huế chuẩn vị, nước lèo đậm đà."><?php echo $editMode ? htmlspecialchars($editProduct['description']) : '';
?></textarea><input type="hidden" name="action" value="<?php echo $editMode ? 'update' : 'create'; ?>"><button type="submit"><?php echo $editMode ? 'Cập nhật' : 'Thêm mới';
?></button><?php if ($editMode): ?><a href="admin_products.php" class="btn-link btn-secondary">Hủy chỉnh sửa</a><?php endif;
?></form>< !-- DANH SÁCH SẢN PHẨM (READ + ACTION EDIT/DELETE) --><h2>Danh sách sản phẩm</h2><table><tr><th>ID</th><th>Tên món</th><th>Giá (VND)</th><th>Ảnh</th><th>Mô tả</th><th>Thao tác</th></tr><?php if ($productResult && $productResult->num_rows > 0): ?><?php while ($p =$productResult->fetch_assoc()): ?><tr><td><?php echo (int)$p['id'];
?></td><td><?php echo htmlspecialchars($p['name']);
?></td><td class="price-cell"><?php echo number_format((int)$p['price'], 0, ",", ".");
?>₫</td><td><?php echo htmlspecialchars($p['image']);
?></td><td><?php echo nl2br(htmlspecialchars($p['description']));
?></td><td class="actions"><a href="admin_products.php?edit_id=<?php echo (int)$p['id']; ?>" class="btn-link">Sửa</a><a href="admin_products.php?delete_id=<?php echo (int)$p['id']; ?>"
class="btn-link btn-danger"
onclick="return confirm('Bạn chắc chắn muốn xoá sản phẩm này?');">Xoá </a></td></tr><?php endwhile;
?><?php else: ?><tr><td colspan="6">Chưa có sản phẩm nào.</td></tr><?php endif;
?></table></body></html><?php $conn->close();
?>