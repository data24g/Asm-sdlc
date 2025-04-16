<?php
require '../connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Username = trim($_POST["Name_rq"]);
    $Password = trim($_POST["Password_rq"]);
    $Email = trim($_POST["Email_rq"]);
    $Number = trim($_POST["Number_rq"]);
    $Address = trim($_POST["Address_rq"]);

    // Kiểm tra email đã tồn tại chưa
    $check_email = "SELECT Email FROM users WHERE Email = ?";
    $stmt_check = mysqli_prepare($conn, $check_email);
    mysqli_stmt_bind_param($stmt_check, "s", $Email);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);
    
    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        echo "Email already exists!";
        exit();
    }
    mysqli_stmt_close($stmt_check);

    // Mã hóa password
    $hashed_password = password_hash($Password, PASSWORD_DEFAULT);

    // Thêm user vào database
    $sql = "INSERT INTO users (Username, Password, Email, Number, Address) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssss", $Username, $hashed_password, $Email, $Number, $Address);
        
        if (mysqli_stmt_execute($stmt)) {
            // Lấy ID user vừa tạo
            $user_id = mysqli_insert_id($conn);
            
            // Bắt đầu session và lưu thông tin user
            session_start();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_email'] = $Email;
            $_SESSION['user_role'] = 'user'; // Mặc định là user
            
            header("Location: ../form-home/ASM1.html");
            exit();
        } else {
            echo "Registration failed: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>