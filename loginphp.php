<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
      <?php require 'header.php';  ?>
      <?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Fetch user from database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            // Store user data in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] == 'manager') {
                echo '<script>window.location.href = "managerEvent.php";</script>';

            } else {
                
                echo '<script>window.location.href = "dashboard.php";</script>';
            }
            exit(); 
        } else {
            echo '<script>window.location.href = "loginphp.php";</script>';
            exit();
        }
    } else {
        echo '<script>window.location.href = "loginphp.php";</script>';
        exit();
    }
}
?>

    <div class="container">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="Interface_register.php">Register</a></p>
        <?php if (isset($_GET['error'])): ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php endif; ?>
    </div>
    <?php require 'footer.php';?>   
</body>
</html>