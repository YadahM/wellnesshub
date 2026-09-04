<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login — WellnessHub</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    /* Supplementary styles for functional states — same convention as register.php */
    .banner{padding:12px 14px; border-radius:10px; font-size:13px; font-weight:700; margin-bottom:16px; display:none;}
    .banner.error{background:rgba(243,109,109,.12); color:#ad3434; display:block;}
    .banner.success{background:rgba(87,166,111,.15); color:#1d563b; display:block;}
  </style>
</head>
<body>
  <main class="auth-screen">
    <section class="auth-card">
      <div class="auth-number">2. &nbsp; LOGIN</div>
      <div class="brand-mini"><span class="mini-leaf" aria-hidden="true">☘</span><span>WellnessHub</span></div>
      <header class="auth-header"><h1>Welcome Back</h1><p>Login to your account</p></header>

      <div id="formBanner" class="banner"></div>

      <form class="auth-form" id="loginForm" novalidate>
        <label class="field"><span>University Email</span><input type="email" id="email" name="email" placeholder="name@richfield.ac.za" required /></label>
        <label class="field password-field"><span>Password</span><input type="password" id="password" name="password" placeholder="••••••••" required /><button type="button" aria-label="Show password" data-toggle="password">◎</button></label>
        <a class="forgot-link" href="#">Forgot Password?</a>
        <button class="btn btn-primary btn-full" type="submit" id="submitBtn">Login</button>
      </form>
      <div class="or-text">or login with</div>
      <button type="button" class="biometric-btn" id="biometricBtn"><span>◎</span> Biometric Login</button>
      <p class="legal-text">By continuing, you agree to the <a href="#">Privacy Policy</a> and <a href="#">POPIA Compliance</a>.</p>
      <p class="form-footer">Need an account? <a href="register.php">Create Account</a></p>
      <div class="auth-links">
        <a class="btn btn-ghost" href="counsellor-login.php">Counsellor Login</a>
        <a class="btn btn-ghost" href="admin-dashboard.php">Admin Preview</a>
      </div>
    </section>
  </main>

  <script src="js/login.js"></script>
</body>
</html>
