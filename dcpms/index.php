<?php
session_start();
require_once 'config/db.php';
require_once 'config/models/Student.php';

$student = new Student($conn);

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentID = htmlspecialchars(trim($_POST['studentID']));
    $password = trim($_POST['password']);
    $role = htmlspecialchars(trim($_POST['role']));

    if ($student->authenticate($studentID, $password, $role)) {
        $_SESSION['studentID'] = $studentID;
        $_SESSION['role'] = $role;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid credentials.";
    }
}

$loggedIn = isset($_SESSION['studentID']);
$role = $_SESSION['role'] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
  <title>DCPMS</title>
  <style>
body {
  margin: 0;
  font-family: 'Segoe UI', sans-serif;
  background: url('assets/login-bg.jpg') no-repeat center center fixed;
  background-size: cover;
  color: #333;
}

  .container {
    padding: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
  }

  .login-form {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 0 20px rgba(0,0,0,0.2);
    max-width: 400px;
    width: 100%;
    text-align: center;
  }

  .login-form h2 {
    margin-bottom: 10px;
    font-size: 24px;
    color: #1080e3ff;
  }

  .login-form p {
    margin-bottom: 20px;
    font-size: 14px;
    color: #666;
  }

  input[type="text"],
input[type="password"],
select {
  width: 90%;
  max-width: 350px;
  padding: 12px;
  margin: 10px auto;
  display: block;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 14px;
}

button {
  width: 90%;
  max-width: 350px;
  padding: 12px;
  margin: 10px auto;
  display: block;
  background-color: #0c51e6ff;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.3s ease;
}

  button:hover {
    background-color: #0a17c7ff;
  }

  .forgot {
    display: block;
    margin-top: 10px;
    font-size: 12px;
    color: #007bff;
    text-decoration: none;
  }

  .forgot:hover {
    text-decoration: underline;
  }

.logo {
  margin-bottom: 20px;
  text-align: center;
}
.logo img {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
}

  .org-name {
    font-weight: bold;
    font-size: 16px;
    margin-bottom: 10px;
    color: #333;
  }

  .footer {
    text-align: center;
    font-size: 12px;
    color: white;
    margin-top: 20px;
  }
/* Dashboard layout */
.dashboard {
  display: flex;
  min-height: 100vh;
}

.sidebar {
  width: 250px;
  background: #14213d;
  color: white;
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  box-shadow: 0 0 10px rgba(0,0,0,0.3);
}

.sidebar .logo {
  margin-bottom: 20px;
}

.sidebar .logo img {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
}

.sidebar .role-label {
  font-size: 16px;
  font-weight: bold;
  color: #fca311;
  margin-bottom: 30px;
  text-align: center;
}

.nav-links {
  width: 100%;
  display: flex;
  flex-direction: column;
}

.nav-links a {
  color: #fca311;
  text-decoration: none;
  padding: 10px 15px;
  margin: 5px 0;
  border-radius: 6px;
  transition: background 0.3s ease;
}

.nav-links a:hover {
  background: #1f3a5c;
}

.main-content {
  flex-grow: 1;
  padding: 40px;
  background: rgba(255,255,255,0.9);
  color: #14213d;
}
</style>
</head>
<body>

<?php if ($loggedIn): ?>
  <div class="dashboard">
  <div class="sidebar">
    <div class="logo">
      <img src="assets/digits-logo.png" alt="DIGITS Logo">
    </div>
    <div class="role-label"><?= ucfirst($role) ?> Dashboard</div>
    <div class="nav-links">
        
      <?php if ($role === 'student'): ?>
<a href="index.php?page=events">Events</a>
<a href="index.php?page=register">Register</a>
<a href="index.php?page=sanctions">Sanctions</a>
       
      <?php elseif ($role === 'digit_officer'): ?>
<a href="index.php?page=manage_events">Manage Events</a>
<a href="index.php?page=registrations">Registrations</a>
<a href="index.php?page=sanctions">Sanctions</a>
      <?php endif; ?>
      <a href="?logout=true">Logout</a>
    </div>
  </div>

  <div class="main-content">
  <?php
    $page = $_GET['page'] ?? 'home';

    if ($page === 'home') {
      echo "<h2>Welcome, " . htmlspecialchars($student->getFullName($_SESSION['studentID'])) . "!</h2>";
      echo "<p>Select a module from the navigation bar to begin.</p>";
    }

    // student modules
    if ($role === 'student') {
      if ($page === 'events') include 'modules/events.php';
      if ($page === 'register') include 'modules/register.php';
      if ($page === 'sanctions') include 'modules/sanctions.php';
    }

    //officer modules
    if ($role === 'digit_officer') {
      if ($page === 'manage_events') include 'modules/manage_events.php';
      if ($page === 'registrations') include 'modules/registrations.php';
      if ($page === 'sanctions') include 'modules/sanctions_officer.php'; // officer view
    }
  ?>
</div>

<?php else: ?>
  <div class="container">
    <div class="login-form">
  <div class="logo">
    <img src="assets/digits-logo.png" alt="DIGITS Logo" style="width:100px; height:100px; border-radius:50%; object-fit:cover;">
  </div>
      <h2>Welcome Back</h2>
      <h4>please login to your account</h4>
      <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
      <form method="POST">
        <input type="text" name="studentID" placeholder="Student ID" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role" required>
          <option value="">Select Role</option>
          <option value="student">Student</option>
          <option value="digit_officer">Digit Officer</option>
        </select>
        <button type="submit">Login</button>
      </form>
    </div>
  </div>
<?php endif; ?>

</body>
</html>