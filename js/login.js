document.querySelectorAll('[data-toggle]').forEach(function (btn) {
  btn.addEventListener('click', function () {
    var input = document.getElementById(btn.dataset.toggle);
    input.type = input.type === 'password' ? 'text' : 'password';
  });
});

function setBanner(type, message) {
  var banner = document.getElementById('formBanner');
  banner.className = 'banner ' + type;
  banner.textContent = message;
}

function clearBanner() {
  var banner = document.getElementById('formBanner');
  banner.className = 'banner';
  banner.textContent = '';
}

document.getElementById('biometricBtn').addEventListener('click', function () {
  setBanner('error', 'Biometric login is not available in this demo yet.');
});

document.getElementById('loginForm').addEventListener('submit', function (event) {
  event.preventDefault();
  clearBanner();

  var submitBtn = document.getElementById('submitBtn');
  submitBtn.disabled = true;
  submitBtn.textContent = 'Logging in...';

  var formData = new FormData(document.getElementById('loginForm'));

  fetch('api/demoAPI.php?action=login', {
    method: 'POST',
    body: formData
  })
    .then(function (res) { return res.json(); })
    .then(function (data) {
      if (data.success) {
        setBanner('success', data.message);
        window.location.href = 'dashboard.php';
      } else {
        setBanner('error', data.message);
        submitBtn.disabled = false;
        submitBtn.textContent = 'Login';
      }
    })
    .catch(function () {
      setBanner('error', 'Something went wrong. Please try again.');
      submitBtn.disabled = false;
      submitBtn.textContent = 'Login';
    });
});
