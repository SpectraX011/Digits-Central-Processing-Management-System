
<h2>All Event Registrations</h2>

<?php
//parent class
class RegistrationDisplay {
    public function render($row) {}
}

//child class
class StudentDisplay extends RegistrationDisplay {
    public function render($row) {
        echo "<div>
                <strong>Your Registration</strong><br>
                Event: <em>" . htmlspecialchars($row['title']) . "</em><br>
                Registered on: " . htmlspecialchars($row['registeredOn']) . "
              </div><hr>";
    }
}

//child class
class AdminDisplay extends RegistrationDisplay {
    public function render($row) {
        echo "<div style='padding:10px; border:1px solid #ccc; margin:10px 0;'>
                <strong>" . htmlspecialchars($row['fullName']) . "</strong>
                (" . htmlspecialchars($row['studentID']) . ")<br>
                Registered for: <em>" . htmlspecialchars($row['title']) . "</em><br>
                Date: " . htmlspecialchars($row['registeredOn']) . "
              </div>";
    }
}

$userRole = "admin"; 

if ($userRole === "student") {
    $display = new StudentDisplay();
} else {
    $display = new AdminDisplay();
}

//sql query fetch registration data
$query = "
  SELECT r.registrationID, r.studentID, s.fullName, e.title, r.registeredOn
  FROM registrations r
  JOIN students s ON r.studentID = s.studentID
  JOIN events e ON r.eventID = e.eventID
  ORDER BY r.registeredOn DESC
";

//executes query connection
$result = $conn->query($query);

//check if query is successful
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $display->render($row);
    }
} else {
    echo "<p>No registrations found.</p>";
}

?>
