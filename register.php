<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $phone_number = trim($_POST['phone_number']);
    $role = $_POST['role'];

    $errors = [];


    if (empty($username)) {
        $errors['username'] = "Username is required.";
    }

 
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

 
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters.";
    }

    
    if (empty($phone_number)) {
        $errors['phone_number'] = "Phone number is required.";
    } elseif (!preg_match("/^\d{10,15}$/", $phone_number)) {
        $errors['phone_number'] = "Invalid phone number.";
    }
    if (empty($errors)) {
    
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, email, password, phone_number, role) 
                  VALUES ('$username', '$email', '$hashed_password', '$phone_number', '$role')";
        if (mysqli_query($conn, $query)) {
            echo '<script>window.location.href = "loginphp.php";</script>';
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
      
        $errorString = http_build_query($errors);
        echo '<script>window.location.href = "interface_register.php";</script>';
   }
}
?>