<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Pagination setup
$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$stmt = $pdo->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->execute([$limit, $offset]);
$posts = $stmt->fetchAll();

// Get total number of posts
$stmt = $pdo->query("SELECT COUNT(*) FROM posts");
$totalPosts = $stmt->fetchColumn();
$totalPages = ceil($totalPosts / $limit);
?>

<link rel="stylesheet" href="global.css">
<div class="container">
    <h2>Posts</h2>

    <!-- Registration and Admin Login Buttons with Inline Styles -->
    <div style="display: flex; gap: 10px; margin-bottom: 15px;">
        <form action="register.php" method="GET">
            <button type="submit" style="background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer;">
                Register
            </button>
        </form>
        <form action="admin_login.php" method="GET">
            <button type="submit" style="background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer;">
                Admin Login
            </button>
        </form>
    </div>

    <!-- Post Creation Form create -->
    <form method="POST" action="add_post.php" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Post Title" required><br>
        <textarea name="content" placeholder="Post Content" required></textarea><br>
        <input type="file" name="image"><br>
        <button type="submit" style="background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer;">
            Add Post
        </button>
    </form>

    <!-- Display Posts -->
    <?php foreach ($posts as $post): ?>
        <div class="post" style="border: 1px solid #ddd; padding: 10px; margin-top: 15px; border-radius: 5px;">
            <img src="<?= $post["profile_picture"] ?: 'profile_pictures/default.png' ?>" width="50" class="profile-pic">
            <strong><?= $post["username"] ?></strong><br>
            <h3><?= $post["title"] ?></h3>
            <?php if ($post["image"]): ?>
                <img src="<?= $post["image"] ?>" width="200"><br>
            <?php endif; ?>
            <p><?= $post["content"] ?></p>
            <a href="edit_post.php?id=<?= $post['id'] ?>">Edit</a> |
            <a href="delete_post.php?id=<?= $post['id'] ?>">Delete</a>

            <!-- Comments Section -->
            <?php
            $comments_stmt = $pdo->prepare("SELECT comments.*, users.username, users.profile_picture FROM comments 
                JOIN users ON comments.user_id = users.id WHERE post_id = ? ORDER BY created_at DESC");
            $comments_stmt->execute([$post["id"]]);
            $comments = $comments_stmt->fetchAll();
            ?>
            <div class="comments">
                <h4>Comments:</h4>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment" style="border-top: 1px solid #ccc; padding: 5px 0;">
                        <img src="<?= $comment["profile_picture"] ?: 'profile_pictures/default.png' ?>" width="30" class="comment-pic">
                        <strong><?= $comment["username"] ?></strong> 
                        <p><?= $comment["comment"] ?></p>
                    </div>
                <?php endforeach; ?>

                <!-- Comment Form -->
                <form method="POST" action="add_comment.php">
                    <input type="hidden" name="post_id" value="<?= $post["id"] ?>">
                    <textarea name="comment" placeholder="Write a comment..." required></textarea><br>
                    <button type="submit" style="background-color: #ff9800; color: white; padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer;">
                        Post Comment
                    </button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Pagination -->
    <div style="margin-top: 15px;">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" style="padding: 8px 12px; text-decoration: none; background-color: #007bff; color: white; margin-right: 5px; border-radius: 3px;">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>

    <a href="logout.php" style="display: inline-block; margin-top: 15px; padding: 10px 15px; background-color: #dc3545; color: white; border-radius: 5px; text-decoration: none;">
        Logout
    </a>
</div>
