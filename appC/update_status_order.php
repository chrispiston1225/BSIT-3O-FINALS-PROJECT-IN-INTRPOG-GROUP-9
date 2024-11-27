<?php
include 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve inputs from the POST request
    $status = $_POST['status'] ?? '';
    $order_date = $_POST['order_date'] ?? '';

    // Validate inputs
    $valid_statuses = ['Processing', 'Ongoing', 'Delivered', 'Complete']; // List of allowed statuses
    if (!in_array($status, $valid_statuses)) {
        http_response_code(400);
        echo "Invalid status value. Allowed values are: " . implode(', ', $valid_statuses) . ".";
        exit;
    }

    if (empty($order_date)) {
        http_response_code(400);
        echo "Order date is required.";
        exit;
    }

    // Prepare the update query
    $sql = "UPDATE tblorder SET status = ? WHERE dateandtime = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        http_response_code(500);
        echo "Failed to prepare the database query: " . $conn->error;
        exit;
    }

    // Bind parameters and execute the query
    $stmt->bind_param("ss", $status, $order_date);
    if ($stmt->execute()) {
        http_response_code(200);
        echo "Order status updated successfully to '$status'.";
    } else {
        http_response_code(500);
        echo "Failed to update order status: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    http_response_code(405);
    echo "Method not allowed. Only POST requests are accepted.";
}

// Close the database connection
$conn->close();
?>
