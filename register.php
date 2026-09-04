<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register — WellnessHub</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    /* Supplementary styles: functional states (banners, inline field errors)
       that aren't part of the team's original style.css. Kept separate so
       the shared stylesheet isn't modified directly. */
    .banner{padding:12px 14px; border-radius:10px; font-size:13px; font-weight:700; margin-bottom:16px; display:none;}
    .banner.error{background:rgba(243,109,109,.12); color:#ad3434; display:block;}
    .banner.success{background:rgba(87,166,111,.15); color:#1d563b; display:block;}
    .field-error{color:#ad3434; font-size:12px; font-weight:650; min-height:16px;}
  </style>
</head>
<body>
  <main class="auth-screen">
    <section class="auth-card register-card">
      <div class="auth-number">3. &nbsp; REGISTER</div>
      <div class="brand-mini"><span class="mini-leaf" aria-hidden="true">☘</span><span>WellnessHub</span></div>
      <header class="auth-header"><h1>Create Account</h1><p>Join WellnessHub</p></header>

      <div id="formBanner" class="banner"></div>

      <form class="auth-form" id="registerForm" novalidate>

        <label class="field">
          <span>Full Name</span>
          <input type="text" id="fullName" name="full_name" placeholder="Sarah Naidoo" required minlength="2" />
          <div class="field-error" id="fullName-error"></div>
        </label>

        <!-- NOTE: added — not in the original register.html, but required by the
             users table (student_number UNIQUE NOT NULL) and the email-match rule. -->
        <label class="field">
          <span>Student Number</span>
          <input type="text" id="studentNumber" name="student_number" placeholder="e.g 402311336" required pattern="\d{6,10}" />
          <div class="field-error" id="studentNumber-error"></div>
        </label>

        <label class="field">
          <span>University Email</span>
          <input type="email" id="email" name="email" placeholder="e.g. 402311336@richfield.ac.za" required
                 pattern="^\d{6,10}@richfield\.ac\.za$" title="Email must be your student number followed by @richfield.ac.za" />
          <div class="field-error" id="email-error"></div>
        </label>

        <label class="field password-field">
          <span>Password</span>
          <input type="password" id="password" name="password" placeholder="••••••••" required minlength="8" />
          <button type="button" aria-label="Show password" data-toggle="password">◎</button>
          <div class="field-error" id="password-error"></div>
        </label>

        <label class="field password-field">
          <span>Confirm Password</span>
          <input type="password" id="confirmPassword" name="confirm_password" placeholder="••••••••" required />
          <button type="button" aria-label="Show password" data-toggle="confirmPassword">◎</button>
          <div class="field-error" id="confirmPassword-error"></div>
        </label>

        <label class="check-row">
          <input type="checkbox" id="popiaAgree" name="popiaAgree" />
          <span>I agree to the POPIA Privacy Policy</span>
        </label>
        <div class="field-error" id="popiaAgree-error"></div>

        <button class="btn btn-primary btn-full" type="submit" id="submitBtn">Create Account</button>
      </form>
      <p class="form-footer">Already have an account? <a href="login.php">Login</a></p>
    </section>
  </main>

  <script src="js/register.js"></script>
</body>
</html>
