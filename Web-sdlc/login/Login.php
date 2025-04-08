<?php
require '../connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["Email_DN"]);
    $password = trim($_POST["Password_DN"]);

    // Lấy thông tin user từ database
    $sql = "SELECT id, Username, Password, role FROM users WHERE Email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            mysqli_stmt_bind_result($stmt, $user_id, $username, $hashed_password, $role);
            mysqli_stmt_fetch($stmt);

            if (password_verify($password, $hashed_password)) {
                // Bắt đầu session và lưu thông tin user
                session_start();
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_name'] = $username;
                $_SESSION['user_role'] = $role;
                
                // Chuyển hướng theo role
                if ($role == 'admin') {
                    header("Location: ../admin/ASM1.html");
                } else {
                    header("Location: ../form-home/ASM1.html");
                }
                exit();
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "User not found!";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>