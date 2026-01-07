<?php
require '../inc/db.php';
session_start();
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT AdminID, Password FROM admins WHERE Email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();
    if($admin && password_verify($password, $admin['Password'])){
        $_SESSION['admin_id'] = $admin['AdminID'];
        header("Location: dashboard.php");
        exit;
    } else {
        $err = "Invalid admin credentials.";
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin Login</title>
<link rel="stylesheet" href="/carrental/assets/style.css"></head><body>
<section class="content">
  <h2>Admin Login</h2>
  <?php if(!empty($err)) echo "<p class='error'>$err</p>"; ?>
  <form method="post">
    <label>Email</label><br><input name="email" type="email" required><br><br>
    <label>Password</label><br><input name="password" type="password" required><br><br>
    <button class="btn">Login</button>
  </form>
</section>
</body></html>
