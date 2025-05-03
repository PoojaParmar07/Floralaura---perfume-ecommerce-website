<?php
session_start();
include 'database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($name) || empty($email) || empty($password)) {
        echo "<script>alert('All fields are required!'); window.location.href='register.php';</script>";
        exit();
    }

    // Check if email already exists
    $check_email = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email already registered! Try logging in.'); window.location.href='register.php';</script>";
        exit();
    }

    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $hashed_password);

    if ($stmt->execute()) {
        // Store user session after successful registration
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['name'] = $name;

        echo "<script> window.location.href='login.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error: Unable to register.'); window.location.href='register.php';</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

<div class="card shadow p-4" style="width: 400px;">
    <h2 class="text-center mb-4">Register</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn w-100 text-white" style="background-color: darkred;">Register</button>
    </form>
    <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
</div>

</body>
</html>
