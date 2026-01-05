<?php
// create_admin.php - run once then delete or protect
require 'inc/db.php';

$email = 'admin@carrental.com';
$password = 'admin123'; // change this
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT IGNORE INTO admins (Email, Password) VALUES (?, ?)");
$stmt->execute([$email, $hash]);

echo "Admin created (or already exists). Email: $email\n";
