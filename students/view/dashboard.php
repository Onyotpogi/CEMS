 <!-- ============================================================== -->
<!-- Three charts -->
<!-- ============================================================== -->


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
        
            <div class="col-lg-4 col-md-6 col-sm-12">
    <!-- Today's Events Card -->
    <div class="card mb-3 shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Today's Event(s)</h5>
        </div>
        <div class="card-body">
            <?php if ($todayResult->num_rows > 0): ?>
                <?php while ($row = $todayResult->fetch_assoc()): ?>
                    <p class="mb-0 fw-bold"><?= htmlspecialchars($row['title']) ?></p>
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
                    <p class="mb-0 fw-bold"><?= htmlspecialchars($row['title']) ?></p>
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