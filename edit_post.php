<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"]) || !isset($_GET["id"])) {
    die("Unauthorized access");
}

$post_id = $_GET["id"];
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];

    $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
    if ($stmt->execute([$title, $content, $post_id])) {
        header("Location: index.php");
    } else {
        echo "Error updating post.";
    }
}
?>

<link rel="stylesheet" href="style.css">
<div class="container">
    <h2>Edit Post</h2>
    <form method="POST">
        <input type="text" name="title" value="<?= $post['title'] ?>" required><br>
        <textarea name="content" required><?= $post['content'] ?></textarea><br>
        <button type="submit">Update Post</button>
    </form>
</div>
