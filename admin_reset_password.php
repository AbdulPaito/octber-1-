<?php
session_start();
include 'database.php'; // Include your database connection file

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        $username = $_SESSION['username']; // Get the username from session
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            $error_message = "New password and confirm password do not match.";
        } else {
            // Update the password in the database
            $stmt = $conn->prepare("UPDATE admins SET password_admin = ? WHERE username_admin = ?");
            if ($stmt === false) {
                die("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("ss", $new_password, $username);
            if ($stmt->execute()) {
                $success_message = "Password has been updated successfully.";
                header('Location: login.php'); // Redirect to admin login page
                exit();
            } else {
                die("Execute failed: " . $stmt->error);
            }
        }
    } else {
        $error_message = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Admin Password</title>
  <link rel="stylesheet" href="log.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    .error-message {
      color: red;
      font-weight: bold;
    }
    .success-message {
      color: green;
      font-weight: bold;
    }
    .inputBox {
      text-align: center;
      border-radius: 20px;
      margin-top: 10px;
    }
    .inputBox input {
      padding: 10px;
      width: 60%;
      border-radius: 20px;
    }
    .inputBox1 input {
      padding: 10px;
      width: 60%;
      text-align: center;
      border-radius: 20px;
    }
    .inputBox1 {
      text-align: center;
      border-radius: 20px;
      margin-top: -10px;
    }
    .eye-icon {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #aaa; /* Icon color */
      font-size: 20px; /* Adjust size */
    }
    .eye-icon.active {
      color: #000; /* Icon color when active */
    }
  </style>
</head>
<body>
  <section>
    <div class="signin">
      <div class="content">
        <h2>Reset Admin Password</h2>
        <?php if (!empty($error_message)): ?>
          <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <?php if (!empty($success_message)): ?>
          <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <form class="form" action="admin_reset_password.php" method="POST">

          <div class="inputBox">
            <input type="password" id="new_password" name="new_password" placeholder="New Password" required>
            <i class="fa fa-eye eye-icon" id="toggleNewPassword" onclick="togglePassword('new_password', 'toggleNewPassword')"></i>
          </div>

          <div class="inputBox">
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
            <i class="fa fa-eye eye-icon" id="toggleConfirmPassword" onclick="togglePassword('confirm_password', 'toggleConfirmPassword')"></i>
          </div>

          <div class="inputBox1">
            <input type="submit" value="Reset Password">
          </div>
        </form>
      </div>
    </div>
  </section>

  <script>
    function togglePassword(fieldId, iconId) {
      var passwordField = document.getElementById(fieldId);
      var eyeIcon = document.getElementById(iconId);
      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.classList.add('active');
      } else {
        passwordField.type = 'password';
        eyeIcon.classList.remove('active');
      }
    }
  </script>

</body>
</html>
