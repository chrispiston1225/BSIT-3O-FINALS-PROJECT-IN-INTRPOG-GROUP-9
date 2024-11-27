<?php
$title = 'Menu';
include './view/admin_header.php';
 

// Prepare queries to get total data
$sql_users = "SELECT COUNT(*) AS total_users FROM user";
$sql_menu = "SELECT COUNT(*) AS total_menu FROM tblmenu";
$sql_sales = "SELECT SUM(price) AS total_sales FROM tblmenu inner join tblorder on tblmenu.menu_id = tblorder.menu_id WHERE tblorder.status = 'Complete'";
$sql_orders = "SELECT COUNT(*) AS total_orders FROM tblorder WHERE status = 'Complete'";

// For weekly and monthly performance
$sql_sales_weekly = "SELECT SUM(price) AS total_sales FROM tblmenu inner join tblorder on tblmenu.menu_id = tblorder.menu_id WHERE status = 'Complete' AND dateandtime >= CURDATE() - INTERVAL 7 DAY";
$sql_sales_monthly = "SELECT SUM(price) AS total_sales FROM tblmenu inner join tblorder on tblmenu.menu_id = tblorder.menu_id WHERE status = 'Complete' AND dateandtime >= CURDATE() - INTERVAL 30 DAY";

// Execute the queries
$total_users_result = $conn->query($sql_users);
$total_menu_result = $conn->query($sql_menu);
$total_sales_result = $conn->query($sql_sales);
$total_orders_result = $conn->query($sql_orders);
$weekly_sales_result = $conn->query($sql_sales_weekly);
$monthly_sales_result = $conn->query($sql_sales_monthly);

// Fetch the results
$total_users = $total_users_result->fetch_assoc()['total_users'];
$total_menu = $total_menu_result->fetch_assoc()['total_menu'];
$total_sales = $total_sales_result->fetch_assoc()['total_sales'];
$total_orders = $total_orders_result->fetch_assoc()['total_orders'];
$weekly_sales = $weekly_sales_result->fetch_assoc()['weekly_sales'];
$monthly_sales = $monthly_sales_result->fetch_assoc()['monthly_sales'];

// Close the connection
$conn->close();
?>

<div class="dashboard-container">
    <!-- Main Content -->
    <div class="main-content">
        <div class="stats">
            <div class="stat-box">
                <h3>Total Users</h3>
                <p><?php echo $total_users; ?></p>
            </div>
            <div class="stat-box">
                <h3>Total Menu Items</h3>
                <p><?php echo $total_menu; ?></p>
            </div>
            <div class="stat-box">
                <h3>Total Sales</h3>
                <p>₱<?php echo number_format($total_sales, 2); ?></p>
            </div>
            <div class="stat-box">
                <h3>Total Orders</h3>
                <p><?php echo $total_orders; ?></p>
            </div>
        </div>

        <div class="performance">
            <div class="performance-box">
                <h3>Weekly Sales</h3>
                <p>₱<?php echo number_format($weekly_sales, 2); ?></p>
            </div>
            <div class="performance-box">
                <h3>Monthly Sales</h3>
                <p>₱<?php echo number_format($monthly_sales, 2); ?></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
