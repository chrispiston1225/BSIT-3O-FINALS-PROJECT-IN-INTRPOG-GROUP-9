<?php
// Include the database connection
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = $_POST["username"]; // If using either username or email for login
    $password = $_POST["password"];

    // Check if user exists in database
    $query = $conn->prepare("SELECT * FROM user WHERE username = ? OR email = ?");
    $query->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $query->execute();
    $result = $query->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            // Successful login
            $_SESSION["login"] = true;
            $_SESSION["id"] = $row["id"];

            if ($row['usertype'] == 'user') {
                header("location: home.php");
            } elseif ($row['usertype'] == 'employee') {
                header("location: employee.php");
            } elseif ($row['usertype'] == 'admin') {
                header("location: dashboard.php");
            }
            exit;
        } else {
            echo "<script> alert('Wrong Password'); </script>";
            header("location: login.php");
            exit;
        }
    } else {
        echo "<script> alert('User Not Registered'); </script>";
        header("location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Milk Tea Store</title>
    <link rel="stylesheet" href="css/login-register.css">
</head>
<body style="background-image: url('img/bg-pattern.svg');">

<form method="POST" action="login.php">
<h2>Login to Your Account</h2>
    <label for="email">Email:</label>
    <input type="text" name="username" required>
    
    <label for="password">Password:</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</form>

</body>
</html>
