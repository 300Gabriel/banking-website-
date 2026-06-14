// ==========================
// LOGIN SECTION
// ==========================

// const loginForm = document.querySelector("#login-form");

// if (loginForm) {
//   loginForm.addEventListener("submit", function (e) {
//     e.preventDefault();

//     const email = document.querySelector("#login-email").value;
//     const password = document.querySelector("#login-password").value;

//     // Get Users
//     const users = JSON.parse(localStorage.getItem("user
// s")) || [];

//     // Find User
//     const foundUser = users.find(function (user) {
//       return user.email === email && user.password === password;
//     });

//     if (foundUser) {
//       // Save Logged In User
//       localStorage.setItem("currentUser", JSON.stringify(foundUser));

//       localStorage.setItem("loggedIn", "true");

//       alert("Login Successful");

//       window.location.href = "dashboard.html";
//     } else {
//       alert("Invalid Email or Password");
//     }
//   });
// }

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

const track = document.querySelector(".testimonial-track");
const slides = document.querySelectorAll(".testimonial");

const nextBtn = document.querySelector("#nextBtn");
const prevBtn = document.querySelector("#prevBtn");

let currentSlide = 0;

if (nextBtn && prevBtn && track && slides.length > 0) {
  function updateSlider() {
    track.style.transform = `translateX(-${currentSlide * 100}%)`;
  }

  nextBtn.addEventListener("click", () => {
    currentSlide++;

    if (currentSlide >= slides.length) {
      currentSlide = 0;
    }

    updateSlider();
  });

  prevBtn.addEventListener("click", () => {
    currentSlide--;

    if (currentSlide < 0) {
      currentSlide = slides.length - 1;
    }

    updateSlider();
  });

  setInterval(() => {
    currentSlide++;

    if (currentSlide >= slides.length) {
      currentSlide = 0;
    }

    updateSlider();
  }, 5000);
}
//  this is the logging section
const togglePassword = document.querySelector("#toggle-password");

const passwordInput = document.querySelector('input[name="password"]');

if (togglePassword && passwordInput) {
  togglePassword.addEventListener("click", () => {
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
    } else {
      passwordInput.type = "password";
    }
  });
}

// ==========================
// RECEIPT
// ==========================

const closeReceipt = document.querySelector("#close-receipt");

if (closeReceipt) {
  closeReceipt.addEventListener("click", function () {
    window.location.href = "dashboard.php";
  });
}

const printReceipt = document.querySelector("#print-receipt");

if (printReceipt) {
  printReceipt.addEventListener("click", function () {
    window.print();
  });
}

const accountInput = document.querySelector("#recipient_account");

const accountName = document.querySelector("#account-name");

if (accountInput && accountName) {
  accountInput.addEventListener("keyup", async () => {
    const account = accountInput.value;

    if (account.length < 10) {
      accountName.textContent = "";
      return;
    }

    const response = await fetch(
      "../API/verify-account.php?account=" + account,
    );

    const data = await response.text();

    accountName.textContent = data;
  });
}

const transferBtn = document.getElementById("open-transfer");

if (transferBtn) {
  transferBtn.onclick = () => {
    document.getElementById("transfer-modal").style.display = "flex";
  };
}

const depositBtn = document.getElementById("open-deposit");

if (depositBtn) {
  depositBtn.onclick = () => {
    document.getElementById("deposit-modal").style.display = "flex";
  };
}

const withdrawBtn = document.getElementById("open-withdraw");

if (withdrawBtn) {
  withdrawBtn.onclick = () => {
    document.getElementById("withdraw-modal").style.display = "flex";
  };
}

const profileBtn = document.getElementById("open-profile");

if (profileBtn) {
  profileBtn.onclick = () => {
    document.getElementById("profile-modal").style.display = "flex";
  };
}

document.querySelectorAll(".close-modal").forEach((btn) => {
  btn.onclick = function () {
    this.closest(".action-modal").style.display = "none";
  };
});

const bell = document.querySelector(".notification-icon");
const dropdown = document.querySelector(".notification-dropdown");

if (bell && dropdown) {
  bell.addEventListener("click", function () {
    dropdown.classList.toggle("show");
  });

  document.addEventListener("click", function (e) {
    if (!bell.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.remove("show");
    }
  });
}
