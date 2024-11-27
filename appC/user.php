<?php
$title = 'Menu';
include './view/admin_header.php';

$menuItems = [];
$query = "SELECT * FROM user";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    $menuItems = $result->fetch_all(MYSQLI_ASSOC);
}

// Add new menu item
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address= $_POST['address'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO user (username, email, contact, address, usertype, password ) VALUES (?, ?, ?, ?, ?, ?)");
    $usertype = "employee"; // Setting usertype value
    $stmt->bind_param('ssssss', $username, $email, $contact, $address, $usertype, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>
                alert('Created Successful!');
                window.location.href = 'user.php'; // Redirect after showing the alert
              </script>";
        exit;
    } else {
        echo "<script> alert('Error: " . $stmt->error .  " ')</script>";
    }

    $stmt->close();
}
?>

<main class="user-main">
        <section class="menu">
            <h2>Users Info</h2>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($menuItems as $item): ?>
                        <tr>
                        <td><?php echo htmlspecialchars($item['id']); ?></td>
                            <td><?php echo htmlspecialchars($item['username']); ?></td>
                            <td><?php echo htmlspecialchars($item['email']); ?></td>
                            <td><?php echo htmlspecialchars($item['contact']); ?></td>
                            <td><?php echo htmlspecialchars($item['address']); ?></td>
                            <div class="btn-action">
                            <td>
                                <a class="btn-delete" href='delete_user.php?id=<?php echo $item['id']; ?>' class='btn btn-delete'><i class="fas fa-trash"></i></a>
                            </td>
                            </div>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
        <button id="addMenuBtn">Create Employee</button>
    </main>

    <!-- Add Menu Modal -->
    <div id="menuModal" class="modal">
        <div class="modal-content">
            <h2>Employee Information</h2>
            <span class="close">&times;</span>
            <form method="POST" enctype="multipart/form-data">
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

                <button type="submit">Create Employee</button>
            </form>
        </div>
    </div>

    
    <script src="js/script.js"></script>
    
</main>