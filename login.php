<?php
require 'inc/db.php';
session_start();
$err = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT CustomerID, Name, Password FROM customers WHERE Email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if($user && password_verify($password, $user['Password'])){
        $_SESSION['customer_id'] = $user['CustomerID'];
        $_SESSION['customer_name'] = $user['Name'];
        header("Location: index.php");
        exit;
    } else {
        $err = "Invalid credentials.";
    }
}
require 'inc/header.php';
?>
<section class="content">
  <h2>Login</h2>
  <?php if($err) echo "<p class='error'>$err</p>"; ?>
  <form method="post">
    <label>Email</label><br>
    <input type="email" name="email" required><br><br>
    <label>Password</label><br>
    <input type="password" name="password" required><br><br>
    <button class="btn">Login</button>
  </form>
</section>
<?php require 'inc/footer.php'; ?>
