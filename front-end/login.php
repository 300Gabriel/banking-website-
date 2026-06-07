<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>

  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <nav>
    <h1>
      <a href="index.html"><img src="../bank.svg" width="30" /> TrustBank</a>
    </h1>

    <div class="nav-links">
      <a href="index.html">Home</a>
      <a href="login.html">Login</a>
      <a href="register.html">Register</a>
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
      <form id="login-form">
        <h2>Sign In</h2>

        <input
          type="email"
          id="login-email"
          placeholder="Enter your email"
          required />

        <div class="password-box">
          <input
            type="password"
            id="login-password"
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

        <button type="submit">Login</button>

        <p class="register-link">
          Don't have an account?
          <a href="register.html">Register</a>
        </p>
      </form>
    </div>
  </section>

  <script src="script.js"></script>
</body>

</html>