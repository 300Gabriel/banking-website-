<?php

session_start();
include "../back-end/db.php";



if (isset($_POST['login'])) {

  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE email='$email'";

  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {

    $user = mysqli_fetch_assoc($result);

    if (password_verify($password, $user['password'])) {

      $_SESSION['user_id'] = $user['id'];

      $_SESSION['full_name'] = $user['full_name'];

      $_SESSION['email'] = $user['email'];

      $_SESSION['account_number'] = $user['account_number'];

      $_SESSION['balance'] = $user['balance'];


      header("Location: ../front-end/dashboard.php");
      exit();
    } else {

      echo "Wrong Password";
    }
  } else {

    echo "User Not Found";
  }
}
?>






<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>

  <link rel="stylesheet" href="../css/components.css">
  <link rel="stylesheet" href="../css/auth.css">
</head>

<body>
  <nav>
    <h1>
      <a href="../front-end/index.php"><img src="../bank.svg" width="30" /> TrustBank</a>
    </h1>

    <div class="nav-links">
      <a href="../front-end/index.php">Home</a>
      <a href="../front-end/login.php">Login</a>
      <a href="../front-end/register.php">Register</a>
    </div>
  </nav>

  <section class="login-page">
    <div class="login-left">
      <h1>Welcome Back</h1>

      <p>
        Securely access your TrustBank account, manage your finances, transfer
        funds and monitor transactions from anywhere.
      </p>

      <div class="security-box">
        <h3>🔒 Bank-Level Security</h3>
        <p>Your account is protected with advanced encryption.</p>
      </div>
    </div>

    <div class="login-card">
      <form action="login.php" method="POST" id="php-login-form">
        <h2>Sign In</h2>

        <input
          type="email"
          name="email"
          placeholder="Enter your email"
          required />

        <div class="password-box">
          <input
            type="password"
            name="password"
            placeholder="Enter your password"
            required />
          <span id="toggle-password">🛡️</span>
        </div>

        <div class="login-options">
          <label>
            <input type="checkbox" />
            Remember Me
          </label>

          <a href="#">Forgot Password?</a>
        </div>

        <button type="submit" name="login">
          Login
        </button>

        <p class="register-link">
          Don't have an account?
          <a href="../front-end/register.php">Register</a>
        </p>
      </form>
    </div>
  </section>

  <script src="../front-end/script.js"></script>
</body>

</html>