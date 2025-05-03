<?php
session_start(); // Ensure session is started
include 'database/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to continue'); window.location.href='login.php';</script>";
    exit;
}

if (isset($_GET['cart_id'])) {
    $cart_id = $_GET['cart_id'];
    $user_id = $_SESSION['user_id'];

    // Secure query using prepared statement
    $delete_query = "DELETE FROM cart WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("ii", $cart_id, $user_id);

    if ($stmt->execute()) {
        echo "<script>window.location.href='cart.php';</script>";
    } else {
        echo "<script>window.location.href='cart.php';</script>";
    }
}
?>
