<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="mystyles.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    </head>

    <body>
        <div class="container fluid">
            <h1>Sign Up Form</h1>
            <p>Please complete the sign up form.</p>

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
                <div class="item">
                    <input type="submit" value="Submit" />
                    <input type="reset" value="Reset" />
                </div>
                <div class="container sighin">
                    <p>Already have an account? <a href="login.php" class="boxed2">Sign in</a></p>
                </div>


            </form>


        </div>


    </body>

</html>