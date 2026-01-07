<?php
require '../inc/db.php';
session_start();
if(empty($_SESSION['admin_id'])) header("Location: login.php");
?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin Dashboard</title>
<link rel="stylesheet" href="/carrental/assets/style.css"></head><body>
<section class="content">
  <h2>Admin Dashboard</h2>
  <nav>
    <a href="manage_cars.php" class="btn">Manage Cars</a>
    <a href="manage_bookings.php" class="btn">Manage Bookings</a>
    <a href="logout.php" class="btn">Logout</a>
  </nav>
</section>
</body></html>
