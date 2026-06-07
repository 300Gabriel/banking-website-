<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Dashboard</title>

  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <nav>
    <h1>
      <a href="index.html"><img src="/bank.svg" width="30" /> TrustBank</a>
    </h1>
    <div class="nav-links">
      <a href="index.html">Home</a>
      <a href="login.html">Login</a>
      <a href="register.html">Register</a>
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

        <button id="logout-btn">Logout</button>
      </div>
    </aside>

    <main class="main-content">
      <h1 id="welcome-message"></h1>

      <!-- PROFILE + ACCOUNT -->
      <div class="dashboard-header">
        <div class="profile-card">
          <img id="profile-image" alt="Profile Image" />

          <div class="profile-details">
            <h2 id="profile-name"></h2>
            <p id="profile-email"></p>
            <p id="profile-account-type"></p>
            <p id="account-number"></p>
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

          <h1 id="balance">₦25,000.00</h1>

          <small> Last updated just now </small>
        </div>

        <div class="stat-card">
          <h3>Total Transactions</h3>
          <h1 id="transaction-count">0</h1>
        </div>

        <div class="stat-card">
          <h3>Account Type</h3>
          <h1 id="account-type-card">Savings</h1>
        </div>
      </div>

      <!-- <div class="quick-actions">
          <button id="open-send">
            📤
            <span>Send</span>
          </button>

          <button id="open-deposit">
            💰
            <span>Add Money</span>
          </button>

          <button id="open-withdraw">
            🏧
            <span>Withdraw</span>
          </button>

          <button id="open-card">
            💳
            <span>Cards</span>
          </button>
        </div> -->

      <!-- PROFILE SETTINGS -->
      <div class="profile-settings">
        <h2>Update Profile</h2>

        <form id="profile-form">
          <input type="text" id="phone" placeholder="Phone Number" />

          <input type="text" id="address" placeholder="Address" />

          <button type="submit">Save Changes</button>
        </form>
      </div>
      <div class="deposit-section">
        <h2>Deposit Money</h2>

        <form id="deposit-form">
          <input
            type="number"
            id="deposit-amount"
            placeholder="Enter amount"
            required />

          <button type="submit">Deposit Funds</button>
        </form>
      </div>

      <div class="withdraw-section">
        <h2>Withdraw Money</h2>

        <form id="withdraw-form">
          <input
            type="number"
            id="withdraw-amount"
            placeholder="Enter amount"
            required />

          <button type="submit">Withdraw Funds</button>
        </form>
      </div>

      <div class="transfer-section">
        <h2>Transfer Money</h2>

        <form id="transfer-form">
          <input
            type="text"
            id="recipient-transfer"
            placeholder="Recipient Account Number"
            required />

          <input type="number" id="amount" placeholder="Amount" required />

          <button type="submit">Send Money</button>
        </form>
      </div>

      <!-- TRANSACTION SEARCH -->
      <div class="search-box">
        <input
          type="text"
          id="search-transaction"
          placeholder="Search transactions..." />
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

          <p><strong>Status:</strong> Successful</p>

          <p><strong>Date:</strong> <span id="receipt-date"></span></p>

          <p><strong>Balance:</strong> $<span id="receipt-balance"></span></p>

          <hr />

          <h3>Thank you for banking with TrustBank</h3>

          <button id="print-receipt">Print Receipt</button>

          <button id="close-receipt">Close</button>
        </div>
      </div>

      <!-- TRANSACTIONS -->

      <div class="transactions" id="transactions-container">
        <h2>Recent Transactions</h2>
      </div>

      <!-- TOAST NOTIFICATION -->
      <div id="toast"></div>
    </main>
  </div>

  <script src="script.js"></script>
</body>

</html>