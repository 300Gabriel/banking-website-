const registerForm = document.querySelector("#register-form");

if (registerForm) {
  registerForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const name = document.querySelector("#name").value;

    const email = document.querySelector("#email").value;

    const password = document.querySelector("#password").value;

    // Generate fake account number
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

    // Get all users
    const users = JSON.parse(localStorage.getItem("users")) || [];

    // Check existing email
    const existingUser = users.find(function (u) {
      return u.email === email;
    });

    if (existingUser) {
      alert("Email already exists");

      return;
    }

    // Add new user
    users.push(user);

    // Save users array
    localStorage.setItem("users", JSON.stringify(users));

    alert("Registration Successful");

    window.location.href = "login.html";
  });
}

const loginForm = document.querySelector("#login-form");

if (loginForm) {
  loginForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const email = document.querySelector("#login-email").value;

    const password = document.querySelector("#login-password").value;

    // Get all users
    const users = JSON.parse(localStorage.getItem("users")) || [];

    // Find matching user
    const foundUser = users.find(function (user) {
      return user.email === email && user.password === password;
    });

    if (foundUser) {
      // Save current logged in user
      localStorage.setItem("currentUser", JSON.stringify(foundUser));

      localStorage.setItem("loggedIn", "true");

      alert("Login Successful");

      window.location.href = "dashboard.html";
    } else {
      alert("Invalid Email or Password");
    }
  });
}

const welcomeMessage = document.querySelector("#welcome-message");

if (welcomeMessage) {
  const loggedIn = localStorage.getItem("loggedIn");

  // Protect dashboard
  if (!loggedIn) {
    window.location.href = "login.html";
  }

  // Current User
  const user = JSON.parse(localStorage.getItem("currentUser"));

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

  // Render Transactions
  function renderTransactions() {
    transactionsContainer.innerHTML = "<h2>Recent Transactions</h2>";

    transactions.forEach(function (transaction) {
      const transactionDiv = document.createElement("div");

      transactionDiv.classList.add("transaction");

      transactionDiv.innerHTML = `
        <p>${transaction.name}</p>
        <span>-$${transaction.amount}</span>
      `;

      transactionsContainer.appendChild(transactionDiv);
    });
  }

  renderTransactions();

  const transferForm = document.querySelector("#transfer-form");

  if (transferForm) {
    transferForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const recipient = document.querySelector("#recipient").value;

      const amount = Number(document.querySelector("#amount").value);

      // Validation
      if (amount <= 0) {
        alert("Enter valid amount");

        return;
      }

      if (amount > balance) {
        alert("Insufficient Balance");

        return;
      }

      // Deduct balance
      balance -= amount;

      // Update UI
      balanceElement.textContent = `$${balance}`;

      // Save balance
      user.balance = balance;

      // New Transaction
      const newTransaction = {
        name: recipient,
        amount: amount,
      };

      // Add to top
      transactions.unshift(newTransaction);

      // Save transactions
      user.transactions = transactions;

      // Update all users array
      const users = JSON.parse(localStorage.getItem("users")) || [];

      const updatedUsers = users.map(function (u) {
        if (u.email === user.email) {
          return user;
        } else {
          return u;
        }
      });
      localStorage.setItem("users", JSON.stringify(updatedUsers));
      localStorage.setItem("currentUser", JSON.stringify(user));
      renderTransactions();
      transferForm.reset();
    });
  }
}

// logging sectiin

const logoutBtn = document.querySelector("#logout-btn");

if (logoutBtn) {
  logoutBtn.addEventListener("click", function () {
    localStorage.removeItem("loggedIn");

    localStorage.removeItem("currentUser");

    window.location.href = "login.html";
  });
}
