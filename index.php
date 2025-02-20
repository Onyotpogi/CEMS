<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CEMS</title>
  <link rel="icon" type="image/png" sizes="16x16" href="plugins/images/cems-logo.png">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
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
      background: url("cedar-bg.jfif");
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
</head>
<body>

  <div class="wrapper">
    <form id="loginForm" data-action="login.php">
      <h2>Login</h2>

      <div class="input-field">
        <input type="text" id="username" required>
        <label>Enter your username</label>
      </div>

      <div class="input-field">
        <div class="password-container">
          <input type="password" id="password" required>
          <label>Enter your password</label>
          <i class="fa-solid fa-eye eye-icon" id="togglePassword"></i>
        </div>
      </div>

      

      <button type="submit">Log In</button>

      <div class="register">
        <p>Don't have an account? <a href="register.php">Register</a></p>
      </div>
    </form>
    <div id="responseMessage"></div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <script>
    $(document).ready(function () {
      $('#loginForm').on('submit', function (e) {
        e.preventDefault(); // Prevent form submission

        $('#responseMessage').html(''); // Clear previous messages

        const username = $('#username').val();
        const password = $('#password').val();
        const remember = $('#remember').is(':checked') ? 1 : 0;

        $.ajax({
          url: $(this).data('action'),
          type: 'POST',
          data: { username, password, remember },
          success: function (response) {
            const res = JSON.parse(response);
            if (res.success) {
              toastr.success(res.message, 'Success', { timeOut: 3000 });

              setTimeout(() => {
                if (res.role === 'Admin') {
                  window.location.href = 'admin/index.php?link=dashboard';
                } else if (res.role === 'Student') {
                  window.location.href = 'students/index.php?link=dashboard';
                }
              }, 3000);
            } else {
              $('#responseMessage').html('<p style="color: red;">' + res.message + '</p>');
            }
          },
          error: function () {
            $('#responseMessage').html('<p style="color: red;">Something went wrong!</p>');
          },
        });
      });

      // Password toggle functionality
      $('#togglePassword').on('click', function () {
        const passwordInput = $('#password');
        if (passwordInput.attr('type') === 'password') {
          passwordInput.attr('type', 'text');
          $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
          passwordInput.attr('type', 'password');
          $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        }
      });
    });
  </script>

</body>
</html>
