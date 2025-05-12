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
            <h1>Edit Calendar Information</h1>
            
            <form name="addCalendar" action="editCalendarAction.php" class="was-validated">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <label for="title" class="form-label">Calendar Title:</label>
                        <input class="form-control" placeholder="Enter title" name="title" size="40" required>
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
                <div class="row">
                    <div class="col-md-4">
                        <label for="day1" class="form-label">1st:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day1" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="day2" class="form-label">2nd:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day2" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="day3" class="form-label">3rd:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day3" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="day4" class="form-label">4th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day4" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="day5" class="form-label">5th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day5" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="day6" class="form-label">6th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day6" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="day7" class="form-label">7th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day7" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="day8" class="form-label">8th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day8" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="day9" class="form-label">9th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day9" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="day10" class="form-label">10th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day10" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="day11" class="form-label">11th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day11" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="day12" class="form-label">12th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day12" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="day13" class="form-label">13th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day13" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="day14" class="form-label">14th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day14" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="day15" class="form-label">15th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day15" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="day16" class="form-label">16th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day16" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="day17" class="form-label">17th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day17" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="day18" class="form-label">18th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day18" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="day19" class="form-label">19th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day19" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="day20" class="form-label">20th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day20" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="day21" class="form-label">21st:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day21" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="day22" class="form-label">22nd:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day22" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="day23" class="form-label">23rd:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day23" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="day24" class="form-label">24th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day24" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="day25" class="form-label">25th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day25" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="day26" class="form-label">26th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day26" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="day27" class="form-label">27th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day27" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="day28" class="form-label">28th:</label>
                        <input class="form-control" placeholder="Input data, null if none" name="day28" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="day29" class="form-label">29th:</label>
                        <input class="form-control" placeholder="Input data, null if none, non-applicable if extra date" name="day29" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="day30" class="form-label">30th:</label>
                        <input class="form-control" placeholder="Input data, null if none, non-applicable if extra date" name="day30" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="day31" class="form-label">31st:</label>
                        <input class="form-control" placeholder="Input data, null if none, non-applicable if extra date" name="day31" required>
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
        <?php 'footer.php' ?>
    </body>
</html>

