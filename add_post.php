<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$title = $_POST["title"];
$content = $_POST["content"];
$image = null;

if ($_FILES["image"]["size"] > 0) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $image = $targetDir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $image);
}

$stmt = $pdo->prepare("INSERT INTO posts (user_id, title, content, image) VALUES (?, ?, ?, ?)");
if ($stmt->execute([$user_id, $title, $content, $image])) {
    header("Location: index.php");
} else {
    echo "Error creating post.";
}
?>
