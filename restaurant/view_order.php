<?php
// Include the database connection
include("../connection/connect.php");
error_reporting(0);
session_start();

// Check if 'user_upd' GET parameter is set
if (isset($_GET['user_upd'])) {
    $user_upd = $_GET['user_upd'];

    // Prepare the SQL query
    $sql = "SELECT users.*, users_orders.*, 
                   COALESCE((SELECT r.status FROM remark r WHERE r.o_id = users_orders.o_id ORDER BY r.remarkDate DESC LIMIT 1), 'Dispatch') AS status 
            FROM users 
            INNER JOIN users_orders ON users.u_id = users_orders.u_id 
            WHERE users_orders.o_id = ?";

    if ($stmt = mysqli_prepare($db, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_upd);
        mysqli_stmt_execute($stmt);
        $query = mysqli_stmt_get_result($stmt);
        $rows = mysqli_fetch_array($query, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
    } else {
        die("Error in query: " . mysqli_error($db));
    }
} else {
    die("Invalid order ID!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Order</title>
    <link href="../admin/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../admin/css/helper.css" rel="stylesheet">
    <link href="../admin/css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    
    <script type="text/javascript">
        function popUpWindow(URLStr) {
            window.open(URLStr, 'popUpWin', 'width=600,height=600,scrollbars=yes,resizable=no');
        }
    </script>
</head>

<body class="fix-header fix-sidebar">
    <div id="main-wrapper">
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <div class="navbar-header">
                    <a class="navbar-brand" href="dashboard.php">
                        <img src="images/logo.png" alt="homepage" class="dark-logo" />
                    </a>
                </div>
            </nav>
        </div>

        <div class="left-sidebar">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav" class="mt-4">
                        <li><a href="dashboard.php">Add Menu</a></li>
                        <li><a href="all_menu.php">All Menu</a></li>
                        <li><a href="all_orderss.php">All Orders</a></li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">View Order</h3>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">View User Orders</h4>
                                <div class="table-responsive m-t-20">
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            <tr>
                                                <td><strong>Username:</strong></td>
                                                <td><center><?php echo htmlspecialchars($rows['username']); ?></center></td>
                                                <td><center>
                                                    <a href="javascript:void(0);" onClick="popUpWindow('order_update.php?form_id=<?php echo htmlentities($rows['o_id']); ?>');">
                                                        <button type="button" class="btn btn-primary">Take Action</button>
                                                    </a>
                                                </center></td>
                                            </tr>
                                            <tr><td><strong>Title:</strong></td><td><center><?php echo htmlspecialchars($rows['title']); ?></center></td></tr>
                                            <tr><td><strong>Quantity:</strong></td><td><center><?php echo htmlspecialchars($rows['quantity']); ?></center></td></tr>
                                            <tr><td><strong>Price:</strong></td><td><center>$<?php echo number_format($rows['price'], 2); ?></center></td></tr>
                                            <tr><td><strong>Address:</strong></td><td><center><?php echo htmlspecialchars($rows['address']); ?></center></td></tr>
                                            <tr><td><strong>Order Date:</strong></td><td><center><?php echo htmlspecialchars($rows['date']); ?></center></td></tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td>
                                                    <center>
                                                        <?php
                                                        $status = htmlspecialchars($rows['status']);
                                                        switch ($status) {
                                                            case "in process":
                                                                echo '<button class="btn btn-warning"><span class="fa fa-cog fa-spin"></span> On the way!</button>';
                                                                break;
                                                            case "closed":
                                                                echo '<button class="btn btn-success"><span class="fa fa-check-circle"></span> Delivered</button>';
                                                                break;
                                                            case "rejected":
                                                                echo '<button class="btn btn-danger"><i class="fa fa-close"></i> Cancelled</button>';
                                                                break;
                                                            default:
                                                                echo '<button class="btn btn-info"><span class="fa fa-bars"></span> Dispatch</button>';
                                                        }
                                                        ?>
                                                    </center>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer">© 2024 All rights reserved.</footer>
        </div>
    </div>
    
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/custom.min.js"></script>
</body>
</html>
