<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php require 'header.php';  ?>
    <div class="container">
        <h2>Register</h2>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Username" >
            <?php if (isset($_GET['username'])): ?>
                <p class="error"><?php echo $_GET['username']; ?></p>
            <?php endif; ?>
            <input type="email" name="email" placeholder="Email" >
            <?php if (isset($_GET['email'])): ?>
                <p class="error"><?php echo $_GET['email']; ?></p>
            <?php endif; ?>
            <input type="password" name="password" placeholder="Password" >
            <?php if (isset($_GET['password'])): ?>
                <p class="error"><?php echo $_GET['password']; ?></p>
            <?php endif; ?>
            <input type="text" name="phone_number" placeholder="Phone Number" >
            <?php if (isset($_GET['phone_number'])): ?>
                <p class="error"><?php echo $_GET['phone_number']; ?></p>
            <?php endif; ?>
            <select name="role">
                <option value="manager">Manager</option>
                <option value="user">User</option>
            </select>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="loginphp.php">Login</a></p>
    </div>
    <script scr="scripts.js"></script>
  git commit -m "modifiy"
</body>
</html>