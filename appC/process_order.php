<?php 
require 'db.php';

if ( isset($_POST['user_id'])) {
    // Sanitize and validate input
    $user_id = (int)$_POST['user_id'];
    $order_status = 'Pending';

    // Start a transaction
    $conn->begin_transaction();

    // Fetch items from the user's cart
    $cart_query = "SELECT * FROM tblcart WHERE id = ?";
    $cart_stmt = $conn->prepare($cart_query);
    if (!$cart_stmt) {
        $conn->rollback();
        error_log("Cart query preparation failed: " . $conn->error);
        echo "An error occurred. Please try again later.";
        return;
    }
    $cart_stmt->bind_param("i", $user_id);
    $cart_stmt->execute();
    $cart_items = $cart_stmt->get_result();

    // Check if the cart is empty
    if ($cart_items->num_rows === 0) {
        $conn->rollback();
        error_log("No items found in the cart for user ID: " . $user_id);
        echo "An error occurred. Please try again later.";
        return;
    }

    // Generate a unique order number
    $order_no = rand(1000, 9999);
   

    // Prepare the insert query for orders
    $order_query = "INSERT INTO tblorder (menu_id, qt, id, dateandtime, status) 
                VALUES (?, ?, ?, NOW(), ?)";
    $order_stmt = $conn->prepare($order_query);
    if (!$order_stmt) {
        $conn->rollback();
        error_log("Order query preparation failed: " . $conn->error);
        echo "An error occurred. Please try again later.";
        return;
    }

    // Insert each cart item as an order
    while ($item = $cart_items->fetch_assoc()) {
        $product_id = (int)$item['menu_id'];
        $quantity = (int)$item['qt'];

        $order_stmt->bind_param("iiis", 
            $product_id, 
            $quantity, 
            $user_id, 
            $order_status

           
        );

        // Execute the order statement inside the loop
        if (!$order_stmt->execute()) {
            $conn->rollback();
            error_log("Order insertion failed: " . $order_stmt->error);
            echo "An error occurred. Please try again later.";
            return;
        }
    }

    // Clear the user's cart after placing the order
    $delete_cart_query = "DELETE FROM tblcart WHERE id = ?";
    $delete_cart_stmt = $conn->prepare($delete_cart_query);
    if (!$delete_cart_stmt) {
        $conn->rollback();
        error_log("Delete cart query preparation failed: " . $conn->error);
        echo "An error occurred. Please try again later.";
        return;
    }
    $delete_cart_stmt->bind_param("i", $user_id);
    if (!$delete_cart_stmt->execute()) {
        $conn->rollback();
        error_log("Cart deletion failed: " . $delete_cart_stmt->error);
        echo "An error occurred. Please try again later.";
        return;
    }

    // Commit the transaction
    $conn->commit();
    echo "success";

    // Close prepared statements
    if (isset($cart_stmt)) $cart_stmt->close();
    if (isset($order_stmt)) $order_stmt->close();
    if (isset($delete_cart_stmt)) $delete_cart_stmt->close();

} else {
    // Improved error message for missing input
    echo "Error: Payment method or user ID is missing.";
}

$conn->close();
?>
