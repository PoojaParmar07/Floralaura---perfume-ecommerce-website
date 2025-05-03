<?php
session_start();
include 'database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate input fields
    if (empty($email) || empty($password)) {
        echo "<script>alert('Please enter both email and password.'); window.location.href='login.php';</script>";
        exit();
    }

    // Check if the user exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("SQL error: " . $conn->error); // Debugging
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Debugging: Check if user exists
    if (!$user) {
        die("User not found.");
    }

    if (password_verify($password, $user['password'])) {
        // Regenerate session ID for security
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];

        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Invalid email or password.'); window.location.href='login.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

<div class="card shadow p-4" style="width: 400px;">
    <h2 class="text-center mb-4">Login</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn w-100 text-white" style="background-color: darkred;">Login</button>
    </form>
    <p class="text-center mt-3">Don't have an account? <a href="register.php">Register</a></p>
</div>

</body>
</html>
