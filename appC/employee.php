<?php 
$title = 'Employee Processing';
include './view/employee_header.php';

// Prepare SQL query
$sql = "SELECT tblorder.*, tblmenu.menuname, tblmenu.price, user.username, user.address,
        tblorder.dateandtime AS order_datetime,
        (tblmenu.price * tblorder.qt) AS total_price
        FROM tblorder
        JOIN tblmenu ON tblorder.menu_id = tblmenu.menu_id
        JOIN user ON tblorder.id = user.id
        WHERE tblorder.status = 'Pending'
        ORDER BY order_datetime ASC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

$stmt->execute();
$result = $stmt->get_result();

$last_order_date = null;
$order_subtotal = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($last_order_date !== $row['dateandtime']) {
            if ($last_order_date !== null) {
                echo "</tbody></table>
                <p class='subtotal'>Subtotal: ₱" . number_format($order_subtotal, 0) . "</p>
                <form>
                    <input type='hidden' name='order_date' value='{$last_order_date}'>
                    <button type='button' class='btn processing'>Processing</button>
                </form>
                </div>";
                $order_subtotal = 0;
            }

            $last_order_date = $row['dateandtime'];
            echo "<div class='container'>";
            echo "<h2 class='info'>Name: " . htmlspecialchars($row['username']) . "</h2>";
            echo "<h2 class='info'>Status: {$row['status']}</h2>";
            echo "<h2 class='info left-info'>Order Date: {$last_order_date}</h2>";
            echo "<h2 class='info left-info-date'>Address: " . htmlspecialchars($row['address']) . "</h2>";
            echo "<table>
                    <thead>
                        <tr>
                            <th>Order Name</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>";
        }

        $item_total = $row['price'] * $row['qt'];
        $order_subtotal += $item_total;

        echo "<tr>
                <td>" . htmlspecialchars($row['menuname']) . "</td>
                <td>{$row['qt']}</td>
                <td>₱" . number_format($row['total_price'], 0) . "</td>
              </tr>";
    }

    echo "</tbody></table>
    <p class='subtotal'>Subtotal: ₱" . number_format($order_subtotal, 0) . "</p>
    <form>
        <input type='hidden' name='order_date' value='{$last_order_date}'>
        <button type='button' class='btn processing'>Processing</button>
    </form>
    </div>";
} else {
    echo "<img src='img//empty (1).png'  class='no-found-img'>";
}
?>

<script>
window.onload = function() {
    const processingButtons = document.querySelectorAll('.btn.processing');
    processingButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            
            const orderDate = this.previousElementSibling.value; // Hidden input value
            const status = "Processing"; // Fixed status for button action
            
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_status.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            
            const data = `status=${encodeURIComponent(status)}&order_date=${encodeURIComponent(orderDate)}`;
            
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText); // Debug response
                    location.reload(); // Reload the page
                } else if (xhr.readyState === 4) {
                    console.error("Request failed: " + xhr.status); // Debug errors
                }
            };
            
            xhr.send(data);
        });
    });
};
</script>
</body>
</html>