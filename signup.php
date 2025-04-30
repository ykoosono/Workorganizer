<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sign Up | WorkOrganizer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <?php include 'header.php' ?>
</head>
<body style="background: linear-gradient(135deg, #c0d6e4, #f0f4f8); min-height: 100vh;">

  <!-- Sign Up Form -->
  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="card p-4 shadow-lg rounded">
          <h2 class="text-center mb-3">Sign Up</h2>
          <p class="text-center mb-4">Please complete the form to create an account.</p>

          <form name="signup" action="signupAction.php" class="was-validated">
                <div class="row">    
                    <div class="col-sm-6">
                        <label for="user" class="form-label">Username:</label>
                        <input class="form-control" placeholder="Enter username" name="user" size="40" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                     <div class="col-sm-6">
                        <label for="pswd" class="form-label">Password:</label>
                        <input type="password" class="form-control" placeholder="Enter password" name="pswd" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email:</label>
                        <input class="form-control" placeholder="Email address" name="email" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="pswd2" class="form-label">Confirm Password:</label>
                        <input type="password" class="form-control" placeholder="Confirm password" name="pswd2" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
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

    <?php include 'footer.php' ?>

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
