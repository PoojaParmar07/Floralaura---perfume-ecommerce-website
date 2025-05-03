<?php
session_start();
include 'database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Please log in to add items to the cart'); window.location.href='./main/public/login.php';</script>";
        exit;
    }

    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    try {
        // Check if product already exists in the cart
        $stmt = $conn->prepare("SELECT quantity FROM cart WHERE product_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $product_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart_item = $result->fetch_assoc();

        if ($cart_item) {
            // Product exists, update quantity
            $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE product_id = ? AND user_id = ?");
            $stmt->bind_param("ii", $product_id, $user_id);
        } else {
            // New product, insert into cart
            $stmt = $conn->prepare("INSERT INTO cart (product_id, user_id, quantity) VALUES (?, ?, 1)");
            $stmt->bind_param("ii", $product_id, $user_id);
        }

        if ($stmt->execute()) {
            // Update session cart count
            $stmt = $conn->prepare("SELECT SUM(quantity) AS total_items FROM cart WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $cart_data = $result->fetch_assoc();
            $_SESSION['cart_count'] = $cart_data['total_items'] ?? 0;

            echo "<script>alert('Item added to cart!'); window.location.href=document.referrer;</script>";
        } else {
            echo "<script>alert('Failed to add item to cart!'); window.location.href=document.referrer;</script>";
        }
    } catch (Exception $e) {
        error_log("Cart Error: " . $e->getMessage());
        echo "<script>alert('Something went wrong! Please try again.'); window.location.href=document.referrer;</script>";
    }
}
?>
