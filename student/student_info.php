<!DOCTYPE html>
<!-- Coding By CodingNepal - www.codingnepalweb.com -->
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CEMS</title>
  <link rel="stylesheet" href="../style.css">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body>
  <div class="wrapper">
  <form id="registerForm" method="POST">
      <h2>Register</h2>
      <div class="input-field">
        <input type="text" id="name" name="name" required>
        <label>Enter your name</label>
      </div>
      <div class="input-field">
        <input type="text" id="username" name="username" required>
        <label>Enter your Username</label>
      </div>
      
      <div class="input-field">
        <input type="password" id="password" name="password" required>
        <label>Enter your password</label>
      </div>

      <div class="input-field">
        <input type="password" id="repassword" name="repassword" required>
        <label>Re-type password</label>
      </div>

      <button type="submit">Register</button>
      <div class="register">
        <p>You have an account? <a href="index.php">Login</a></p>
      </div>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <!-- <script>
    $(document).ready(function() {
      // AJAX form submission
      $('#registerForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        var name = $('#name').val();
        var username = $('#username').val();
        var password = $('#password').val();
        var repassword = $('#repassword').val();

        // Simple validation for matching passwords
        if (password !== repassword) {
          
          toastr.warning("Passwords do not match!", 'Warning', { timeOut: 3000 });
          return;
        }

        $.ajax({
          url: 'registerPro.php', // PHP script to process registration
          type: 'POST',
          data: {
            name: name,
            username: username,
            password: password
          },
          success: function(response) {
            // Handle success (display success message)
            toastr.success(response, 'Success', { timeOut: 3000 });
            $('#registerForm')[0].reset(); // Reset form
          },
          error: function() {
            // Handle error
            alert("Error registering user.");
          }
        });
      });
    });
  </script> -->
</body>
</html>