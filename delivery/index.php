<?php
// Include the database connection file
include("../connection/connect.php");
session_start();

// Enable error reporting for development (optional, remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $full_name = $db->real_escape_string($_POST['full_name']);
    $phone_no = $db->real_escape_string($_POST['phone_no']);
    $email = $db->real_escape_string($_POST['email']);
    $address = $db->real_escape_string($_POST['address']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt the password

    // Set status to 'active' by default
    $status = 'active';

    // Get the current date and time for the 'date_created' field
    $date_created = date('Y-m-d H:i:s');

    // Prepared statement to prevent SQL injection
    $sql = "INSERT INTO delivery_boys (full_name, phone_no, email, address, password, status, date_created)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $db->prepare($sql)) {
        // Bind parameters (s = string, i = integer, d = double, b = blob)
        $stmt->bind_param("sssssss", $full_name, $phone_no, $email, $address, $password, $status, $date_created);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to login page after successful registration
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }
}

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delivery Boy Registration</title>
  <link rel="stylesheet" href="../css/login.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
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
        <h1>Delivery Boy Registration</h1>
    </div>

  <div class="ff module form-module">
    <div class="toggle"></div>
      <div class="form">
          <form action="" method="POST" id="registrationForm">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label>Full Name *</label>
                              <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <label>Phone Number *</label>
                              <input type="text" name="phone_no" class="form-control" placeholder="Phone Number" required minlength="10" maxlength="10" id="phone_no">
                          </div>
                      </div>
                  </div>

                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group">
                              <label>Email Address *</label>
                              <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                          </div>
                      </div>
                  </div>

                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group">
                              <label>Address *</label>
                              <textarea name="address" class="form-control" rows="4" placeholder="Delivery Boy Address" required></textarea>
                          </div>
                      </div>
                  </div>

                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group">
                              <label>Password *</label>
                              <input type="password" name="password" class="form-control" placeholder="Password" required>
                          </div>
                      </div>
                  </div>

                  <div class="form-actions text-center">
                      <button type="submit" class="btn btn-primary">Register</button>
                  </div>
              </div>
              <div class="cta">
                Already have an account?
                <a href="login.php" style="color:#f30;"> Login</a>
            </div>
          </form>

  </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>


<script>
    // Custom validation for phone number
    document.querySelector("#registrationForm").addEventListener("submit", function(e) {
        var phone = document.querySelector("[name='phone_no']").value;
        
        // Strip spaces from the phone number
        phone = phone.replace(/\s+/g, '');
        document.querySelector("[name='phone_no']").value = phone;

        // Validate phone number: Should be 10 digits, only numbers, and shouldn't start with 0
        if (!/^\d{10}$/.test(phone)) {
            alert("Please enter a valid 10-digit phone number without spaces or special characters.");
            e.preventDefault(); // Prevent form submission
            return false;
        }

        if (phone[0] === '0') {
            alert("Phone number cannot start with 0.");
            e.preventDefault(); 
            return false;
        }
    });
</script>

</body>
</html>
