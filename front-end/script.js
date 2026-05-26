const registerForm = document.querySelector("#register-form");

// ==========================
// REGISTER SECTION
// ==========================

if (registerForm) {
  registerForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const name = document.querySelector("#name").value;
    const email = document.querySelector("#email").value;
    const password = document.querySelector("#password").value;

    // Generate Fake Account Number
    const accountNumber = Math.floor(1000000000 + Math.random() * 9000000000);

    // New User Object
    const user = {
      name,
      email,
      password,
      accountNumber,
      balance: 25000,
      transactions: [],
    };

    // Get Existing Users
    const users = JSON.parse(localStorage.getItem("users")) || [];

    // Check Existing Email
    const existingUser = users.find(function (u) {
      return u.email === email;
    });

    if (existingUser) {
      alert("Email already exists");
      return;
    }

    // Add User
    users.push(user);

    // Save Users
    localStorage.setItem("users", JSON.stringify(users));

    alert("Registration Successful");

    window.location.href = "login.html";
  });
}

// ==========================
// LOGIN SECTION
// ==========================

const loginForm = document.querySelector("#login-form");

if (loginForm) {
  loginForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const email = document.querySelector("#login-email").value;
    const password = document.querySelector("#login-password").value;

    // Get Users
    const users = JSON.parse(localStorage.getItem("users")) || [];

    // Find User
    const foundUser = users.find(function (user) {
      return user.email === email && user.password === password;
    });

    if (foundUser) {
      // Save Logged In User
      localStorage.setItem("currentUser", JSON.stringify(foundUser));

      localStorage.setItem("loggedIn", "true");

      alert("Login Successful");

      window.location.href = "dashboard.html";
    } else {
      alert("Invalid Email or Password");
    }
  });
}

// ==========================
// DASHBOARD SECTION
// ==========================

const welcomeMessage = document.querySelector("#welcome-message");

if (welcomeMessage) {
  // Get Current User
  const user = JSON.parse(localStorage.getItem("currentUser"));

  // Protect Dashboard
  if (!user) {
    window.location.href = "login.html";
  }

  // Welcome Message
  welcomeMessage.textContent = `Welcome, ${user.name}`;

  // Account Number
  const accountNumber = document.querySelector("#account-number");

  accountNumber.textContent = `Account Number: ${user.accountNumber}`;

  // Balance
  let balance = user.balance;

  const balanceElement = document.querySelector("#balance");

  balanceElement.textContent = `$${balance}`;

  // Transactions
  let transactions = user.transactions || [];

  const transactionsContainer = document.querySelector(
    "#transactions-container",
  );

  // ==========================
  // RENDER TRANSACTIONS
  // ==========================

  function renderTransactions() {
    transactionsContainer.innerHTML = "<h2>Recent Transactions</h2>";

    transactions.forEach(function (transaction) {
      const transactionDiv = document.createElement("div");

      transactionDiv.classList.add("transaction");

      transactionDiv.innerHTML = `
        <div class="transaction-info">
          <h4>${transaction.name}</h4>
          <small>${transaction.date}</small>
        </div>

      <span class="amount ${
        transaction.type === "received" ? "income" : "expense"
      }">
  ${transaction.type === "received" ? "+" : "-"}$${transaction.amount}
</span>
      `;

      transactionsContainer.appendChild(transactionDiv);
    });
  }

  renderTransactions();

  // ==========================
  // TRANSFER SECTION
  // ==========================

  const transferForm = document.querySelector("#transfer-form");

  if (transferForm) {
    transferForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const recipient = document.querySelector("#recipient").value.trim();

      const amount = Number(document.querySelector("#amount").value);

      // Validation
      if (amount <= 0) {
        alert("Enter valid amount");
        return;
      }

      // Get all users
      const users = JSON.parse(localStorage.getItem("users")) || [];

      // Find recipient user
      const recipientUser = users.find(function (u) {
        return u.name.toLowerCase() === recipient.toLowerCase();
      });

      // Check recipient
      if (!recipientUser) {
        alert("Recipient not found");
        return;
      }

      // Prevent self transfer
      if (recipientUser.email === user.email) {
        alert("You cannot transfer to yourself");
        return;
      }

      // Balance check
      if (amount > balance) {
        alert("Insufficient Balance");
        return;
      }

      // Deduct sender balance
      balance -= amount;

      user.balance = balance;

      // Add receiver balance
      recipientUser.balance += amount;

      // Sender transaction
      const senderTransaction = {
        name: recipientUser.name,
        amount: amount,
        type: "sent",
        date: new Date().toLocaleDateString(),
      };

      // Receiver transaction
      const receiverTransaction = {
        name: user.name,
        amount: amount,
        type: "received",
        date: new Date().toLocaleDateString(),
      };

      // Add transactions
      transactions.unshift(senderTransaction);

      recipientUser.transactions.unshift(receiverTransaction);

      // Save sender transactions
      user.transactions = transactions;

      // Update all users
      const updatedUsers = users.map(function (u) {
        if (u.email === user.email) {
          return user;
        }

        if (u.email === recipientUser.email) {
          return recipientUser;
        }

        return u;
      });

      // Save updated users
      localStorage.setItem("users", JSON.stringify(updatedUsers));

      // Save current user
      localStorage.setItem("currentUser", JSON.stringify(user));

      // Update balance UI
      balanceElement.textContent = `$${balance}`;

      // Render transactions again
      renderTransactions();

      // Reset form
      transferForm.reset();

      alert(`$${amount} sent to ${recipientUser.name}`);
    });
  }
}

// ==========================
// LOGOUT SECTION
// ==========================

const logoutBtn = document.querySelector("#logout-btn");

if (logoutBtn) {
  logoutBtn.addEventListener("click", function () {
    localStorage.removeItem("loggedIn");

    localStorage.removeItem("currentUser");

    window.location.href = "login.html";
  });
}

// ==========================
// DARK MODE
// ==========================

const themeToggle = document.querySelector("#theme-toggle");

// Load Saved Theme
if (localStorage.getItem("darkMode") === "enabled") {
  document.body.classList.add("dark");
}

if (themeToggle) {
  themeToggle.addEventListener("click", function () {
    document.body.classList.toggle("dark");

    // Save Theme
    if (document.body.classList.contains("dark")) {
      localStorage.setItem("darkMode", "enabled");

      themeToggle.textContent = "☀️ Light Mode";
    } else {
      localStorage.setItem("darkMode", "disabled");

      themeToggle.textContent = "🌙 Dark Mode";
    }
  });
}
