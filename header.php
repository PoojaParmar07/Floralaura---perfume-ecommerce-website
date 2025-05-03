<?php 
session_start();
include 'database/db.php';

// Default cart count
$cart_count = $_SESSION['cart_count'] ?? 0;

// If user is logged in, fetch cart count from database
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT SUM(quantity) AS total_items FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_data = $result->fetch_assoc();
    $cart_count = $cart_data['total_items'] ?? 0;
    $_SESSION['cart_count'] = $cart_count; // Update session
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Floralaura - Choose your perfume</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons for icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top" style="height:70px">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php" style="font-size: 25px; color: #E57373;">Floral Aura</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">

                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Cart Icon with Badge -->
                    <li class="nav-item mx-2">
                        <a class="nav-link fw-bold position-relative d-flex align-items-center" href="cart.php">
                            <i class='bx bx-cart-alt fs-4'></i> <!-- Boxicon Cart -->
                            <span class="badge position-absolute top-30 start-100 right-20 translate-middle rounded-pill" style="background-color: #E57373;">
                                <?php echo $cart_count; ?>
                            </span>
                        </a>
                    </li>
                    <!-- Logout Link -->
                    <li class="nav-item mx-2">
                        <a class="nav-link fw-bold text-decoration-none" href="logout.php" style="color: #E57373;">Logout</a>
                    </li>
                <?php else: ?>
                    <!-- Register & Login Links -->
                    <li class="nav-item mx-2">
                        <a class="nav-link fw-bold text-decoration-none" href="register.php">Register</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link fw-bold text-primary text-decoration-none" href="login.php">Login</a>
                    </li>
                <?php endif; ?>
                
            </ul>
        </div>
    </div>
</nav>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
