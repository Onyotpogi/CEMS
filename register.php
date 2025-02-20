<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CEMS</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<style>
  body {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    width: 100%;
    padding: 0 10px;
  }

  body::before {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    background: url("cedar-bg.jpg");
    background-position: center;
    background-size: cover;
  }

  .password-container {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
  }

  .password-container input {
    padding-right: 35px;
    width: 100%;
  }

  .password-container .eye-icon {
    position: absolute;
    right: 10px;
    cursor: pointer;
  }
</style>
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
        <div class="password-container">
          <input type="password" id="password" name="password" required>
          <label>Enter your password</label>
          <i class="fa-solid fa-eye eye-icon" data-target="password"></i>
        </div>
      </div>

      <div class="input-field">
        <div class="password-container">
          <input type="password" id="repassword" name="repassword" required>
          <label>Re-type password</label>
          <i class="fa-solid fa-eye eye-icon" data-target="repassword"></i>
        </div>
      </div>

      <button type="submit">Register</button>
      <div class="register">
        <p>You have an account? <a href="index.php">Login</a></p>
      </div>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script>
    $(document).ready(function() {
      // Toggle password visibility
      $('.eye-icon').on('click', function() {
        const target = $(this).data('target');
        const input = $('#' + target);
        if (input.attr('type') === 'password') {
          input.attr('type', 'text');
          $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
          input.attr('type', 'password');
          $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        }
      });

      // AJAX form submission
      $('#registerForm').on('submit', function(e) {
        e.preventDefault();
        var name = $('#name').val();
        var username = $('#username').val();
        var password = $('#password').val();
        var repassword = $('#repassword').val();

        if (password !== repassword) {
          toastr.warning("Passwords do not match!", 'Warning', { timeOut: 2000 });
          return;
        }

        $.ajax({
          url: 'registerPro.php',
          type: 'POST',
          data: { name: name, username: username, password: password },
          success: function(response) {
            toastr.success(response, 'Success', { timeOut: 2000 });
            setTimeout(() => {
              window.location.href = 'login.php';
            }, 2000);
            $('#registerForm')[0].reset();
          },
          error: function() {
            alert("Error registering user.");
          }
        });
      });
    });
  </script>
</body>
</html>
