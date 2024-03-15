<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin["password"])) {
        $_SESSION["admin_id"] = $admin["id"];
        header("Location: admin.php"); // Redirect to admin panel
        exit();
    } else {
        echo "<p style='color: red; text-align: center;'>Invalid admin credentials!</p>";
    }
}
?>

<link rel="stylesheet" href="style.css">
<div class="container" style="max-width: 400px; margin: auto; text-align: center;">
    <h2>Admin Login</h2>
    <form method="POST" style="display: flex; flex-direction: column; gap: 10px;">
        <input type="email" name="email" placeholder="Admin Email" required style="padding: 10px; width: 100%; border: 1px solid #ddd; border-radius: 5px;">
        <input type="password" name="password" placeholder="Admin Password" required style="padding: 10px; width: 100%; border: 1px solid #ddd; border-radius: 5px;">
        <button type="submit" style="background-color: #ff9800; color: white; padding: 12px; border: none; border-radius: 5px; cursor: pointer;">
            Login as Admin
        </button>
    </form>

    <div style="margin-top: 15px;">
        <a href="login.php" style="background-color: #007bff; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none;">
            User Login
        </a>
    </div>
</div>
