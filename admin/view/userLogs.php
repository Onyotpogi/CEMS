<style>
    /* Additional styling to enhance the appearance */
#search {
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);  /* Subtle shadow around the input */
  padding-left: 30px;  /* Extra padding for the icon */
}

#search:focus {
  border-color: #007bff;  /* Blue border on focus */
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);  /* Glow effect on focus */
}

.d-flex input[type="text"] {
  transition: all 0.3s ease;  /* Smooth transition effect */
}

.d-flex input[type="text"]:focus {
  outline: none;  /* Remove default focus outline */
}

</style>

<div class="row justify-content-center">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <div class="row">
            <!-- Category Table -->
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="font-weight-bold text-dark">User Logs</h5>
                    <div class="d-flex align-items-center">
                        <input type="text" id="search" class="form-control" placeholder="Search logs..." style="border-radius: 20px; height: 40px; max-width: 250px;"/>
                    </div>
                </div>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Date and Time</th>
                    </tr>
                    </thead>
                    <tbody id="user-logs-data-body">
                    <!-- Category data will be appended here via AJAX -->
                    </tbody>
                </table>
                <nav>
                  <ul class="pagination" id="pagination"></ul>
                </nav>
            </div>
          </div>
        </div>   
      </div>
    </div>
  </div>
</div>
