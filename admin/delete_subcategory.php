<?php
include("../connection/connect.php"); // Include the database connection

if (isset($_GET['subcat_del'])) {
    $subcat_id = mysqli_real_escape_string($db, $_GET['subcat_del']);
    
    // Query to delete the subcategory
    $sql = "DELETE FROM subcategories WHERE subcat_id = '$subcat_id'";

    if (mysqli_query($db, $sql)) {
        // Redirect to the page that lists subcategories after successful deletion
        header("Location: subcategories.php?message=deleted");
        exit();
    } else {
        // Show an error message if something goes wrong
        echo "Error deleting subcategory: " . mysqli_error($db);
    }
}
?>