let timerDisplay = document.getElementById("timer");
let startBtn = document.getElementById("startBtn");
let resetBtn = document.getElementById("resetBtn");
let focusBtn = document.getElementById("focusMode");
let shortBreakBtn = document.getElementById("shortBreak");
let longBreakBtn = document.getElementById("longBreak");

let countdown;
let isRunning = false;
let currentMode = "focus";

// Default durations in minutes
const durations = {
  focus: 25,
  short: 5,
  long: 15
};

// Convert minutes to mm:ss
function formatTime(minutes) {
  const mins = Math.floor(minutes);
  const secs = Math.round((minutes - mins) * 60);
  return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
}

// Convert mm:ss to seconds
function parseTime(text) {
  const [mins, secs] = text.split(":").map(Number);
  return mins * 60 + secs;
}

// Set timer display and internal time
function setTimer(minutes) {
  clearInterval(countdown);
  isRunning = false;
  timerDisplay.value = formatTime(minutes);
}

// Start timer
function startTimer() {
  if (isRunning) return;
  isRunning = true;

  let remaining = parseTime(timerDisplay.value);

  countdown = setInterval(() => {
    remaining--;
    if (remaining <= 0) {
      clearInterval(countdown);
      isRunning = false;
      alert(`${currentMode === "focus" ? "Focus session" : "Break"} complete!`);
    }
    timerDisplay.value = formatTime(remaining / 60);
  }, 1000);
}

// Reset timer to current mode
function resetTimer() {
  clearInterval(countdown);
  isRunning = false;
  setTimer(durations[currentMode]);
}

// Mode switch handlers
focusBtn.onclick = () => {
  currentMode = "focus";
  setTimer(durations.focus);
};

shortBreakBtn.onclick = () => {
  currentMode = "short";
  setTimer(durations.short);
};

longBreakBtn.onclick = () => {
  currentMode = "long";
  setTimer(durations.long);
};

// Event listeners
startBtn.onclick = startTimer;
resetBtn.onclick = resetTimer;

// Allow user to edit timer manually
timerDisplay.addEventListener("input", () => {
  clearInterval(countdown);
  isRunning = false;
});
