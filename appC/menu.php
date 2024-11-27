<?php
$title = 'Menu';
include './view/admin_header.php';

$menuItems = [];
if ($conn) { // Ensure $conn is valid
    $query = "SELECT * FROM tblmenu";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $menuItems = $result->fetch_all(MYSQLI_ASSOC);
    }
}

// Add new menu item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['menuname'])) {
    $menuname = htmlspecialchars(trim($_POST['menuname']));
    $price = (float)$_POST['price'];
    $image = null;

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = uniqid() . '-' . basename($_FILES['image']['name']);
        $image = 'uploads/' . $imageName;

        if (mime_content_type($imageTmp) === 'image/jpeg' || mime_content_type($imageTmp) === 'image/png') {
            move_uploaded_file($imageTmp, $image);
        } else {
            echo "Error: Invalid image file type.";
        }
    }

    $stmt = $conn->prepare("INSERT INTO tblmenu (menuname, price, image) VALUES (?, ?, ?)");
    $stmt->bind_param('sds', $menuname, $price, $image);
    if ($stmt->execute()) {
        header("Location: menu.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Update menu item
if (isset($_POST['update_menu'])) {
    $menu_id = (int)$_POST['menu_id'];
    $menuname = htmlspecialchars(trim($_POST['edit_menuname']));
    $price = (float)$_POST['edit_price'];
    $image = null;

    if (!empty($_FILES['edit_image']['name'])) {
        $imageTmp = $_FILES['edit_image']['tmp_name'];
        $imageName = uniqid() . '-' . basename($_FILES['edit_image']['name']);
        $image = 'uploads/' . $imageName;

        if (mime_content_type($imageTmp) === 'image/jpeg' || mime_content_type($imageTmp) === 'image/png') {
            move_uploaded_file($imageTmp, $image);
            $stmt = $conn->prepare("UPDATE tblmenu SET menuname = ?, price = ?, image = ? WHERE menu_id = ?");
            $stmt->bind_param('sdsi', $menuname, $price, $image, $menu_id);
        } else {
            echo "Error: Invalid image file type.";
        }
    } else {
        $stmt = $conn->prepare("UPDATE tblmenu SET menuname = ?, price = ? WHERE menu_id = ?");
        $stmt->bind_param('sdi', $menuname, $price, $menu_id);
    }

    if ($stmt->execute()) {
        header("Location: menu.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<main>
    <section class="menu">
        <h2>Our Menu</h2>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Item</th>
                    <th>Price (₱)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($menuItems as $item): ?>
                    <tr>
                        <td>
                            <?php if ($item['image']): ?>
                                <img class="menu-img" src="<?php echo htmlspecialchars($item['image']); ?>" alt="Menu Image">
                            <?php else: ?>
                                <span>No Image</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($item['menuname']); ?></td>
                        <td>₱<?php echo number_format($item['price'], 2); ?></td>
                        <td class="btn-action">
                            <button class="edit-btn" 
                                    data-id="<?php echo $item['menu_id']; ?>" 
                                    data-name="<?php echo htmlspecialchars($item['menuname']); ?>" 
                                    data-price="<?php echo $item['price']; ?>" 
                                    data-image="<?php echo htmlspecialchars($item['image']); ?>">
                                Edit
                            </button>
                            <!-- Fix Delete link -->
                            <a href="delete_menu.php?id=<?php echo $item['menu_id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
    <button id="addMenuBtn">Add Menu</button>
</main>

<!-- Add Menu Modal -->
<div id="menuModal" class="modal">
    <div class="modal-content">
        <h2>Add Menu</h2>
        <span class="close">&times;</span>
        <form method="POST" enctype="multipart/form-data">
            <label for="menuname">Menu Name:</label>
            <input type="text" name="menuname" id="menuname" required>

            <label for="price">Price (₱):</label>
            <input type="number" step="0.01" name="price" required>

            <label for="image">Image:</label>
            <input type="file" name="image" id="image">

            <button type="submit">Add Menu Item</button>
        </form>
    </div>
</div>

<!-- Edit Menu Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <h2>Edit Menu</h2>
        <span class="close">&times;</span>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="menu_id" id="edit-menu-id">

            <label for="edit-menuname">Menu Name:</label>
            <input type="text" name="edit_menuname" id="edit-menuname" required>

            <label for="edit-price">Price (₱):</label>
            <input type="number" step="0.01" name="edit_price" id="edit-price" required>

            <label for="edit-image">Image:</label>
            <input type="file" name="edit_image" id="edit-image">

            <button type="submit" name="update_menu">Update Menu</button>
        </form>
    </div>
</div>
<script src="js/script.js"></script>
<script src="js/edit.js"></script>
