<?php include 'header.php'; ?>

<div class="d-flex flex-column min-vh-100">
  <main class="flex-grow-1">
    <div class="container mt-5">
      <h2 class="mb-4">My Calendars</h2>

      <!-- Functional Buttons -->
      <div class="row mb-4 align-items-center">
        <div class="col-md-4 mb-2 mb-md-0">
          <a href="addCalendar.php" class="btn btn-success w-100">
            <i class="bi bi-plus-circle"></i> Add New Calendar
          </a>
        </div>

        <div class="col-md-4 mb-2 mb-md-0">
          <div class="dropdown w-100">
            <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              Sort Calendars
            </button>
            <ul class="dropdown-menu w-100">
              <li><a class="dropdown-item" href="?sort=recent">Most Recent</a></li>
              <li><a class="dropdown-item" href="?sort=alpha">Alphabetically</a></li>
            </ul>
          </div>
        </div>

        <div class="col-md-4">
          <form class="d-flex" action="search-calendars.php" method="get">
            <input class="form-control me-2" type="search" name="query" placeholder="Search calendars..." aria-label="Search">
            <button class="btn btn-primary" type="submit">Search</button>
          </form>
        </div>
      </div>

      <!-- Calendars grid -->
      <div class="row row-cols-1 row-cols-md-3 g-4">
        <!-- Example calendar card -->
        <div class="col">
          <div class="card shadow-sm h-100">
            <div class="card-body">
              <h5 class="card-title">Team Project Calendar</h5>
              <p class="card-text">Track milestones and deadlines for our current project.</p>
              <a href="editCalendar.php" class="btn btn-primary">View Calendar</a>
            </div>
          </div>
        </div>

        <!-- Another calendar card -->
        <div class="col">
          <div class="card shadow-sm h-100">
            <div class="card-body">
              <h5 class="card-title">Marketing Events</h5>
              <p class="card-text">All key marketing campaign dates and events.</p>
              <a href="#" class="btn btn-primary">View Calendar</a>
            </div>
          </div>
        </div>

        <!-- Add more dynamic cards here -->
      </div>
    </div>
  </main>

  <?php include 'footer.php'; ?>
</div>
