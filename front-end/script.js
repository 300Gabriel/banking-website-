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
//     const users = JSON.parse(localStorage.getItem("users")) || [];

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
    document.querySelector("#receipt-modal").style.display = "none";
  });
}

const printReceipt = document.querySelector("#print-receipt");

if (printReceipt) {
  printReceipt.addEventListener("click", function () {
    window.print();
  });
}
