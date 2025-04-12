<?php
include("../connection/connect.php");

if (isset($_GET['menu_copy'])) {
    $d_id = $_GET['menu_copy'];

    // Fetch the original dish record
    $sql = "SELECT * FROM dishes WHERE d_id = '$d_id'";
    $query = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($query);

    // Prepare data for the new dish (copying all data except d_id)
    $d_name = $row['d_name'];
    $d_discription = $row['d_discription'];
    $price = $row['price'];
    $img = $row['img'];
    $rs_id = $row['rs_id'];
    $status = $row['status'];

    // Insert a new record with the same data but a new d_id
    $insert_sql = "INSERT INTO dishes (d_name, d_discription, price, img, rs_id, status) 
                   VALUES ('$d_name', '$d_discription', '$price', '$img', '$rs_id', '$status')";

    if (mysqli_query($db, $insert_sql)) {
        header("Location: all_menu.php?msg=Dish copied successfully!");
    } else {
        echo "Error: " . mysqli_error($db);
    }
}
?>
