<?php
// Include database connection
include('db_connection.php');  // Adjust the path to your DB connection file

// Check if order_id is passed in the URL
if (isset($_GET['order_id']) && !empty($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Prepare the query to get order details
    $stmt = $db->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order_result = $stmt->get_result();

    // If the order exists, fetch details
    if ($order_result->num_rows > 0) {
        $order = $order_result->fetch_assoc();
        
        // Fetch order items (you may have an 'order_items' table for this)
        $stmt_items = $db->prepare("SELECT oi.*, d.title, d.price FROM order_items oi
                                    JOIN dishes d ON oi.dish_id = d.d_id
                                    WHERE oi.order_id = ?");
        $stmt_items->bind_param("i", $order_id);
        $stmt_items->execute();
        $items_result = $stmt_items->get_result();
    } else {
        echo "<p>Order not found.</p>";
        exit;
    }
} else {
    echo "<p>Invalid Order ID.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS -->
</head>
<body>

    <div class="container">
        <h1>Order Confirmation</h1>

        <!-- Display Order Details -->
        <div class="order-summary">
            <h2>Thank you for your order!</h2>
            <p>Your order ID is: <strong><?php echo $order['order_id']; ?></strong></p>
            <p>Date of Order: <strong><?php echo date("F j, Y, g:i a", strtotime($order['order_date'])); ?></strong></p>
            <p>Shipping Address: <strong><?php echo $order['shipping_address']; ?></strong></p>
        </div>

        <h3>Order Details:</h3>
        <table class="order-items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_amount = 0;
                while ($item = $items_result->fetch_assoc()) {
                    $item_total = $item['quantity'] * $item['price'];
                    $total_amount += $item_total;
                ?>
                <tr>
                    <td><?php echo $item['title']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td>$<?php echo number_format($item_total, 2); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="order-total">
            <p><strong>Total Amount: $<?php echo number_format($total_amount, 2); ?></strong></p>
        </div>

        <!-- Thank You Message -->
        <div class="thank-you-message">
            <p>Your order will be processed shortly. You will receive an email with the shipping details soon.</p>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2025 Your Restaurant Name. All rights reserved.</p>
    </div>

</body>
</html>

<?php
// Close the prepared statements
$stmt->close();
$stmt_items->close();
// Close database connection
$db->close();
?>
