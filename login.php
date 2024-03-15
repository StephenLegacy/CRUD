<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        header("Location: index.php");
        exit();
    } else {
        echo "<p style='color: red;'>Invalid credentials!</p>";
    }
}
?>

<link rel="stylesheet" href="style.css">
<div class="container" style="max-width: 400px; margin: auto; text-align: center;">
    <h2>Login</h2>
    <form method="POST" style="display: flex; flex-direction: column; gap: 10px;">
        <input type="email" name="email" placeholder="Email" required style="padding: 10px; width: 100%; border: 1px solid #ddd; border-radius: 5px;">
        <input type="password" name="password" placeholder="Password" required style="padding: 10px; width: 100%; border: 1px solid #ddd; border-radius: 5px;">
        <button type="submit" style="background-color: #007bff; color: white; padding: 12px; border: none; border-radius: 5px; cursor: pointer;">
            Login
        </button>
    </form>

    <!-- Register & Admin Login Links -->
    <div style="margin-top: 15px;">
        <a href="register.php" style="background-color: #28a745; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; margin-right: 10px;">
            Register
        </a>
        <a href="admin_login.php" style="background-color: #ff9800; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none;">
            Admin Login
        </a>
    </div>
</div>
