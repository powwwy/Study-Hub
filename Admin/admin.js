document.getElementById('create-group-form').addEventListener('submit', function (e) {
  e.preventDefault();

  const name = document.getElementById('group-name').value.trim();
  const category = document.getElementById('category').value;
  const units = document.getElementById('units').value.trim();
  const availability = document.getElementById('availability').value.trim();
  const members = document.getElementById('members').value.trim();

  if (!name || !category || !units || !availability) {
    showFeedback("Please fill in all required fields.", false);
    return;
  }

  // Simulate success
  showFeedback(`✅ Group "${name}" created successfully under ${category}.`, true);

  // Optionally reset form
  this.reset();
});

function showFeedback(message, success) {
  const box = document.getElementById('feedback');
  box.textContent = message;
  box.style.color = success ? 'green' : 'red';
}
