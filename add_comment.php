<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$post_id = $_POST["post_id"];
$comment = $_POST["comment"];

$stmt = $pdo->prepare("INSERT INTO comments (user_id, post_id, comment) VALUES (?, ?, ?)");
$stmt->execute([$user_id, $post_id, $comment]);

header("Location: index.php");
?>
