document.getElementById("switch-to-signup").addEventListener("click", function () {
  document.getElementById("login-modal").classList.remove("active");
  document.getElementById("signup-modal").classList.add("active");
});

document.getElementById("switch-to-login").addEventListener("click", function () {
  document.getElementById("signup-modal").classList.remove("active");
  document.getElementById("login-modal").classList.add("active");
});

    card.innerHTML = `
        <img src="${group.image}" alt="${group.unitName} image" />
        <div class="category">${group.category}</div>
        <div class="unit-name">${group.unitName}</div>
        <div class="members-count">${
          group.members + (joined ? 1 : 0)
        } members</div>
        <button class="btn-join" data-id="${group.id}" ${
      joined ? "disabled" : ""
    }>${joined ? "Joined" : "Join"}</button>
      `;
    cardsGrid.appendChild(card);
  });
}

// Handle join clicks
cardsGrid.addEventListener("click", (e) => {
  if (e.target.classList.contains("btn-join")) {
    const groupId = parseInt(e.target.dataset.id);
    if (!loggedIn) {
      openLoginModal();
      return;
    }
    if (!joinedGroups.has(groupId)) {
      joinedGroups.add(groupId);
      alert(
        `You joined the study group for "${
          studyGroups.find((g) => g.id === groupId).unitName
        }"!`
      );
      renderCards();
    }
  }
});

// Open/close modal
function openLoginModal() {
  loginModal.classList.add("active");
}
function closeLoginModal() {
  loginModal.classList.remove("active");
}

openLoginBtn.addEventListener("click", openLoginModal);
closeLoginBtn.addEventListener("click", closeLoginModal);

// Login form validation & simulation
loginForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const email = loginForm.email.value.trim();
  const password = loginForm.password.value.trim();
  let valid = true;

  // Email validation
  if (!email || !email.includes("@")) {
    document.getElementById("email-error").style.display = "block";
    valid = false;
  } else {
    document.getElementById("email-error").style.display = "none";
  }

  // Password validation
  if (!password || password.length < 6) {
    document.getElementById("password-error").style.display = "block";
    valid = false;
  } else {
    document.getElementById("password-error").style.display = "none";
  }

  if (!valid) return;

  // Simulate login success
  loggedIn = true;
  alert(`Logged in as ${email}`);
  closeLoginModal();
  openLoginBtn.textContent = "Logout";
});

// Logout toggle for demo
openLoginBtn.addEventListener("click", () => {
  if (loggedIn) {
    if (confirm("Logout?")) {
      loggedIn = false;
      joinedGroups.clear();
      openLoginBtn.textContent = "Login / Signup";
      renderCards();
    }
  }
});

// Initial render
renderCards();


function startTimer() {
  if (timerInterval) return;
  timerInterval = setInterval(() => {
    timeLeft--;
    updateDisplay();
    if (timeLeft <= 0) {
      clearInterval(timerInterval);
      timerInterval = null;
      alert("Pomodoro finished! Take a break 🎉");
    }
  }, 1000);
}

function resetTimer() {
  clearInterval(timerInterval);
  timerInterval = null;
  timeLeft = 25 * 60;
  updateDisplay();
}

startBtn.addEventListener("click", startTimer);
resetBtn.addEventListener("click", resetTimer);

updateDisplay(); // Initial call
// Handle opening and closing of Add Group modal
const openGroupBtn = document.getElementById("open-add-group");
const closeGroupBtn = document.getElementById("close-add-group");
const groupModal = document.getElementById("add-group-modal");
const addGroupForm = document.getElementById("add-group-form");

openGroupBtn.addEventListener("click", () => {
  groupModal.style.display = "block";
});

closeGroupBtn.addEventListener("click", () => {
  groupModal.style.display = "none";
});

window.addEventListener("click", (event) => {
  if (event.target === groupModal) {
    groupModal.style.display = "none";
  }
});

// Handle Add Group form submission
addGroupForm.addEventListener("submit", (e) => {
  e.preventDefault();

  const unitName = document.getElementById("unitName").value;
  const category = document.getElementById("category").value;
  const members = parseInt(document.getElementById("members").value);

  const newGroup = {
    id: studyGroups.length + 1,
    unitName,
    category,
    members,
    image: "https://via.placeholder.com/300x150?text=" + encodeURIComponent(unitName)
  };

  studyGroups.push(newGroup);
  addGroupForm.reset();
  groupModal.style.display = "none";
  renderCards(); // Refresh the cards
});
