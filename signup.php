<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sign Up | WorkOrganizer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body style="background: linear-gradient(135deg, #c0d6e4, #f0f4f8); min-height: 100vh;">

  <!-- Header -->
  <nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="homepage.php">WorkOrganizer</a>
    </div>
  </nav>

  <!-- Sign Up Form -->
  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="card p-4 shadow-lg rounded">
          <h2 class="text-center mb-3">Sign Up</h2>
          <p class="text-center mb-4">Please complete the form to create an account.</p>

          <form name="signup" action="signupAction.php" method="POST" class="needs-validation" novalidate>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="user" class="form-label">Username</label>
                <input type="text" class="form-control" name="user" placeholder="Enter username" required>
                <div class="invalid-feedback">Please enter a username.</div>
              </div>
              <div class="col-md-6">
                <label for="pswd" class="form-label">Password</label>
                <input type="password" class="form-control" name="pswd" placeholder="Enter password" required>
                <div class="invalid-feedback">Please enter a password.</div>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" placeholder="Email address" required>
                <div class="invalid-feedback">Please enter a valid email.</div>
              </div>
              <div class="col-md-6">
                <label for="pswd2" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="pswd2" placeholder="Confirm password" required>
                <div class="invalid-feedback">Please confirm your password.</div>
              </div>
            </div>

            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary">Submit</button>
              <button type="reset" class="btn btn-secondary">Reset</button>
            </div>

            <div class="text-center mt-3">
              <p>Already have an account? <a href="login.php">Sign in</a></p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="text-center mt-5 p-3 bg-light">
    <p>&copy; 2025 WorkOrganizer. All rights reserved.</p>
  </footer>

  <!-- Bootstrap validation script -->
  <script>
    (() => {
      'use strict'
      const forms = document.querySelectorAll('.needs-validation')
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }
          form.classList.add('was-validated')
        }, false)
      })
    })()
  </script>
</body>
</html>
