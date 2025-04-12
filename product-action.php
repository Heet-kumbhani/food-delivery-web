<?php
session_start();

// Include your database connection
include("connection/connect.php");

if (!isset($_SESSION["user_id"]) || empty($_SESSION["user_id"])) {
    header("Location: login.php?error=" . urlencode("Please log in to place an order."));
    exit;
}

if (!empty($_GET["action"])) {
    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    switch ($_GET["action"]) {
        case "check":
            if (!empty($_SESSION["cart_item"])) {
                foreach ($_SESSION["cart_item"] as $item) {
                    // Fetch the restaurant ID based on the dish ID
                    $res_query = "SELECT rs_id FROM dishes WHERE d_id = ?";
                    if ($stmtRes = $db->prepare($res_query)) {
                        $stmtRes->bind_param('i', $item["d_id"]);
                        $stmtRes->execute();
                        $stmtRes->bind_result($res_id);
                        $stmtRes->fetch();
                        $stmtRes->close();
                    }

                    // Insert the order with restaurant ID
                    $checkSQL = "SELECT quantity FROM users_orders WHERE u_id = ? AND d_id = ? AND order_status = 'pending'";
$stmtCheck = $db->prepare($checkSQL);
$stmtCheck->bind_param('ii', $_SESSION["user_id"], $item["d_id"]);
$stmtCheck->execute();
$stmtCheck->bind_result($existingQty);
$stmtCheck->fetch();
$stmtCheck->close();

if ($existingQty > 0) {
    // If dish already ordered, update quantity
    $updateSQL = "UPDATE users_orders SET quantity = quantity + ?, price = price + ? WHERE u_id = ? AND d_id = ? AND order_status = 'pending'";
    $stmtUpdate = $db->prepare($updateSQL);
    $newPrice = $item["price"] * $item["quantity"];
    $stmtUpdate->bind_param('diii', $item["quantity"], $newPrice, $_SESSION["user_id"], $item["d_id"]);
    $stmtUpdate->execute();
    $stmtUpdate->close();
} else {
    // Insert as a new order
    $insertSQL = "INSERT INTO users_orders (u_id, title, quantity, price, d_id, rs_id, date, order_status) VALUES (?, ?, ?, ?, ?, ?, NOW(), 'pending')";
    $stmtInsert = $db->prepare($insertSQL);
    $stmtInsert->bind_param('isdisi', $_SESSION["user_id"], $item["title"], $item["quantity"], $item["price"], $item["d_id"], $res_id);
    $stmtInsert->execute();
    $stmtInsert->close();
}

                }

                $_SESSION["order_placed"] = true;
                $_SESSION["cart_item"] = array();

                $success = "Thank you! Your order has been placed successfully!";
                header("Location:index.php?success=" . urlencode($success));
                exit;
            } else {
                $success = "Your cart is empty. Please add items to your cart.";
                header("Location:index.php?success=" . urlencode($success));
                exit;
            }
            break;

        case "remove":
            if (!empty($_SESSION["cart_item"])) {
                foreach ($_SESSION["cart_item"] as $k => $v) {
                    if ($productId == $v['d_id']) {
                        unset($_SESSION["cart_item"][$k]);
                        break;
                    }
                }
            }
            break;

        case "empty":
            unset($_SESSION["cart_item"]);
            break;

        case "check":
            header("location:checkout.php");
            break;
    }
}
?>

<!-- HTML to show success message -->
<div class="container">
    <?php if (isset($_GET['success'])): ?>
        <span style="color: green;">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </span>
    <?php endif; ?>
</div>
