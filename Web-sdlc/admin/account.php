<?php
require '../connect.php';

// Handle Add User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $username = $conn->real_escape_string($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Mã hóa mật khẩu
        $email = $conn->real_escape_string($_POST['email']);
        $number = $conn->real_escape_string($_POST['number']);
        $address = $conn->real_escape_string($_POST['address']);

        $sql = "INSERT INTO users (Username, Password, Email, Number, Address) 
                VALUES ('$username', '$password', '$email', '$number', '$address')";

        if ($conn->query($sql)) {
            echo "<div class='success-message'>New user added successfully!</div>";
        } else {
            echo "<div class='error-message'>Error: " . $conn->error . "</div>";
        }
    }
    elseif ($_POST['action'] === 'update') {
        $id = intval($_POST['id']);
        $username = $conn->real_escape_string($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Mã hóa mật khẩu khi cập nhật
        $email = $conn->real_escape_string($_POST['email']);
        $number = $conn->real_escape_string($_POST['number']);
        $address = $conn->real_escape_string($_POST['address']);

        $sql = "UPDATE users SET 
                Username = '$username', 
                Password = '$password', 
                Email = '$email', 
                Number = '$number', 
                Address = '$address' 
                WHERE id = $id";

        if ($conn->query($sql)) {
            echo "<div class='success-message'>User updated successfully!</div>";
        } else {
            echo "<div class='error-message'>Error updating user: " . $conn->error . "</div>";
        }
    }
}

// Handle Delete User
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM users WHERE id = $id";

    if ($conn->query($sql)) {
        echo "<div class='success-message'>User deleted successfully!</div>";
    } else {
        echo "<div class='error-message'>Error deleting user: " . $conn->error . "</div>";
    }
}

// Handle Edit User Form
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        echo '<div class="form-container">';
        echo '<h2>Edit User</h2>';
        echo '<form action="account.php" method="post">';
        echo '<input type="hidden" name="action" value="update">';
        echo '<input type="hidden" name="id" value="' . $user['id'] . '">';
        echo '<div class="form-group"><label>Username:</label><input type="text" name="username" value="' . htmlspecialchars($user['Username']) . '" required></div>';
        echo '<div class="form-group"><label>Password:</label><input type="password" name="password" placeholder="Enter new password to change" required></div>';
        echo '<div class="form-group"><label>Email:</label><input type="email" name="email" value="' . htmlspecialchars($user['Email']) . '" required></div>';
        echo '<div class="form-group"><label>Phone Number:</label><input type="text" name="number" value="' . htmlspecialchars($user['Number']) . '" required></div>';
        echo '<div class="form-group"><label>Address:</label><input type="text" name="address" value="' . htmlspecialchars($user['Address']) . '" required></div>';
        echo '<button type="submit" class="submit-btn">Update User</button>';
        echo '</form>';
        echo '</div>';
    }
}

// Display User Table
$sql = "SELECT id, Username, Password, Email, Number, Address FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>
    <thead>
    <tr>
    <th>Username</th>
    <th>Password</th>
    <th>Email</th>
    <th>Phone Number</th>
    <th>Address</th>
    <th>Actions</th>
    </tr>
    </thead>
    <tbody>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>" . htmlspecialchars($row["Username"]) . "</td>
        <td>••••••••</td> <!-- Hiển thị dấu • thay vì mật khẩu thực tế -->
        <td>" . htmlspecialchars($row["Email"]) . "</td>
        <td>" . htmlspecialchars($row["Number"]) . "</td>
        <td>" . htmlspecialchars($row["Address"]) . "</td>
        <td class='action-buttons'>
            <a href='account.php?action=edit&id=" . $row["id"] . "' class='edit-btn'>Edit</a>
            <a href='account.php?action=delete&id=" . $row["id"] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
        </td>
        </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p class='no-users'>No users found</p>";
}

$conn->close();

// Message styles
echo '<style>
.success-message {
    padding: 15px;
    background-color: #d4edda;
    color: #155724;
    border-radius: 8px;
    margin: 15px 0;
    border: 1px solid #c3e6cb;
}

.error-message {
    padding: 15px;
    background-color: #f8d7da;
    color: #721c24;
    border-radius: 8px;
    margin: 15px 0;
    border: 1px solid #f5c6cb;
}
</style>';
?>