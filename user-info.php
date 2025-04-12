<?php
session_start();
include("connection/connect.php");

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch user details
$stmt = $db->prepare("SELECT username, f_name, l_name, email, phone, address FROM users WHERE u_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle form submission for updating user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $f_name = htmlspecialchars($_POST['f_name']);
    $l_name = htmlspecialchars($_POST['l_name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);
    
    // Update user details
    $update_stmt = $db->prepare("UPDATE users SET username = ?, f_name = ?, l_name = ?, email = ?, phone = ?, address = ? WHERE u_id = ?");
    $update_stmt->bind_param("ssssssi", $username, $f_name, $l_name, $email, $phone, $address, $user_id);
    
    if ($update_stmt->execute()) {
        $success_message = "Profile updated successfully!";
    } else {
        $error_message = "Error updating profile. Please try again.";
    }
    
    $update_stmt->close();
    
    // Refresh user data after update
    header("Refresh:0");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/login.css">
    <style>
        /* Reset some default styling */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body */
body {
    font-family: 'Roboto', sans-serif;
    background-color: #f8f9fa;
    padding: 0;
    margin: 0;
}

/* Center the profile card on the screen */
.pen-title {
    text-align: center;
    margin-top: 30px;
}

.pen-title h2 {
    color: #ff3300;
    font-size: 30px;
    font-weight: 700;
    margin-bottom: 20px;
}

/* Card Container */
.module {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* The profile form container */
.card {
    background-color: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    width: 100%;
}

/* Form label */
.form-label {
    font-size: 16px;
    font-weight: 500;
    color: #333;
}

/* Form input fields */
.form-control {
    width: 100%;
    padding: 12px;
    margin: 10px 0 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    background-color: #f9f9f9;
}

.form-control:focus {
    outline: none;
    border-color: #ff3300;
    background-color: #fff;
}

/* Submit Button */
.btn-primary {
    width: 100%;
    /* padding: 14px; */
    background-color: #ff3300;
    color: white;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #e62e00;
}

/* Success/Error message */
.alert {
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
    font-size: 16px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Address Textarea */
textarea.form-control {
    min-height: 100px;
    max-height: 150px;
    resize: vertical;
}

/* Footer or Call to Action section */
.cta {
    text-align: center;
    margin-top: 20px;
}

.cta a {
    color: #ff3300;
    text-decoration: none;
    font-weight: 600;
}

.cta a:hover {
    color: #e62e00;
}

    </style>
</head>
<body>
    <div class="pen-title">
        <h2>User Profile</h2>
  </div>
    <div class="module form-module">
        <div class="card shadow p-4">
            <?php if (isset($success_message)) echo "<div class='alert alert-success'>$success_message</div>"; ?>
            <?php if (isset($error_message)) echo "<div class='alert alert-danger'>$error_message</div>"; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?php echo $user['username']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" name="f_name" class="form-control" value="<?php echo $user['f_name']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="l_name" class="form-control" value="<?php echo $user['l_name']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo $user['phone']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" required><?php echo $user['address']; ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>
</body>
</html>
