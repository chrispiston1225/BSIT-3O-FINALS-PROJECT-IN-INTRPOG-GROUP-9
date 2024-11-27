<?php
// Include the database connection
require "db.php";

// Check if the connection to the database was successful
if ($conn->connect_error) {
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}

// Check if a valid ID is passed in the GET request
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = (int) $_GET['id']; // Ensure the ID is an integer

    // Prepare the DELETE SQL statement
    $stmt = $conn->prepare("DELETE FROM tblmenu WHERE menu_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $product_id);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            // Redirect to the menu page on successful deletion
            header("Location: menu.php");
            exit;
        } else {
            // Output SQL error for debugging
            echo "<p>Error deleting product: " . htmlspecialchars($stmt->error) . "</p>";
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // Handle errors in preparing the statement
        echo "<p>Error preparing the statement: " . htmlspecialchars($conn->error) . "</p>";
    }
} else {
    // Handle invalid or missing product ID
    echo "<p>Invalid product ID.</p>";
}

// Close the database connection
$conn->close();
?>