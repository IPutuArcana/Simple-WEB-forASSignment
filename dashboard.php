<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// 1. CREATE
if (isset($_POST['add'])) {
    $name = $_POST['item_name'];
    $stock = $_POST['stock'];
    mysqli_query($conn, "INSERT INTO inventory (item_name, stock) VALUES ('$name', '$stock')");
    header("Location: dashboard.php");
    exit();
}

// 2. DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM inventory WHERE id=$id");
    header("Location: dashboard.php");
    exit();
}

// 3. UPDATE
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['item_name'];
    $stock = $_POST['stock'];
    mysqli_query($conn, "UPDATE inventory SET item_name='$name', stock='$stock' WHERE id=$id");
    header("Location: dashboard.php");
    exit();
}

// Fetch Data for Edit
$edit_row = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM inventory WHERE id=$id");
    $edit_row = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">UAS Project</a>
            <div class="d-flex text-white align-items-center">
                <span class="me-3">User: <strong><?php echo $_SESSION['user']; ?></strong></span>
                <a href="index.php?logout=1" class="btn btn-outline-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header <?php echo $edit_row ? 'bg-warning' : 'bg-primary text-white'; ?>">
                        <h5 class="mb-0"><?php echo $edit_row ? 'Edit Item' : 'Add New Item'; ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $edit_row['id'] ?? ''; ?>">
                            <div class="mb-3">
                                <label class="form-label">Item Name</label>
                                <input type="text" name="item_name" class="form-control" value="<?php echo $edit_row['item_name'] ?? ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stock</label>
                                <input type="number" name="stock" class="form-control" value="<?php echo $edit_row['stock'] ?? ''; ?>" required>
                            </div>
                            <div class="d-grid">
                                <?php if ($edit_row): ?>
                                    <button type="submit" name="update" class="btn btn-warning">Update</button>
                                    <a href="dashboard.php" class="btn btn-secondary mt-2">Cancel</a>
                                <?php else: ?>
                                    <button type="submit" name="add" class="btn btn-primary">Add Item</button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Inventory List</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Item</th>
                                    <th>Stock</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = mysqli_query($conn, "SELECT * FROM inventory");
                                while ($row = mysqli_fetch_assoc($res)) {
                                    echo "<tr>";
                                    echo "<td>{$row['id']}</td>";
                                    echo "<td>" . htmlspecialchars($row['item_name']) . "</td>";
                                    echo "<td>{$row['stock']}</td>";
                                    echo "<td>
                                        <a href='dashboard.php?edit={$row['id']}' class='btn btn-sm btn-info text-white'>Edit</a>
                                        <a href='dashboard.php?delete={$row['id']}' onclick='return confirm(\"Delete?\")' class='btn btn-sm btn-danger'>Del</a>
                                    </td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>