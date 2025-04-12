<?php
include("../connection/connect.php");
session_start();
error_reporting(E_ALL); // Show all errors for debugging

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and sanitize
    $res_name = trim(mysqli_real_escape_string($db, $_POST['res_name']));
    $res_name = preg_replace('/\s+/', ' ', $res_name); // Replace multiple spaces with a single space

    $email = mysqli_real_escape_string($db, $_POST['email']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $url = mysqli_real_escape_string($db, $_POST['url']);
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $status = 'inactive';  // Default status

    // Password handling
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password for security

    // Validate phone number (exactly 10 digits)
    if (!preg_match('/^\d{10}$/', $phone)) {
        echo '<div class="alert alert-danger">Phone number should be exactly 10 digits long and contain only digits!</div>';
        return;
    }

    // Check for duplicate entries
    $check_query = "SELECT * FROM restaurant WHERE res_name = '$res_name' OR email = '$email' OR phone = '$phone' OR url = '$url'";
    $check_result = mysqli_query($db, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo '<div class="alert alert-danger">Error: The Restaurant Name, Email, Phone, or Website URL already exists. Please enter unique values.</div>';
    } else {
        // Handle file upload
        $image = $_FILES['file']['name'];
        $target_dir = "../admin/Res_img/";  // Directory to store uploaded images
        $target_file = $target_dir . basename($image);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allowed file extensions for image upload
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($imageFileType, $allowed_extensions)) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
                // Insert restaurant data into the database
                $query = "INSERT INTO restaurant (res_name, email, phone, url, address, image, password, status, date) 
                          VALUES ('$res_name', '$email', '$phone', '$url', '$address', '$image', '$hashed_password', '$status', NOW())";
                if (mysqli_query($db, $query)) {
                    echo '<div class="alert alert-success">Restaurant registered successfully!</div>';
                    header("Location: login.php"); // Redirect after success
                    exit();
                } else {
                    echo '<div class="alert alert-danger">Error: ' . mysqli_error($db) . '</div>';
                }
            } else {
                echo '<div class="alert alert-danger">Sorry, there was an error uploading your image.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Only JPG, JPEG, PNG & GIF files are allowed for the image.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restaurant Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/animsition.min.css" rel="stylesheet">
    <link href="../css/animate.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet"> -->
  <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">

    <style>
        .ff {
            max-width:1300px;
        }
        .bb{
            background: #e9e9e9;
        }
    </style>
</head>
<body class="bb">
    <div class="pen-title">
        <h1>Restaurant Regiseration Form</h1>
    </div>

    <div class="ff module form-module">
        <div class="toggle"></div>
        <div class="form">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <!-- Left Column -->
                    <div class="mb-3">
                        <label class="form-label">Restaurant Name *</label>
                        <input type="text" name="res_name" class="form-control" placeholder="Restaurant Name" required 
                               value="<?php echo isset($_POST['res_name']) ? $_POST['res_name'] : ''; ?>"
                               pattern="^\S+(?:\s\S+)*$" 
                               oninput="this.value = this.value.replace(/\s{2,}/g, ' ');">
                    </div>
                    <div class="col-md-6">

                        <div class="mb-3">
                            <label class="form-label">Phone *</label>
                            <input type="text" name="phone" class="form-control" placeholder="Phone Number" required 
                                   value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>"
                                   pattern="^\d{10}$" minlength="10" maxlength="10">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload Image *</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>

                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                    <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" placeholder="Email" required 
                                   value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Website URL</label>
                            <input type="text" name="url" class="form-control" placeholder="Website URL"  value="<?php echo isset($_POST['url']) ? $_POST['url'] : ''; ?>">
                        </div>
                        

                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address *</label>
                        <textarea name="address" class="form-control" rows="4" placeholder="Restaurant Address" required><?php echo isset($_POST['address']) ? $_POST['address'] : ''; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-control" placeholder="Password" required minlength="6" maxlength="20">
                    </div>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn" style="background-color:#f30; color:#fff;">Register</button>
                </div>
            </form>

        </div>
            <div class="cta">
                Already have an account?
                <a href="login.php" style="color:#f30;"> Login</a>
            </div>
    </div>

<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>


</body>
</html>
