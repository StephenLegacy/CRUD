<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $profile_picture = "profile_pictures/default.png"; // Default image path

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        die("Error: Email already exists! Try a different one.");
    }

    // Handle Profile Picture Upload
    if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["size"] > 0) {
        $targetDir = "profile_pictures/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES["profile_picture"]["name"]); // Prevent overwrites
        $profile_picture = $targetDir . $fileName;
        
        // Validate file type
        $allowedTypes = ["image/jpeg", "image/png", "image/gif"];
        if (!in_array($_FILES["profile_picture"]["type"], $allowedTypes)) {
            die("Error: Only JPG, PNG, and GIF files are allowed.");
        }

        // Validate file size (max 2MB)
        if ($_FILES["profile_picture"]["size"] > 2 * 1024 * 1024) {
            die("Error: File size must be under 2MB.");
        }

        // Move uploaded file
        if (!move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $profile_picture)) {
            die("Error uploading file.");
        }
    }

    // Insert User Data
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, profile_picture) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $password, $profile_picture]);
        header("Location: login.php"); // Redirect after success
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage()); // Debugging
    }
}
?>

<!-- Registration Form -->
<link rel="stylesheet" href="reg.css">
<div class="container">
    <h2>Register</h2>
    <form method="POST" action="register.php" enctype="multipart/form-data">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="file" name="profile_picture"><br>
        <button type="submit">Register</button>
    </form>
</div>
