<?php
session_start();

include "../back-end/db.php";

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}


$id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id='$id'";

$result = mysqli_query($conn, $sql);

$user = mysqli_fetch_assoc($result);



$account = $user['account_number'];

$search = "";

if (isset($_GET['search'])) {

  $search = mysqli_real_escape_string(
    $conn,
    $_GET['search']
  );
}

if (isset($_GET['type'])) {

  $type = mysqli_real_escape_string(
    $conn,
    $_GET['type']
  );
}

$transactionSql = "
SELECT *
FROM transactions
WHERE
(
sender_account='$account'
OR receiver_account='$account'
)
";

if (!empty($search)) {

  $transactionSql .= "
    AND
    (
        reference LIKE '%$search%'
        OR transaction_type LIKE '%$search%'
    )
    ";
}
if (!empty($type)) {
  $transactionSql .= "
    AND transaction_type='$type'
    ";
}
$transactionSql .= "
ORDER BY created_at DESC
";
$transactionResult = mysqli_query(
  $conn,
  $transactionSql
);

$notificationResult = mysqli_query(
  $conn,
  "SELECT *
     FROM notifications
     WHERE user_account='$account'
     ORDER BY created_at DESC
     LIMIT 10"
);
// Total Deposits
$depositQuery = mysqli_query(
  $conn,
  "SELECT SUM(amount) AS total
     FROM transactions
     WHERE receiver_account='$account'
     AND transaction_type='deposit'"
);
$beneficiariesResult = mysqli_query(
  $conn,
  "SELECT b.*, u.full_name
     FROM beneficiaries b
     JOIN users u
     ON b.beneficiary_account = u.account_number
     WHERE b.user_account='$account'
     ORDER BY b.created_at DESC"
);

$totalDeposits = mysqli_fetch_assoc($depositQuery)['total'] ?? 0;


// Total Withdrawals
$withdrawQuery = mysqli_query(
  $conn,
  "SELECT SUM(amount) AS total
     FROM transactions
     WHERE sender_account='$account'
     AND transaction_type='withdraw'"
);
$countQuery = mysqli_query(
  $conn,
  "SELECT COUNT(*) AS total
     FROM notifications
     WHERE user_account='$account'
     AND is_read=0"
);

$unreadCount = mysqli_fetch_assoc($countQuery)['total'];

$totalWithdrawals = mysqli_fetch_assoc($withdrawQuery)['total'] ?? 0;


// Total Transfers Sent
$transferQuery = mysqli_query(
  $conn,
  "SELECT SUM(amount) AS total
     FROM transactions
     WHERE sender_account='$account'
     AND transaction_type='transfer'"
);

$totalTransfers = mysqli_fetch_assoc($transferQuery)['total'] ?? 0;
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Dashboard</title>
  <link rel="stylesheet" href="../css/dashboard.css">

  <link rel="stylesheet" href="../css/components.css">

  <link rel="stylesheet" href="../css/receipt.css">
</head>

<body>
  <nav>
    <h1>
      <a href="../front-end/index.php"><img src="../bank.svg" width="30" /> TrustBank</a>

    </h1>


    <div class="nav-links">
      <a href="../front-end/index.php">Home</a>
      <a href="../front-end/login.php">Login</a>

      <div class="notification-wrapper">
        <a href="notifications.php" class="notification-icon">
          🔔
          <span class="notification-count">
            <?php echo $unreadCount; ?>
          </span>
        </a>
      </div>

      <a href="../front-end/register.php">Register</a>
    </div>

  </nav>

  <div class="dashboard-layout">
    <aside class="sidebar">
      <div class="sidebar-top">
        <h2>TrustBank</h2>
        <ul>
          <li class="active">
            <a href="#">
              <span>🏠</span>
              Dashboard
            </a>
          </li>

          <li>
            <a href="#transactions-container">
              <span>💸</span>
              Transactions
            </a>
          </li>

          <li>
            <a href="#transfer-form">
              <span>📤</span>
              Transfer
            </a>
          </li>

          <li>
            <a href="#">
              <span>💳</span>
              Cards
            </a>
          </li>

          <li>
            <a href="#">
              <span>⚙️</span>
              Settings
            </a>
          </li>
        </ul>


      </div>

      <div class="sidebar-bottom">
        <button id="theme-toggle">🌙 Dark Mode</button>

        <a href="logout.php" class="logout-btn">
          Logout
        </a>
      </div>
    </aside>

    <main class="main-content">
      <h1>
        Welcome,
        <?php echo $_SESSION['full_name']; ?>👋
        <p class="just-sector" style="font-size: 20px;">Manage your finances securely.</p>

      </h1>

      <!-- PROFILE + ACCOUNT -->
      <div class="dashboard-header">
        <div class="profile-card">
          <img
            src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['full_name']; ?>&background=2563eb&color=fff"
            alt="Profile Image">

          <div class="profile-details">
            <h2 id="profile-name"></h2>
            <p>
              <?php echo $_SESSION['email']; ?>
            </p>
            <p id="profile-account-type"></p>
            <p>
              <?php echo $_SESSION['account_number']; ?>
            </p>
          </div>
        </div>

        <div class="virtual-card">
          <div class="card-chip"></div>

          <h3>TRUSTBANK PREMIUM</h3>

          <div class="card-number">5399 1234 5678 9010</div>

          <div class="card-footer">
            <span id="profile-name-card">CARD HOLDER</span>
            <span>VISA</span>
          </div>
        </div>
      </div>

      <!-- STATS -->
      <div class="stats-grid">
        <div class="balance-card">
          <p>Available Balance</p>

          <h1>
            ₦<?php echo number_format($user['balance'], 2); ?>
          </h1>

          <small> Last updated just now </small>
        </div>

        <div class="stat-card">
          <h3>Total Transactions</h3>
          <?php

          $countSql = "
SELECT COUNT(*) AS total
FROM transactions
WHERE sender_account='$account'
OR receiver_account='$account'
";

          $countResult = mysqli_query(
            $conn,
            $countSql
          );

          $countData = mysqli_fetch_assoc(
            $countResult
          );
          ?>

          <h1>
            <?php echo $countData['total']; ?>
          </h1>
        </div>

        <div class="stat-card">
          <h3>Account Type</h3>
          <h1>
            <?php echo $user['account_type']; ?>
          </h1>
        </div>
        <div class="stat-card">
          <h3>Total Deposits</h3>

          <h1>
            ₦<?php echo number_format($totalDeposits, 2); ?>
          </h1>
        </div>

        <div class="stat-card">
          <h3>Total Withdrawals</h3>

          <h1>
            ₦<?php echo number_format($totalWithdrawals, 2); ?>
          </h1>
        </div>

        <div class="stat-card">
          <h3>Total Transfers</h3>

          <h1>
            ₦<?php echo number_format($totalTransfers, 2); ?>
          </h1>
        </div>
      </div>


      <div class="quick-actions">

        <button id="open-transfer">
          📤 Transfer
        </button>

        <button id="open-deposit">
          💰 Deposit
        </button>

        <button id="open-withdraw">
          🏧 Withdraw
        </button>

        <button id="open-profile">
          ⚙️ Profile
        </button>

      </div>

      <!-- PROFILE MODAL -->
      <div class="action-modal" id="profile-modal">

        <div class="action-box">

          <span class="close-modal">&times;</span>

          <h2>Update Profile</h2>

          <form action="../API/update-profile.php" method="POST">

            <input
              type="text"
              name="phone"
              placeholder="Phone Number"
              value="<?php echo $user['phone']; ?>">

            <input
              type="text"
              name="address"
              placeholder="Address"
              value="<?php echo $user['address']; ?>">

            <select name="account_type">

              <option value="Savings"
                <?php if ($user['account_type'] == "Savings") echo "selected"; ?>>
                Savings
              </option>

              <option value="Current"
                <?php if ($user['account_type'] == "Current") echo "selected"; ?>>
                Current
              </option>

              <option value="Business"
                <?php if ($user['account_type'] == "Business") echo "selected"; ?>>
                Business
              </option>

            </select>

            <button type="submit">
              Save Changes
            </button>

          </form>

          <hr><br>

          <h2>Change Password</h2>

          <form action="../API/change-password.php" method="POST">

            <input
              type="password"
              name="current_password"
              placeholder="Current Password"
              required>

            <input
              type="password"
              name="new_password"
              placeholder="New Password"
              required>

            <input
              type="password"
              name="confirm_password"
              placeholder="Confirm New Password"
              required>

            <button type="submit">
              Change Password
            </button>

          </form>

        </div>

      </div>
      <!-- DEPOSIT MODAL -->
      <div class="action-modal" id="deposit-modal">

        <div class="action-box">

          <span class="close-modal">&times;</span>

          <h2>Deposit Money</h2>

          <form action="../API/deposit.php" method="POST">

            <input
              type="number"
              name="amount"
              placeholder="Enter amount"
              required>

            <button type="submit">
              Deposit Funds
            </button>
          </form>
        </div>
      </div>

      <!-- WITHDRAW MODAL -->
      <div class="action-modal" id="withdraw-modal">
        <div class="action-box">
          <span class="close-modal">&times;</span>
          <h2>Withdraw Money</h2>
          <form action="../API/withdraw.php" method="POST">

            <input
              type="number"
              name="amount"
              placeholder="Enter amount"
              required>

            <button type="submit">
              Withdraw Funds
            </button>
          </form>
        </div>
      </div>

      <!-- TRANSFER MODAL -->
      <div class="action-modal" id="transfer-modal">

        <div class="action-box">

          <span class="close-modal">&times;</span>

          <h2>Transfer Money</h2>

          <form action="../API/transfer.php" method="POST">

            <input
              type="text"
              id="recipient_account"
              name="recipient_account"
              placeholder="Recipient Account Number"
              required>

            <p id="account-name"></p>

            <input
              type="number"
              id="amount"
              name="amount"
              placeholder="Amount"
              required>
            <input
              type="text"
              name="description"
              placeholder="Narration (optional)">
            <button type="submit">
              Send Money
            </button>
          </form>
        </div>
      </div>

      <!-- TRANSACTION SEARCH -->
      <div class="search-box">
        <form method="GET">

          <input
            type="text"
            name="search"
            placeholder="Reference">

          <select name="type">

            <option value="">
              All Types
            </option>

            <option value="deposit">
              Deposit
            </option>

            <option value="withdraw">
              Withdraw
            </option>

            <option value="transfer">
              Transfer
            </option>

          </select>

          <button type="submit">
            Filter
          </button>

        </form>
      </div>
      <br />

      <!-- RECEIPT MODAL -->

      <div id="receipt-modal" class="receipt-modal">

        <div class="receipt">

          <h2>TrustBank Receipt</h2>

          <hr />

          <p><strong>Reference:</strong> <span id="receipt-ref"></span></p>

          <p><strong>Sender:</strong> <span id="receipt-sender"></span></p>

          <p>
            <strong>Recipient:</strong> <span id="receipt-recipient"></span>
          </p>

          <p><strong>Amount:</strong> $<span id="receipt-amount"></span></p>

          <p><strong>Status:</strong> <span id="receipt-status"></span></p>

          <p><strong>Date:</strong> <span id="receipt-date"></span></p>

          <p><strong>Balance:</strong> $<span id="receipt-balance"></span></p>

          <hr />

          <h3>Thank you for banking with TrustBank</h3>

          <button id="print-receipt">Print Receipt</button>

          <button id="close-receipt">Close</button>
        </div>
      </div>

      <div class="beneficiaries">

        <h2>Saved Beneficiaries</h2>

        <?php while ($beneficiary = mysqli_fetch_assoc($beneficiariesResult)) { ?>

          <div class="beneficiary-card">

            <strong class="strong">
              <?php echo $beneficiary['full_name']; ?>
            </strong>

            <br>

            <small>
              <?php echo $beneficiary['beneficiary_account']; ?>
            </small>


            <a href="../API/delete-beneficiary.php?id=<?php echo $beneficiary['id']; ?>" class="remove-beneficiary ">
              Remove
            </a>

          </div>

        <?php } ?>

      </div>

      <!-- TRANSACTIONS -->

      <div class="transactions">

        <h2>Recent Transactions</h2>

        <?php while ($row = mysqli_fetch_assoc($transactionResult)) { ?>

          <div class="transaction">

            <div class="transaction-left">

              <div class="icon">

                <?php
                if ($row['transaction_type'] == "deposit") {
                  echo "💰";
                } elseif ($row['transaction_type'] == "withdraw") {
                  echo "🏧";
                } else {
                  echo "📤";
                }
                ?>

              </div>


              <div class="transaction-info">

                <h4>
                  <a href="transaction-details.php?id=<?php echo $row['id']; ?>">
                    <?php echo ucfirst($row['transaction_type']); ?>
                  </a>
                </h4>

                <small>
                  <?php echo $row['reference']; ?>
                </small>

              </div>

            </div>

            <div class="transaction-right">

              <strong>
                ₦<?php echo number_format($row['amount'], 2); ?>
              </strong>

              <br>

              <small>
                <?php echo date("d M Y", strtotime($row['created_at'])); ?>
              </small>

            </div>

          </div>

        <?php } ?>

      </div>

      <!-- TOAST NOTIFICATION -->
      <div id="toast"></div>
    </main>
  </div>

  <script src="../front-end/script.js"></script>
</body>

</html>