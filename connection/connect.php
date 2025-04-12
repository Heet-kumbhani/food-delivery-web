<!-- <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tometo_db";

$db = mysqli_connect($servername, $username, $password, $dbname);

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
?> -->

<?php
$servername = "localhost";  // or your database server
$username = "root";         // your database username
$password = "";             // your database password
$dbname = "tometo_db";  

// Create connection
$db = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);  // Ensure it stops execution if the connection fails
}

return $db;  // Return the connection object
?>
