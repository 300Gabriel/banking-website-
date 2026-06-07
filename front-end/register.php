<?php



include "../back-end/db.php";




if (isset($_POST['register'])) {
    $name =
        $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = password_hash(
        $password,
        PASSWORD_DEFAULT
    );
    $accountNumber = "23" . rand(10000000, 99999999);
    $sql =
        "INSERT INTO users ( full_name, email, password, account_number, balance
) VALUES ( '$name', '$email', '$hashedPassword', '$accountNumber',
25000 )";
    if (mysqli_query($conn, $sql)) {
        echo "Registration Successful";
    } else {
        echo mysqli_error($conn);
    }
}

?>



<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Open Account | TrustBank</title>

    <link rel="stylesheet" href="../front-end/style.css">
</head>

<body>
    <nav>
        <h1>
            <a href="index.html">
                <img src="../bank.svg" width="30" />
                TrustBank
            </a>
        </h1>

        <div class="nav-links">
            <a href="index.html">Home</a>
            <a href="login.html">Login</a>
            <a href="register.html">Register</a>
        </div>
    </nav>

    <section class="auth-section">
        <!-- LEFT SIDE -->
        <div class="auth-info">
            <h1>Open Your TrustBank Account Today</h1>

            <p>
                Join thousands of customers enjoying secure banking, instant
                transfers, online payments, savings accounts, and 24/7 access to their
                money.
            </p>

            <div class="auth-features">
                <div>💻 Instant Account Creation</div>
                <div>🛡️ Secure Banking</div>
                <div>📩 Free Online Transfers</div>
                <div>📱 Mobile Banking Access</div>
            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="auth-card">
            <form action="register.php" method="POST">
                <h2>Create Account</h2>

                <input
                    type="text"
                    id="name"
                    name="full_name"
                    placeholder="Full Name"
                    required />

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Email Address"
                    required />

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Password"
                    required />
                <button type="submit" name="register">Open Account</button>

                <p class="auth-link">
                    Already have an account?
                    <a href="login.html">Login</a>
                </p>
            </form>
        </div>
    </section>

    <script src="script.js"></script>
</body>

</html>