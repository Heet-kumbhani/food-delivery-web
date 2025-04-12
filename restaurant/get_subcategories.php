<?php
include("../connection/connect.php");

if (isset($_POST['category_id'])) {
    $category_id = $_POST['category_id'];

    // Query to get subcategories based on the selected category
    $sql = "SELECT * FROM subcategories WHERE c_id = '$category_id' AND status = 'active'";  // Adjust as per your schema
    $result = mysqli_query($db, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Generate options for subcategories
        echo '<option value="">--Select Subcategory--</option>';
        while ($row = mysqli_fetch_array($result)) {
            echo '<option value="' . $row['subcat_id'] . '">' . $row['subcat_name'] . '</option>';
        }
    } else {
        echo '<option value="">No subcategories available</option>';
    }
}
?>
