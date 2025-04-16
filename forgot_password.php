<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Forgot Password | WorkOrganizer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html, body {
      height: 100%;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background: linear-gradient(to right, #717e91, #736f79);
    }

    .container-wrapper {
      min-height: 80vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 1rem;
    }

    .login-box {
      background-color: #ffffff;
      padding: 2.5rem;
      border-radius: 1rem;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .login-box h2 {
      margin-bottom: 1.5rem;
      font-weight: 600;
      color: #333;
    }

    .form-label {
      font-weight: 500;
      margin-bottom: 0.5rem;
      display: block;
      text-align: left;
    }

    .form-control {
      margin-bottom: 1.2rem;
      padding: 0.75rem;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 0.5rem;
    }

    button[type="submit"] {
      width: 100%;
      padding: 0.75rem;
      background-color: #0d6efd;
      color: white;
      font-size: 1rem;
      font-weight: 500;
      border: none;
      border-radius: 0.5rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover {
      background-color: #084db3;
    }
  </style>
</head>
<body style="background: linear-gradient(135deg, #c0d6e4, #f0f4f8); min-height: 100vh;">

  <!-- Navbar -->
  <nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="homepage.php">WorkOrganizer</a>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container-wrapper">
    <div class="login-box">
      <h2>Forgot Password</h2>
      <form method="POST" action="forgot_password.php">
        <label for="email" class="form-label">Enter your email address:</label>
        <input type="email" name="email" id="email" class="form-control" required>
        <button type="submit">Submit</button>
      </form>
      <div class="mt-3">
        <a href="login.php" class="text-decoration-none">Back to Login</a>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="text-center p-3 bg-light">
    <p>&copy; 2025 WorkOrganizer. All rights reserved.</p>
  </footer>

</body>
</html>
