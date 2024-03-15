<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($user["role"] !== "admin") {
    die("<div style='text-align:center; font-size:18px; color:red;'>Access Denied</div>");
}

$users = $pdo->query("SELECT * FROM users")->fetchAll();
?>

<link rel="stylesheet" href="admin.css">
<div class="container">
    <h2>Admin Panel</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user["id"] ?></td>
            <td><?= $user["username"] ?></td>
            <td><?= $user["email"] ?></td>
            <td><?= ucfirst($user["role"]) ?></td>
        </tr>
        <?php endforeach; ?>
    </table> 
</div>
