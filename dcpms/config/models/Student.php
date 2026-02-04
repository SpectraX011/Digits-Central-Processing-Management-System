<?php
// Student class handles 
class Student {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    public function authenticate($studentID, $password, $role) {
        $stmt = $this->conn->prepare("SELECT * FROM students WHERE studentID = ? AND role = ?");
        $stmt->bind_param("ss", $studentID, $role);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return password_verify($password, $row['password_hash']);
        }
        return false;
    }

    public function getFullName($studentID) {
        $stmt = $this->conn->prepare("SELECT fullName FROM students WHERE studentID = ?");
        $stmt->bind_param("s", $studentID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['fullName'];
        }
        return "User";
    }
}