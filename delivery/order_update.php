<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if (isset($_POST['update'])) {
    // Securely fetch the form_id from the URL
    $form_id = mysqli_real_escape_string($db, $_GET['form_id']);
    $status = mysqli_real_escape_string($db, $_POST['status']);  // Secure the status input
    $remark = mysqli_real_escape_string($db, $_POST['remark']);  // Secure the remark input
    $remarkDate = date('Y-m-d H:i:s'); // Get the current timestamp

    // Insert the status, remark, and timestamp into the 'remark' table
    $query = mysqli_query($db, "INSERT INTO remark (o_id, status, remark, remarkDate) 
                                VALUES ('$form_id', '$status', '$remark', '$remarkDate')");
    
    // Update the status of the form in the 'users_orders' table
    $sql = mysqli_query($db, "UPDATE users_orders SET status='$status' WHERE o_id='$form_id'");

    if ($query || $sql) {
        echo "<script>alert('Form details updated successfully'); window.close();</script>";
    } else {
        echo "<script>alert('Error updating form details');</script>";
    }
}
?>

<script language="javascript" type="text/javascript">
    function f2() {
        window.close();  // Close the current window
    }
</script>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Admin Dashboard">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="#">
    <title>Admin Dashboard Template</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style type="text/css">
        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0f0f0;
            background: linear-gradient(135deg, #e5e5e5 0%, #ffffff 100%);
            font-family: "Open Sans", Arial, sans-serif;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label.control-label {
            font-weight: bold;
            color: #777;
        }

        table { 
            width: 650px; 
            border-collapse: collapse; 
            margin-top: 50px;
        }

        tr:nth-of-type(odd) { 
            background: #f9f9f9; 
        }

        th { 
            background: #004684; 
            color: white; 
            font-weight: bold; 
        }

        td, th { 
            padding: 10px; 
            border: 1px solid #ccc; 
            text-align: left; 
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <form name="updateticket" id="updatecomplaint" method="post">
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td><b>Form Number</b></td>
                    <td><?php echo htmlentities($_GET['form_id']); ?></td>
                </tr>
                <tr>
                    <td><b>Status</b></td>
                    <td>
                        <select name="status" required="required">
                            <option value="">Select Status</option>
                            <option value="in process">In Process</option>
                            <option value="closed">Closed</option>
                            <!-- <option value="rejected">Rejected</option> -->
                        </select>
                    </td>
                </tr>
                <tr>
                  <td><b>Remark</b></td>
                  <td><textarea name="remark" cols="50" rows="10"></textarea></td>
                </tr>
                <tr>
                    <td><b>Action</b></td>
                    <td>
                        <input type="submit" name="update" class="btn btn-primary" value="Submit">
                        <input name="Submit2" type="button" class="btn btn-danger" value="Close this window" onClick="f2();" style="cursor: pointer;" />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>
