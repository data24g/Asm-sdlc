<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="user.css">
</head>

<body>
    <div class="main-container">
        <div class="navigation-buttons">
            <a href="ASM1.html" class="submit-btn">Home</a>
            <a href="product.php" class="submit-btn">Product Management</a>
        </div>

        <h1>User Management</h1>

        <div class="form-container">
            <h2>Add New User</h2>
            <form action="account.php" method="post">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Phone Number:</label>
                    <input type="text" name="number" required>
                </div>
                <div class="form-group">
                    <label>Address:</label>
                    <input type="text" name="address" required>
                </div>
                <button type="submit" class="submit-btn">Add User</button>
            </form>
        </div>

        <div class="user-list-container">
            <div class="user-list-header">
                <h2>User List</h2>
                <form action="account.php" method="get">
                    <button type="submit" class="refresh-btn">Refresh Users</button>
                </form>
            </div>

            <div class="table-container">
                <?php include 'account.php'; ?>
            </div>
        </div>
    </div>
</body>

</html>