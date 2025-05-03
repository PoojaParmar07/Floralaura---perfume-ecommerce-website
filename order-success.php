<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="success-container" style="background-color: #E57373;height:400px;position:relative;">
    </div>
    <div class="box " style="background-color:#FFFFFF;position:absolute;top:35%;left:33%">
    <div class="bg-light p-5 rounded shadow text-center" style="width: 450px;border-radius:10px;">
        <h1 class="display-3" style="color: #E57373;"><img src="images/tick.png" alt="" height="50px" width="70px"></h1>
        <h2 class="text-dark mb-3">Order Placed Successfully!</h2>
        <p class="text-muted mb-4">Thank you for your order. Your food is on its way!</p>

        <div class="d-flex">
            <a href="index.php" class="btn m-3 p-2" style="background-color: #E57373;color:white;width:180px;">Home</a>
            <a href="my-orders.php" class="btn btn-outline-secondary m-3 p-2" style="width:180px;">View My Orders</a>
        </div>

    </div>
    </div>
    <?php
include 'database/db.php';  // Make sure to include your DB connection

session_start();

$user_id = $_SESSION['user_id'];  // Get the logged-in user's ID
$total_amount = $_POST['total_amount'];  // The total amount passed from the form

// Check if user is logged in
if (!isset($user_id)) {
    exit;
}

// Start a transaction to ensure data consistency
$conn->begin_transaction();

try {
    // Insert the order into the order table
    $order_sql = "INSERT INTO orders (user_id, total_amount, order_date) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($order_sql);
    $stmt->bind_param("id", $user_id, $total_amount);
    $stmt->execute();

    // Get the order ID of the newly inserted order
    $order_id = $stmt->insert_id;  // Get the last inserted order ID

    // Get the cart items for the user
    $cart_sql = "SELECT * FROM cart WHERE user_id = ?";
    $cart_stmt = $conn->prepare($cart_sql);
    $cart_stmt->bind_param("i", $user_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();

    // Insert cart items into the order_items table
    while ($cart_row = $cart_result->fetch_assoc()) {
        $product_id = $cart_row['product_id'];
        $quantity = $cart_row['quantity'];

        // Fetch the price from the products table (optional if price is stored in cart)
        $price_sql = "SELECT price FROM products WHERE id = ?";
        $price_stmt = $conn->prepare($price_sql);
        $price_stmt->bind_param("i", $product_id);
        $price_stmt->execute();
        $price_result = $price_stmt->get_result();
        $price_row = $price_result->fetch_assoc();
        $price = $price_row['price'];  // Get the price from the products table

        // Insert into order_items
        $order_item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $order_item_stmt = $conn->prepare($order_item_sql);
        $order_item_stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
        $order_item_stmt->execute();
    }

    // After inserting into order_items, clear the cart for the user
    $clear_cart_sql = "DELETE FROM cart WHERE user_id = ?";
    $clear_cart_stmt = $conn->prepare($clear_cart_sql);
    $clear_cart_stmt->bind_param("i", $user_id);
    $clear_cart_stmt->execute();

    // Commit the transaction
    $conn->commit();

    // Redirect to success page
    header("Location: order-success.php");
    exit;

} catch (Exception $e) {
    // If an error occurs, roll back the transaction
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}
?>


</body>
</html>