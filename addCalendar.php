<?php include 'header.php'; ?>
    <body style="background: linear-gradient(135deg, #c0d6e4, #f0f4f8); min-height: 100vh;">
        <div id="wrap">
        <main class="flex-grow-1">
    <div class="container my-5">
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
          <div class="card p-4 shadow-lg rounded">
            <h1>Add Calendar</h1>
            

            <form name="addCalendar" action="addCalendarAction.php" class="was-validated">
                <div class="row"></div>
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
                <div class="item">
                    <input type="submit" value="Submit" />
                    <input type="reset" value="Reset" />
                </div>
                
            </form>
            
          </div>
        </div>
        </div>
        </main>
        </div>
        <div id="main"></div>
        <?php include 'footer.php' ?>
    </body>


