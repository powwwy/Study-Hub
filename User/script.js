document.getElementById("switch-to-signup").addEventListener("click", function () {
  document.getElementById("login-modal").classList.remove("active");
  document.getElementById("signup-modal").classList.add("active");
});

document.getElementById("switch-to-login").addEventListener("click", function () {
  document.getElementById("signup-modal").classList.remove("active");
  document.getElementById("login-modal").classList.add("active");
});

document.getElementById("close-login").addEventListener("click", function () {
  document.getElementById("login-modal").classList.remove("active");
});