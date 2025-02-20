
<?php

// Fetch today's events
$todayQuery = "SELECT 
    `event_id`, 
    `title`, 
    `description`, 
    `date_from`, 
    `date_to`
FROM 
    `events`
WHERE 
    CURDATE() >= date(`date_from`) AND CURDATE() <= date(`date_to`)";
$todayResult = $conn->query($todayQuery);

// Fetch upcoming events
$upcomingQuery = "SELECT `event_id`, `title`, `description`, `date_from`, date_to 
                  FROM `events` 
                  WHERE date(`date_from`) > CURDATE() limit 1";
$upcomingResult = $conn->query($upcomingQuery);
?>
<style>
  .white-box {
    background: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease-in-out;
  }

  .white-box:hover {
    transform: translateY(-5px);
    box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.15);
  }

  .box-title {
    font-weight: bold;
    font-size: 18px;
    color: #333;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }

  .icon-box {
    font-size: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
  }

  .counter {
    font-size: 24px;
    font-weight: bold;
  }
</style>
<div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Three charts -->
        <!-- ============================================================== -->
        <!-- Font Awesome for icons -->
        <div class="row justify-content-center">
            <!-- Students -->
            <div class="col-lg-4 col-md-12">
                <div class="white-box">
                <div class="icon-box text-success">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h3 class="box-title">Students</h3>
                <span class="counter text-success">659</span>
                </div>
            </div>

            <!-- Events -->
            <div class="col-lg-4 col-md-12">
                <div class="white-box">
                <div class="icon-box text-purple">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3 class="box-title">Events</h3>
                <span class="counter text-purple">869</span>
                </div>
            </div>

            <!-- Users -->
            <div class="col-lg-4 col-md-12">
                <div class="white-box">
                <div class="icon-box text-info">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="box-title">Users</h3>
                <span class="counter text-info">911</span>
                </div>
            </div>
            </div>


        <!-- ============================================================== -->
        <!-- PRODUCTS YEARLY SALES -->
        <!-- ============================================================== -->
        <div class="row justify-content-center">
            <section class="event-section text-center" id="events">
                
                <div class="row">
                    <div class="col-lg-8">
                        <div class="container px-4 px-lg-5 ">
                            <div class="row gx-4 gx-lg-5 justify-content-center">
                                <div class="white-box">
                                    <h2 class="mb-4">Events Calendar</h2>
                                    <!-- Calendar placeholder -->
                                    <div id="eventsCalendar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-sm-4">
                        <!-- Today's Events Card -->
                        <div class="card mb-3 shadow">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Today's Event(s)</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($todayResult->num_rows > 0): ?>
                                    <?php while ($row = $todayResult->fetch_assoc()): ?>
                                        <p class="mb-0"><strong><?= htmlspecialchars($row['title']) ?></strong></p>
                                        <p class="text-muted"><?= htmlspecialchars($row['description']) ?></p>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p class="text-muted">No events scheduled</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Upcoming Events Card -->
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Upcoming Event(s)</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($upcomingResult->num_rows > 0): ?>
                                    <?php while ($row = $upcomingResult->fetch_assoc()): ?>
                                        <p class="mb-0"><strong><?= htmlspecialchars($row['title']) ?></strong></p>
                                        <p class="text-muted"><?= htmlspecialchars($row['description']) ?></p>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($row['date_from']) ?> - <?= htmlspecialchars($row['date_to']) ?>
                                        </small>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p class="text-muted">No events scheduled</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>



                </div>
            </section>
        </div>
        <!-- ============================================================== -->
        <!-- EVENT RATINGS-->
        <!-- ============================================================== -->
        <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="white-box">
                <div class="d-md-flex mb-3">
                <h3 class="box-title mb-0">
                    <i class="fa fa-star text-warning"></i> Events Ratings
                </h3>
                    <div class="col-md-3 col-sm-4 col-xs-6 ms-auto">
                    <select id="monthSelect">
                        <option value="">Select Month</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>

                    <select id="yearSelect">
                        <option value="">Select Year</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                    </select>
                    </div>
                </div>
                <div class="chart-container">
                    <h3 class="text-center">Events Rating Overview</h3>
                    <canvas id="myBarChart"></canvas>
                </div>
            </div>
          </div>
        </div>
        
      </div>