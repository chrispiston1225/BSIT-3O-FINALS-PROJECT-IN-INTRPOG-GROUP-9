<?php
require './db.php';

if (!isset($_SESSION['id'])) {
    // Redirect to login page or handle as needed
    header('location: index.php');
    exit;
}
// Fetch logged-in user data from the database
$userId = $_SESSION['id'];

$query = "SELECT username, email FROM user WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'Retrofee'; ?></title>
    <!-- Styles -->
    <link rel="stylesheet" href="css/employee.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>
<body>
    <nav id="navbar">
        <!-- Responsive menu toggle button -->
        <label onclick="myFunction()" for="check" class="checkbtn">
            <i class="fas fa-bars"></i>
        </label>

        <!-- Company logo -->
        <img src="img/Milk.png" class="logo" alt="Logo">
        <ul class="ul-nav" id="ul-nav">
            <!-- Close button for responsive menu -->
            <label for="check" onclick="myFunction()" class="checkbtn escapebtn">
                <i class="fa fa-times"></i>
            </label>
            <li><a class="mb-hide" href="employee.php" onclick="myFunction()">Pending</a></li>
            <li><a class="mb-hide" href="order_employee.php" onclick="myFunction()">Orders</a></li>
            <li class="user-nav dropdown">
                <a href="#user" class="mb-hide"><i class="fa fa-user"></i></a>
                <div class="dropdown-content">
                    <a href="#"><?php echo htmlspecialchars($username); ?></a>
                    <a href="order_employee_history.php">Order History</a>
                    <a href="logout.php">Logout</a>
                </div>
            </li>
        </ul>
    </nav>
