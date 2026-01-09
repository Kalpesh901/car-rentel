<?php
require 'inc/db.php';
session_start();
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $license = trim($_POST['license']);
    $password = $_POST['password'];

    if(!$name || !$email || !$license || !$password) $errors[] = "Please fill required fields.";

    if(empty($errors)){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO customers (Name, Email, Contact, LicenseNo, Password) VALUES (?, ?, ?, ?, ?)");
        try {
            $stmt->execute([$name,$email,$contact,$license,$hash]);
            $_SESSION['customer_id'] = $pdo->lastInsertId();
            $_SESSION['customer_name'] = $name;
            header("Location: index.php");
            exit;
        } catch (PDOException $e){
            if($e->getCode() == 23000) $errors[] = "Email or License number already registered.";
            else $errors[] = $e->getMessage();
        }
    }
}
require 'inc/header.php';
?>
<section class="content">
  <h2>Register</h2>
  <?php foreach($errors as $err) echo "<p class='error'>".htmlspecialchars($err)."</p>"; ?>
  <form method="post">
    <label>Name</label><br>
    <input name="name" required><br><br>
    <label>Email</label><br>
    <input name="email" type="email" required><br><br>
    <label>Contact</label><br>
    <input name="contact"><br><br>
    <label>License No</label><br>
    <input name="license" required><br><br>
    <label>Password</label><br>
    <input name="password" type="password" required><br><br>
    <button class="btn" type="submit">Register</button>
  </form>
</section>
<?php require 'inc/footer.php'; ?>
