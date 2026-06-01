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

      profileImage: "https://ui-avatars.com/api/?name=" + name,

      phone: "",
      address: "",
      accountType: "Savings",

      joinDate: new Date().toLocaleDateString(),

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
  const user = JSON.parse(localStorage.getItem("currentUser"));

  if (!user) {
    window.location.href = "login.html";
  }

  // Profile Information
  const profileImage = document.querySelector("#profile-image");
  const profileName = document.querySelector("#profile-name");
  const profileEmail = document.querySelector("#profile-email");
  const profileAccountType = document.querySelector("#profile-account-type");

  if (profileImage) profileImage.src = user.profileImage;
  if (profileName) profileName.textContent = user.name;
  if (profileEmail) profileEmail.textContent = user.email;

  if (profileAccountType) {
    profileAccountType.textContent = "Account Type: " + user.accountType;
  }

  // Welcome Message
  welcomeMessage.textContent = `Welcome, ${user.name}`;

  // Account Number
  const accountNumber = document.querySelector("#account-number");

  if (accountNumber) {
    accountNumber.textContent = `Account Number: ${user.accountNumber}`;
  }

  // Balance
  let balance = user.balance;

  const balanceElement = document.querySelector("#balance");

  if (balanceElement) {
    balanceElement.textContent = `$${balance}`;
  }

  // ==========================
  // DEPOSIT MONEY
  // ==========================

  const depositForm = document.querySelector("#deposit-form");

  if (depositForm) {
    depositForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const amount = Number(document.querySelector("#deposit-amount").value);

      if (amount <= 0) {
        alert("Enter a valid amount");
        return;
      }

      balance += amount;

      user.balance = balance;

      const depositTransaction = {
        name: "Cash Deposit",
        amount: amount,
        type: "received",
        date: new Date().toLocaleDateString(),
      };

      transactions.unshift(depositTransaction);

      user.transactions = transactions;

      const users = JSON.parse(localStorage.getItem("users")) || [];

      const updatedUsers = users.map(function (u) {
        return u.email === user.email ? user : u;
      });

      localStorage.setItem("users", JSON.stringify(updatedUsers));

      localStorage.setItem("currentUser", JSON.stringify(user));

      balanceElement.textContent = `$${balance}`;

      renderTransactions();

      depositForm.reset();

      alert(`$${amount} deposited successfully`);
    });
  }

  // ==========================
  // WITHDRAW MONEY
  // ==========================

  const withdrawForm = document.querySelector("#withdraw-form");

  if (withdrawForm) {
    withdrawForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const amount = Number(document.querySelector("#withdraw-amount").value);

      if (amount <= 0) {
        alert("Enter a valid amount");
        return;
      }

      if (amount > balance) {
        alert("Insufficient balance");
        return;
      }

      balance -= amount;

      user.balance = balance;

      const withdrawTransaction = {
        name: "Cash Withdrawal",
        amount,
        type: "sent",
        date: new Date().toLocaleDateString(),
      };

      transactions.unshift(withdrawTransaction);

      user.transactions = transactions;

      const users = JSON.parse(localStorage.getItem("users")) || [];

      const updatedUsers = users.map(function (u) {
        return u.email === user.email ? user : u;
      });

      localStorage.setItem("users", JSON.stringify(updatedUsers));

      localStorage.setItem("currentUser", JSON.stringify(user));

      balanceElement.textContent = `$${balance}`;

      renderTransactions();

      withdrawForm.reset();

      alert(`$${amount} withdrawn successfully`);
    });
  }

  // Transactions
  let transactions = user.transactions || [];

  const transactionsContainer = document.querySelector(
    "#transactions-container",
  );

  function renderTransactions() {
    if (!transactionsContainer) return;

    transactionsContainer.innerHTML = "<h2>Recent Transactions</h2>";

    if (transactions.length === 0) {
      transactionsContainer.innerHTML += "<p>No transactions yet.</p>";
      return;
    }

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
  // TRANSFER MONEY
  // ==========================

  const transferForm = document.querySelector("#transfer-form");

  if (transferForm) {
    transferForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const recipient = document.querySelector("#recipient").value.trim();

      const amount = Number(document.querySelector("#amount").value);

      if (amount <= 0) {
        alert("Enter valid amount");
        return;
      }

      const users = JSON.parse(localStorage.getItem("users")) || [];

      const recipientUser = users.find(function (u) {
        return u.name.toLowerCase() === recipient.toLowerCase();
      });

      if (!recipientUser) {
        alert("Recipient not found");
        return;
      }

      if (recipientUser.email === user.email) {
        alert("You cannot transfer to yourself");
        return;
      }

      if (amount > balance) {
        alert("Insufficient Balance");
        return;
      }

      // Sender
      balance -= amount;
      user.balance = balance;

      // Receiver
      recipientUser.balance += amount;

      const senderTransaction = {
        name: recipientUser.name,
        amount,
        type: "sent",
        date: new Date().toLocaleDateString(),
      };

      const receiverTransaction = {
        name: user.name,
        amount,
        type: "received",
        date: new Date().toLocaleDateString(),
      };

      transactions.unshift(senderTransaction);

      recipientUser.transactions.unshift(receiverTransaction);

      user.transactions = transactions;

      const updatedUsers = users.map(function (u) {
        if (u.email === user.email) return user;

        if (u.email === recipientUser.email) return recipientUser;

        return u;
      });

      localStorage.setItem("users", JSON.stringify(updatedUsers));

      localStorage.setItem("currentUser", JSON.stringify(user));

      balanceElement.textContent = `$${balance}`;

      renderTransactions();

      transferForm.reset();

      alert(`$${amount} sent to ${recipientUser.name}`);
    });
  }

  // ==========================
  // PROFILE UPDATE
  // ==========================

  const profileForm = document.querySelector("#profile-form");

  if (profileForm) {
    profileForm.addEventListener("submit", function (e) {
      e.preventDefault();

      user.phone = document.querySelector("#phone").value;

      user.address = document.querySelector("#address").value;

      const users = JSON.parse(localStorage.getItem("users")) || [];

      const updatedUsers = users.map(function (u) {
        return u.email === user.email ? user : u;
      });

      localStorage.setItem("users", JSON.stringify(updatedUsers));

      localStorage.setItem("currentUser", JSON.stringify(user));

      alert("Profile Updated");
    });
  }
}
const logoutBtn = document.querySelector("#logout-btn");

if (logoutBtn) {
  logoutBtn.addEventListener("click", function () {
    localStorage.removeItem("loggedIn");

    localStorage.removeItem("currentUser");

    window.location.href = "login.html";
  });
}

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

      themeToggle.textContent = " Light Mode";
    } else {
      localStorage.setItem("darkMode", "disabled");

      themeToggle.textContent = "🌙 Dark Mode";
    }
  });
}
