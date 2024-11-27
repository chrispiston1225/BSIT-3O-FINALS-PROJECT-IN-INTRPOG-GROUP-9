<?php 
$title = 'Menu';
include './view/header.php';

// Determine search term
$searchTerm = isset($_GET['order_no']) ? "%" . $conn->real_escape_string($_GET['order_no']) . "%" : '%';

$sql = "SELECT menu_id, image, menuname, price 
        FROM tblmenu
        WHERE menuname LIKE ?";

// Prepare statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing SQL statement: " . $conn->error);
}

$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Search Form -->
<form method="get" action="" class="search-form">
    <input type="text" name="order_no" placeholder="Search..." value="<?php echo isset($_GET['order_no']) ? htmlspecialchars($_GET['order_no']) : ''; ?>">
    <button type="submit"><i class="fas fa-search"></i></button>
</form>

<div id="cartMessage" style="display:none;">Add to cart succesfully!</div>
<div class="menu">

<div class="product-container">
<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="product-card">
            <img src="<?php echo htmlspecialchars($row["image"]); ?>" alt="<?php echo htmlspecialchars($row["menuname"]); ?>">
            <div class="product-name"><?php echo htmlspecialchars($row["menuname"]); ?></div>
            <div class="product-price">₱<?php echo number_format($row["price"], 2); ?></div>
            <button class="add-to-cart" onclick="addToCart(<?php echo $row['menu_id']; ?>)"><i class="fa fa-cart-plus" aria-hidden="true"></i> Add to Cart</button>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p class="no">No products found.</p>
<?php endif; ?>
</div>

<script>
function addToCart(productId) {
    // Get the user ID securely
    var userId = <?php echo json_encode($_SESSION['id']); ?>;  

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "add_to_cart.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Display success message
            var cartMessage = document.getElementById("cartMessage");
            cartMessage.style.display = "block";

            // Hide the message after 3 seconds
            setTimeout(function() {
                cartMessage.style.display = "none";
            }, 3000);
        }
    };
    xhr.send("product_id=" + productId + "&user_id=" + userId);
}
</script>

<script src="js/scroll.js"></script>
</body>
</html>
