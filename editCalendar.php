<?php
require "DBConnect.php";
session_start();

//$calendarID = $_GET[calendarID];
//$_SESSION['calendar_id'] = $calendarID;
//$calendarID = (int) $_SESSION['calendar_id'];
$calendarID = isset($_GET['id']) ? intval($_GET['id']) : null;
$_SESSION['calendar_id'] = $calendarID;

$sql = "select * from calendars where id='$calendarID'";

$result = queryDB($sql);

if ($result->num_rows > 0)
{
    $row = $result->fetch_assoc();
    
    $title = $row["title"];
    $desc = $row["description"];
}

?>

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
        <div id="wrap">
        <main class="flex-grow-1">
    <div class="container my-5">
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
          <div class="card p-4 shadow-lg rounded">
            <h1>Edit Calendar Information</h1>
            
            <form name="addCalendar" action="editCalendarAction.php" class="was-validated">
                <div class="row"></div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Calendar Title:</label>
                        <input class="form-control" value="<?php echo $title; ?>" name="title" size="40" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="desc" class="form-label">Description:</label>
                        <input class="form-control" value="<?php echo $desc ?>" name="desc" size="40" required>
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
          </div>
        </div>
    </div>
        </main>
        </div>
        <div id="main"></div>
        <?php 'footer.php' ?>
    </body>
</html>

