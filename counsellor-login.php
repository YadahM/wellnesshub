<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Counsellor Login — WellnessHub</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body class="dark-body">
  <main class="auth-screen">
    <section class="auth-card dark-login-card">
      <div class="auth-number">11. &nbsp; COUNSELLOR LOGIN</div>
      <div class="brand-mini"><span class="mini-leaf" aria-hidden="true">☘</span><span>WellnessHub</span></div>
      <header class="auth-header"><h1>Counsellor Account</h1><p>Login to continue</p></header>
      <form class="auth-form" action="counsellor-dashboard.php" method="get">
        <label class="field"><span>Email</span><input type="email" placeholder="counsellor@richfield.ac.za" required /></label>
        <label class="field password-field"><span>Password</span><input type="password" placeholder="••••••••" required /><button type="button">◎</button></label>
        <a class="forgot-link" href="#">Forgot Password?</a><button class="btn btn-blue btn-full">Login</button>
      </form>
      <p class="legal-text">Secure • Confidential • POPIA Compliant</p>
      <p class="form-footer"><a href="login.php">Student Login</a> · <a href="admin-dashboard.php">Admin Preview</a></p>
    </section>
  </main>
</body>
</html>
