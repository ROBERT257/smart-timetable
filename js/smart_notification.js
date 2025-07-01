// smart_notification.js

function showLoader() {
  const btn = document.querySelector('button[type="submit"]');
  if (btn) {
    btn.disabled = true;
    btn.innerHTML = "⏳ Sending...";
  }
}

function confirmAllStudents(checkbox) {
  if (checkbox.checked) {
    const confirmSend = confirm("Are you sure you want to send this message to ALL students?");
    if (!confirmSend) checkbox.checked = false;
  }
}

function validateForm() {
  const msg = document.querySelector("textarea[name='message']");
  const appPass = document.querySelector("input[name='app_password']");

  if (!msg.value.trim()) {
    alert("Please write a message before sending.");
    return false;
  }

  if (!appPass.value.trim()) {
    alert("Gmail App Password is required.");
    return false;
  }

  return true;
}

document.addEventListener("DOMContentLoaded", function () {
  setTimeout(() => {
    const msg = document.querySelector('.msg');
    if (msg) msg.style.display = 'none';
  }, 5000);
});
