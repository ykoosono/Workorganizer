<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="refresh" content="2;url=Index.php" />
  <title>Signing Out...</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100" style="background-color: #f8f9fa;">
  <div class="text-center">
    <h3 class="mb-3">Signing you out...</h3>
    <div class="spinner-border text-danger" role="status"></div>
    <p class="mt-3">Redirecting to homepage.</p>
  </div>
</body>
</html>

