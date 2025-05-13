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
    <div id="wrap">
  <!-- Sign Up Form -->
  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card p-4 shadow-lg rounded">
          <h2 class="text-center mb-4">Sign Up</h2>
          <form name="signup" action="signupAction.php" class="was-validated">
            <div class="mb-3">
              <label for="user" class="form-label">Full Name</label>
              <input class="form-control" name="user" required>
              <div class="valid-feedback">Valid.</div>
              <div class="invalid-feedback">Please fill out this field.</div>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email Address</label>
              <input type="email" class="form-control" name="email" required>
              <div class="valid-feedback">Valid.</div>
              <div class="invalid-feedback">Please fill out this field.</div>
            </div>

            <div class="mb-3">
              <label for="pswd" class="form-label">Password</label>
              <input type="password" class="form-control" name="pswd" required>
              <div class="valid-feedback">Valid.</div>
              <div class="invalid-feedback">Please fill out this field.</div>
            </div>

            <div class="mb-3">
              <label for="pswd2" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" name="pswd2" required>
              <div class="valid-feedback">Valid.</div>
              <div class="invalid-feedback">Please fill out this field.</div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Create Account</button>
          </form>

          <div class="mt-3 text-center">
            Already have an account? <a href="login.php">Log in</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="main"></div>
    </div>
    <?php include 'footer.php' ?>
</body>
</html>
