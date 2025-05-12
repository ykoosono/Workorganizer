
<!DOCTYPE html>
<html lang="en">
<head>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    html, body {
      height: 100%;
    }
    body {
      display: flex;
      flex-direction: column;
    }
    main {
      flex: 1 0 auto; /* Allows main content to grow */
    }
    footer {
      flex-shrink: 0; /* Prevents footer from shrinking */
    }
  </style>
</head>
<body>
<html>
    <head>
        <title>Add Calendar</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <?php include 'header.php' ?>
    </head>
    
    <body style="background: linear-gradient(135deg, #c0d6e4, #f0f4f8); min-height: 100vh;">
        <div class="container fluid">
            <h1>Add Calendar</h1>
            

            <form name="addCalendar" action="addCalendarAction.php" class="was-validated">
                <div class="row">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Calendar Title:</label>
                        <input class="form-control" placeholder="Enter title" name="title" size="40" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="desc" class="form-label">Description:</label>
                        <input class="form-control" placeholder="Enter description" name="desc" size="40" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="month" class="form-label">Month:</label>
                        <input class="form-control" placeholder="Enter month" name="month" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="year" class="form-label">Year:</label>
                        <input class="form-control" placeholder="Enter year" name="year" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
                <div class="item">
                    <input type="submit" value="Submit" />
                    <input type="reset" value="Reset" />
                </div>
                
            </form>
            
            
        </div>
        <?php include 'footer.php' ?>
    </body>
</html>


