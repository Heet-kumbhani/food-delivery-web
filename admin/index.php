<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Login functionality
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($_POST["submit"])) {
        // Query to check if the admin exists in the database
        $loginquery = "SELECT * FROM admin WHERE email='$email'";
        $result = mysqli_query($db, $loginquery);
        $row = mysqli_fetch_array($result);

        if (is_array($row)) {
            // Use password_verify to check if the provided password matches the stored hashed password
            if (password_verify($password, $row['password'])) {
                $_SESSION["adm_id"] = $row['adm_id'];

                // Set a cookie for the admin id (adm_id), 24 hours expiration
                setcookie("adm_id", $row['adm_id'], time() + 226400, "/");  // 86400 seconds = 24 hours

                $adm_id = $row['adm_id'];
                $last_login = $row['last_login'];

                // Get the current time and calculate the time difference
                $current_time = new DateTime();
                $last_login_time = new DateTime($last_login);
                $interval = $current_time->diff($last_login_time);

                // Check if the last login is more than a week ago
                if ($interval->days > 7) {
                    // If more than 7 days ago, set status to 'inactive'
                    $updateLoginQuery = "UPDATE admin SET last_login = NOW(), status = 'inactive' WHERE adm_id = '$adm_id'";
                } else {
                    // If within the last 7 days, set status to 'active'
                    $updateLoginQuery = "UPDATE admin SET last_login = NOW(), status = 'active' WHERE adm_id = '$adm_id'";
                }

                mysqli_query($db, $updateLoginQuery);

                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $message = "Invalid Email or Password!";
            }
        } else {
            $message = "Invalid Email or Password!";
        }
    }
}

// Registration functionality for a new admin
if (isset($_POST['submit1'])) {
    if (empty($_POST['cr_user']) || empty($_POST['cr_email']) || empty($_POST['cr_pass']) || empty($_POST['cr_cpass']) || empty($_POST['cr_phone_no'])) {
        $message = "All fields must be filled";
    } else {
        // Check if the username, email, or phone already exists in the database
        $check_username = mysqli_query($db, "SELECT full_name FROM admin WHERE full_name = '" . $_POST['cr_user'] . "'");
        $check_email = mysqli_query($db, "SELECT email FROM admin WHERE email = '" . $_POST['cr_email'] . "'");
        $check_phone = mysqli_query($db, "SELECT phone_no FROM admin WHERE phone_no = '" . $_POST['cr_phone_no'] . "'");

        if ($_POST['cr_pass'] != $_POST['cr_cpass']) {
            $message = "Passwords do not match!";
        } elseif (!filter_var($_POST['cr_email'], FILTER_VALIDATE_EMAIL)) {
            $message = "Invalid email address, please type a valid email!";
        } elseif (mysqli_num_rows($check_username) > 0) {
            $message = 'Username already exists!';
        } elseif (mysqli_num_rows($check_email) > 0) {
            $message = 'Email already exists!';
        } elseif (mysqli_num_rows($check_phone) > 0) {
            $message = 'Phone number already exists!';
        } elseif (substr($_POST['cr_phone_no'], 0, 1) === '0') {
            $message = 'Phone number cannot start with 0!';
        } elseif (preg_match('/\D/', $_POST['cr_phone_no'])) {
            $message = 'Phone number should only contain digits!';
        } elseif (strlen($_POST['cr_phone_no']) != 10) {
            $message = 'Phone number must be 10 digits long!';
        } elseif (preg_match('/^00+/', $_POST['cr_phone_no'])) {  // Prevent multiple leading zeros
            $message = 'Phone number cannot start with multiple zeros!';
        } else {
            // Hash the password using password_hash() function for better security
            $hashed_password = password_hash($_POST['cr_pass'], PASSWORD_BCRYPT);

            // Insert the new admin into the database
            $mql = "INSERT INTO admin (full_name, password, email, phone_no, status) VALUES ('" . $_POST['cr_user'] . "', '" . $hashed_password . "', '" . $_POST['cr_email'] . "', '" . $_POST['cr_phone_no'] . "', 'active')";
            mysqli_query($db, $mql);
            $success = "Admin added successfully!";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900'>
    <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Montserrat:400,700'>
    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="container">
    <div class="info">
        <h1>Administration</h1>
    </div>
</div>

<div class="form">
    <div class="thumbnail"><img src="images/manager.png"/></div>

    <!-- Registration form (for new admin creation) -->
    <form class="register-form" action="index.php" method="post" onsubmit="return validateRegistrationForm()">
        <input type="text" placeholder="Full name" name="cr_user" id="cr_user" required />
        <input type="email" placeholder="Email address" name="cr_email" id="cr_email" required />
        <input type="password" placeholder="Password" name="cr_pass" id="cr_pass" required minlength="6" />
        <input type="password" placeholder="Confirm password" name="cr_cpass" id="cr_cpass" required />
        <input type="text" placeholder="Phone Number" name="cr_phone_no" id="cr_phone_no" required maxlength="10" oninput="validatePhoneNumber()" />
        <input type="submit" name="submit1" value="Create" />
        <p class="message">Already have an account? <a href="#">Login</a></p>
    </form>

    <!-- Login form -->
    <form class="login-form" action="index.php" method="post" onsubmit="return validateForm()">
        <input type="email" placeholder="Email address" name="email" id="email" required />
        <input type="password" placeholder="Password" name="password" id="password" required minlength="6" />
        <input type="submit" name="submit" value="Login" />
        <p class="message">Don't have an account? <a href="#">Sign In</a></p>
        <p class="message"><a href="forgot_password.php">Forgot Password?</a></p> <!-- Forgot Password link -->
    </form>

    <span style="color:red;"><?php echo $message; ?></span>
    <span style="color:green;"><?php echo $success; ?></span>
</div>

<script>
    function validateRegistrationForm() {
        var fullName = document.getElementById("cr_user").value;
        var email = document.getElementById("cr_email").value;
        var password = document.getElementById("cr_pass").value;
        var confirmPassword = document.getElementById("cr_cpass").value;
        var phone = document.getElementById("cr_phone_no").value;

        if (fullName.trim() === "") {
            alert("Full name is required.");
            return false;
        }

        var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!email.match(emailPattern)) {
            alert("Please enter a valid email address.");
            return false;
        }

        if (password.length < 6) {
            alert("Password must be at least 6 characters long.");
            return false;
        }

        if (password !== confirmPassword) {
            alert("Passwords do not match.");
            return false;
        }

        var phonePattern = /^\d{10}$/;
        if (!phone.match(phonePattern)) {
            alert("Please enter a valid 10-digit phone number.");
            return false;
        }

        if (phone.startsWith('0')) {
            alert("Phone number cannot start with 0.");
            return false;
        }

        return true;
    }

    function validatePhoneNumber() {
        var phoneNumber = document.getElementById('cr_phone_no').value;
        if (/\D/.test(phoneNumber)) {
            document.getElementById('cr_phone_no').value = phoneNumber.replace(/\D/g, '');
        }
    }

    function validateForm() {
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;

        var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!email.match(emailPattern)) {
            alert("Please enter a valid email address.");
            return false;
        }
        if (password.length < 6) {
            alert("Password must be at least 6 characters long.");
            return false;
        }

        return true;
    }
</script>

<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='js/index.js'></script>

</body>
</html>
