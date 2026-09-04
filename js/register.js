document.querySelectorAll('[data-toggle]').forEach(function (btn) {
  btn.addEventListener('click', function () {
    var input = document.getElementById(btn.dataset.toggle);
    input.type = input.type === 'password' ? 'text' : 'password';
  });
});

function showError(fieldId, message) {
  var el = document.getElementById(fieldId + '-error');
  if (el) el.textContent = message || '';
}

function clearErrors() {
  ['fullName', 'studentNumber', 'email', 'password', 'confirmPassword', 'popiaAgree'].forEach(function (id) {
    showError(id, '');
  });
  var banner = document.getElementById('formBanner');
  banner.className = 'banner';
  banner.textContent = '';
}

function validateRegisterForm() {
  var fullName = document.getElementById('fullName').value.trim();
  var studentNumber = document.getElementById('studentNumber').value.trim();
  var email = document.getElementById('email').value.trim();
  var password = document.getElementById('password').value;
  var confirmPassword = document.getElementById('confirmPassword').value;
  var popiaChecked = document.getElementById('popiaAgree').checked;

  var valid = true;

  if (!fullName || fullName.length < 2) {
    showError('fullName', 'Please enter your full name.');
    valid = false;
  }
  if (!/^\d{6,10}$/.test(studentNumber)) {
    showError('studentNumber', 'Enter a valid student number.');
    valid = false;
  }

  var expectedEmail = studentNumber + '@richfield.ac.za';
  if (!/^\d{6,10}@richfield\.ac\.za$/.test(email)) {
    showError('email', 'Email must be in the format studentnumber@richfield.ac.za');
    valid = false;
  } else if (email.toLowerCase() !== expectedEmail.toLowerCase()) {
    showError('email', 'Email must match your student number: ' + expectedEmail);
    valid = false;
  }

  if (password.length < 8) {
    showError('password', 'Password must be at least 8 characters.');
    valid = false;
  }
  if (confirmPassword !== password) {
    showError('confirmPassword', 'Passwords do not match.');
    valid = false;
  }
  if (!popiaChecked) {
    showError('popiaAgree', 'You must agree to the POPIA Privacy Policy.');
    valid = false;
  }

  return valid;
}

document.getElementById('registerForm').addEventListener('submit', function (event) {
  event.preventDefault();
  clearErrors();

  if (!validateRegisterForm()) return;

  var submitBtn = document.getElementById('submitBtn');
  submitBtn.disabled = true;
  submitBtn.textContent = 'Creating account...';

  var formData = new FormData(document.getElementById('registerForm'));

  fetch('api/demoAPI.php?action=register', {
    method: 'POST',
    body: formData
  })
    .then(function (res) { return res.json(); })
    .then(function (data) {
      var banner = document.getElementById('formBanner');
      if (data.success) {
        banner.className = 'banner success';
        banner.textContent = data.message;
        setTimeout(function () { window.location.href = 'login.php'; }, 1200);
      } else {
        banner.className = 'banner error';
        banner.textContent = data.message;
        submitBtn.disabled = false;
        submitBtn.textContent = 'Create Account';
      }
    })
    .catch(function () {
      var banner = document.getElementById('formBanner');
      banner.className = 'banner error';
      banner.textContent = 'Something went wrong. Please try again.';
      submitBtn.disabled = false;
      submitBtn.textContent = 'Create Account';
    });
});
