<?php
include("../connection/connect.php");

if (isset($_GET['menu_del'])) {
    $d_id = $_GET['menu_del'];

    // Delete the dish record from the database
    $delete_sql = "DELETE FROM dishes WHERE d_id = '$d_id'";

    if (mysqli_query($db, $delete_sql)) {
        header("Location: all_menu.php?msg=Dish deleted successfully!");
    } else {
        echo "Error: " . mysqli_error($db);
    }
}
?>
