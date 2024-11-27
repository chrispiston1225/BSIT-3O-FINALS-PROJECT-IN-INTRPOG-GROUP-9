<?php 
$title = 'Order';
include './view/header.php';


$user_id = $_SESSION['id'];
if (!isset($_SESSION['id'])) {
    echo "<p>You must be logged in to view this page.</p>";
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
$stmt->close();

// Updated query without the admin_id, since it's a single store
$query = "SELECT tblorder.*, tblmenu.*
          FROM tblorder 
          JOIN tblmenu ON tblorder.menu_id = tblmenu.menu_id 
          WHERE tblorder.id = ? AND tblorder.status IN ('Pending', 'Processing','Ongoing', 'Delivered')
          ORDER BY tblorder.dateandtime DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$results = $stmt->get_result();

$current_date = null;
$grand_total = 0;
?>

<?php if ($results->num_rows === 0): ?>
    <img src="img//empty (1).png" alt="" class="no-found-img">
    <?php else: ?>
        <div class="orders-container">
        <h1 class="sign">Orders</h1>
            <?php
            $order_subtotal = 0;
            while ($row = $results->fetch_assoc()): 
                if ($current_date !== $row['dateandtime']) {
                    if ($current_date !== null) {
                        echo "</tbody></table><tr><td colspan='2' class='subtotal-label'>Subtotal:</td>
                              <td class='subtotal-amount'>₱" . number_format($order_subtotal, 0) . "</td></tr>";
                        echo '</div>'; 
                        $grand_total += $order_subtotal;
                        $order_subtotal = 0; 
                    }

                    $current_date = $row['dateandtime'];
                    echo '<div class="order-group">';
                    echo "<p class='order-date'><strong>Order Date</strong>: " . date('Y-m-d H:i:s', strtotime($current_date)) . "</p>";
                    echo "<p class='order-status'><strong>Status</strong>: " . htmlspecialchars($row['status']) . "</p>";

                    echo '<table class="order-table">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Total Price</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>';
                }

                $item_total = $row['price'] * $row['qt'];
                $order_subtotal += $item_total;
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['menuname']); ?></td>
                    <td>₱<?php echo number_format($item_total, 0); ?></td>
                    <td><?php echo $row['qt']; ?></td>
                </tr>
               
            <?php endwhile; ?>

            <?php if ($current_date !== null): ?>
                </tbody>
                </table>
                    <td colspan="2" class="subtotal-label"><strong>Subtotal</strong>:</td>
                    <td class="subtotal-amount">₱<?php echo number_format($order_subtotal, 0); ?></td>
                   
                </div>
                <?php $grand_total += $order_subtotal; ?>
            <?php endif; ?>
            
            <div class="grand-total">
                <h3 class="h3-grantotal">All Orders Total: ₱<?php echo number_format($grand_total, 0); ?></h3>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>