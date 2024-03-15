<?php
try {
    // Connect to SQLite database (creates database if it doesn't exist)
    $pdo = new PDO("sqlite:database.sqlite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Users Table with 'role' column from the beginning
$pdo->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    profile_picture TEXT DEFAULT 'profile_pictures/default.png',
    role TEXT DEFAULT 'user' -- New role column with default value 'user'
);");

// Insert Default Admin if Not Exists
$admin_username = "admin";
$admin_email = "admin@example.com";
$admin_password = password_hash("Admin@123", PASSWORD_DEFAULT); // Secure password
$admin_role = "admin";

// Check if admin already exists
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = ?");
$stmt->execute([$admin_role]);
$adminExists = $stmt->fetchColumn();

if (!$adminExists) {
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$admin_username, $admin_email, $admin_password, $admin_role]);
}


    // Create Posts Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        title TEXT NOT NULL,
        content TEXT NOT NULL,
        image TEXT,  -- Stores image file path
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    // Create Comments Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS comments (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        post_id INTEGER NOT NULL,
        comment TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY(post_id) REFERENCES posts(id) ON DELETE CASCADE
    )");

    // echo "Database and tables created successfully!";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
