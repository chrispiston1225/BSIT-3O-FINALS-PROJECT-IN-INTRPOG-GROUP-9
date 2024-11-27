<?php
// Include the database connection
include('db.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address= $_POST['address'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    $checkEmailQuery = "SELECT email FROM user WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "<script>
                alert('Email is already registered!');
                window.location.href = 'register.php';
              </script>";
        exit;
    }
    $stmt->close();

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Hash the password before storing it (recommended for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO user (username, email, contact, address, usertype, password ) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $usertype = "user"; // Setting usertype value
    $stmt->bind_param('ssssss', $username, $email, $contact, $address, $usertype, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>
                alert('Signup successful!');
                window.location.href = 'login.php'; // Redirect after showing the alert
              </script>";
        exit;
    } else {
        echo "<script> alert('Error: " . $stmt->error .  " ')</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Milk Tea Store</title>
    <link rel="stylesheet" href="css/login-register.css">
</head>
<body style="background-image: url('img/bg-pattern.svg');">

<form method="POST" action="register.php">
<h2>Register an Account</h2>
    <label for="username">Username:</label>
    <input type="text" name="username" required>
    
    <label for="email">Email:</label>
    <input type="email" name="email" required>

    <label for="contact">Contact:</label>
    <input type="text" name="contact" required>

    <label for="address">Address:</label>
    <input type="text" name="address" required>
    
    <label for="password">Password:</label>
    <input type="password" name="password" required>

    <button type="submit">Register</button>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</form>

</body>
</html>
