
  // Dummy data for study groups
  const studyGroups = [
    {
      id: 1,
      unitName: "Data Structures & Algorithms",
      category: "Computer Science",
      members: 15,
      image: "/images/dsa.jpg"
    },
    {
      id: 2,
      unitName: "Business Management",
      category: "Business",
      members: 12,
      image: "/images/business-management.png"
    },
    {
      id: 3,
      unitName: "Japanese I",
      category: "Humanities",
      members: 18,
      image: "/images/japanese-i.png"
    },
    {
      id: 4,
      unitName: "Probability & Statistics",
      category: "Mathematics",
      members: 10,
      image: "https://images.unsplash.com/photo-1526045612212-70caf35c14df?auto=format&fit=crop&w=800&q=60"
    },
    {
      id: 5,
      unitName: "Ethics",
      category: "Humanities",
      members: 8,
      image: "https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=800&q=60"
    },
    {
      id: 6,
      unitName: "Object-Oriented Programming II",
      category: "Computer Science",
      members: 14,
      image: "https://images.unsplash.com/photo-1517433456452-f9633a875f6f?auto=format&fit=crop&w=800&q=60"
    },
    {
      id: 7,
      unitName: "Marketing Fundamentals",
      category: "Business",
      members: 20,
      image: "https://images.unsplash.com/photo-1556742044-3c52d6e88c62?auto=format&fit=crop&w=800&q=60"
    },
  ];

  const cardsGrid = document.getElementById('cards-grid');
  const loginModal = document.getElementById('login-modal');
  const openLoginBtn = document.getElementById('open-login-btn');
  const closeLoginBtn = document.getElementById('close-login');
  const loginForm = document.getElementById('login-form');
const signupModal = document.getElementById('signup-modal');
const closeSignupBtn = document.getElementById('close-signup');
const switchToLoginLink = document.getElementById('switch-to-login');
const switchToSignupLink = document.getElementById('switch-to-signup');
const signupForm = document.getElementById('signup-form');

// Show signup modal (optional external button)
document.getElementById('open-signup-btn')?.addEventListener('click', () => {
  signupModal.classList.add('active');
  loginModal.classList.remove('active');
});

// Close signup modal
closeSignupBtn.addEventListener('click', () => {
  signupModal.classList.remove('active');
});

// Switch back to login
switchToLoginLink.addEventListener('click', (e) => {
  e.preventDefault();
  signupModal.classList.remove('active');
  loginModal.classList.add('active');
});
switchToSignupLink.addEventListener('click', (e) => {
  e.preventDefault();
  loginModal.classList.remove('active');
  signupModal.classList.add('active');
  
});

// Signup form submission
signupForm.addEventListener('submit', e => {
  e.preventDefault();

  const studentId = document.getElementById('signup-student-id').value.trim();
  const name = document.getElementById('signup-name').value.trim();
  const course = document.getElementById('signup-course').value.trim();
  const email = document.getElementById('signup-email').value.trim();
  const password = document.getElementById('signup-password').value.trim();

  let valid = true;

  // Student ID: exactly 6 digits
  if (!/^\d{6}$/.test(studentId)) {
    document.getElementById('signup-id-error').style.display = 'block';
    valid = false;
  } else {
    document.getElementById('signup-id-error').style.display = 'none';
  }

  // Name: only letters (no digits)
  if (!/^[A-Za-z\s]+$/.test(name)) {
    document.getElementById('signup-name-error').style.display = 'block';
    valid = false;
  } else {
    document.getElementById('signup-name-error').style.display = 'none';
  }

  // Course: 3 to 5 uppercase letters
  if (!/^[A-Z]{3,5}$/.test(course)) {
    document.getElementById('signup-course-error').style.display = 'block';
    valid = false;
  } else {
    document.getElementById('signup-course-error').style.display = 'none';
  }

  // Email validation
  if (!email || !email.includes('@')) {
    document.getElementById('signup-email-error').style.display = 'block';
    valid = false;
  } else {
    document.getElementById('signup-email-error').style.display = 'none';
  }

  // Password validation
  if (!password || password.length < 6) {
    document.getElementById('signup-password-error').style.display = 'block';
    valid = false;
  } else {
    document.getElementById('signup-password-error').style.display = 'none';
  }

  if (!valid) return;

  // Simulate signup success
  loggedIn = true;
  alert("Account created successfully!");
  signupModal.classList.remove('active');
  openLoginBtn.textContent = "Logout";
  renderCards();
});


  // Simulated login state
  let loggedIn = false;
  // Keep track of joined group IDs
  let joinedGroups = new Set();

  // Render cards
  function renderCards() {
    cardsGrid.innerHTML = '';
    studyGroups.forEach(group => {
      const joined = joinedGroups.has(group.id);
      const card = document.createElement('div');
      card.classList.add('card');

      card.innerHTML = `
        <img src="${group.image}" alt="${group.unitName} image" />
        <div class="category">${group.category}</div>
        <div class="unit-name">${group.unitName}</div>
        <div class="members-count">${group.members + (joined ? 1 : 0)} members</div>
        <button class="btn-join" data-id="${group.id}" ${joined ? 'disabled' : ''}>${joined ? 'Joined' : 'Join'}</button>
      `;
      cardsGrid.appendChild(card);
    });
  }

  // Handle join clicks
  cardsGrid.addEventListener('click', e => {
    if (e.target.classList.contains('btn-join')) {
      const groupId = parseInt(e.target.dataset.id);
      if (!loggedIn) {
        openLoginModal();
        return;
      }
      if (!joinedGroups.has(groupId)) {
        joinedGroups.add(groupId);
        alert(`You joined the study group for "${studyGroups.find(g => g.id === groupId).unitName}"!`);
        renderCards();
      }
    }
  });

  // Open/close modal
  function openLoginModal() {
    loginModal.classList.add('active');
  }
  function closeLoginModal() {
    loginModal.classList.remove('active');
  }

  openLoginBtn.addEventListener('click', openLoginModal);
  closeLoginBtn.addEventListener('click', closeLoginModal);

  // Login form validation & simulation
  loginForm.addEventListener('submit', e => {
    e.preventDefault();
    const email = loginForm.email.value.trim();
    const password = loginForm.password.value.trim();
    let valid = true;

    // Email validation
    if (!email || !email.includes('@')) {
      document.getElementById('email-error').style.display = 'block';
      valid = false;
    } else {
      document.getElementById('email-error').style.display = 'none';
    }

    // Password validation
    if (!password || password.length < 6) {
      document.getElementById('password-error').style.display = 'block';
      valid = false;
    } else {
      document.getElementById('password-error').style.display = 'none';
    }

    if (!valid) return;

    // Simulate login success
    loggedIn = true;
    alert(`Logged in as ${email}`);
    closeLoginModal();
    openLoginBtn.textContent = "Logout";
  });

  // Logout toggle for demo
  openLoginBtn.addEventListener('click', () => {
    if (loggedIn) {
      if (confirm('Logout?')) {
        loggedIn = false;
        joinedGroups.clear();
        openLoginBtn.textContent = "Login / Signup";
        renderCards();
      }
    }
  });

  // Initial render
  renderCards();
