<?php
//inheritance and encapsulation

//parent class
class DatabaseRecord {
    protected $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    protected function executeQuery($query) {
        return $this->conn->query($query);
    }
}
//child class
class Sanctions extends DatabaseRecord {
    private $studentID;

    public function __construct($conn, $studentID) {
        parent::__construct($conn);
        $this->studentID = $this->sanitize($studentID);
    }

    // Basic protection against SQL injection 
    private function sanitize($value) {
        return $this->conn->real_escape_string($value);
    }

    //encapsulated to get sanction data
    private function getSanctionsData() {
        $query = "
            SELECT s.*, e.title 
            FROM sanctions s
            LEFT JOIN events e ON s.eventID = e.eventID
            WHERE s.studentID = '{$this->studentID}'
            ORDER BY 
                CASE WHEN s.resolvedOn IS NULL THEN 1 ELSE 0 END,
                s.resolvedOn DESC
        ";

        return $this->executeQuery($query);
    }

    //encapsulated to display sanctions
    public function displaySanctions() {
        $result = $this->getSanctionsData();

        echo "<div style='padding:10px; border-radius:8px;'>";

        if ($result && $result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

                $reason      = htmlspecialchars($row['reason']);
                $eventTitle  = htmlspecialchars($row['title'] ?? 'N/A');
                $status      = htmlspecialchars($row['status']);
                $penalty     = number_format($row['penaltyAmount'], 2);
                $resolvedOn  = $row['resolvedOn'] ? htmlspecialchars($row['resolvedOn']) : 'Not yet';

                echo "
                    <div style='
                        background:#f8f8f8;
                        padding:15px;
                        margin-bottom:15px;
                        border-radius:10px;
                        border:1px solid #ddd;
                    '>
                        <p><strong>Violation:</strong> $reason</p>
                        <p><strong>Event:</strong> $eventTitle</p>
                        <p><strong>Status:</strong> $status</p>
                        <p><strong>Penalty:</strong> ₱$penalty</p>
                        <p><strong>Resolved On:</strong> $resolvedOn</p>
                    </div>
                ";
            }

        } else {
            echo "<p>No sanctions found.</p>";
        }

        echo "</div>";
    }
}

$studentID = $_SESSION['studentID'] ?? null;

if ($studentID) {
    $sanctions = new Sanctions($conn, $studentID);
}
?>
<h2>Your Sanctions</h2>
<?php
$sanctions->displaySanctions();
?>
