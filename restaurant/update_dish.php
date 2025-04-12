<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Check if the dish ID is provided in the URL for updating
if (isset($_GET['dish_upd'])) {
    $dish_id = $_GET['dish_upd'];

    // Fetch the dish data based on the dish ID
    $sql = "SELECT * FROM dishes WHERE d_id = '$dish_id'";
    $query = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($query);

    if (!$row) {
        die("Dish not found!");
    }

    // Check if the form is submitted
    if (isset($_POST['update'])) {
        // Fetch the updated values from the form
        $d_name = $_POST['d_name'];
        $d_description = $_POST['d_description'];
        $price = $_POST['price'];
        $status = $_POST['status'];

        // Update the dish details in the database
        $update_sql = "UPDATE dishes SET d_name='$d_name', d_discription='$d_description', price='$price', status='$status' WHERE d_id='$dish_id'";

        if (mysqli_query($db, $update_sql)) {
            $success_message = "Dish updated successfully!";
            header("Location: all_menu.php?msg=" . urlencode($success_message));  // Redirect to all menu page
            exit();
        } else {
            echo "Error: " . mysqli_error($db);
        }
    }
} else {
    die("Dish ID is missing.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Dish</title>
    <link href="../admin/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Update Dish Details</h2>
        <form action="update_dish.php?dish_upd=<?php echo $dish_id; ?>" method="POST">
            <div class="form-group">
                <label for="d_name">Dish Name:</label>
                <input type="text" class="form-control" id="d_name" name="d_name" value="<?php echo $row['d_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="d_description">Dish Description:</label>
                <textarea class="form-control" id="d_description" name="d_description" required><?php echo $row['d_discription']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" id="price" name="price" value="<?php echo $row['price']; ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status">
                    <option value="active" <?php echo ($row['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($row['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" name="update" class="btn btn-success">Update Dish</button>
            <a href="all_menu.php" class="btn btn-danger">Cancel</a>
        </form>
    </div>

    <script src="../admin/js/lib/jquery/jquery.min.js"></script>
    <script src="../admin/js/lib/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
