<?php
$servername = "localhost";
$username = "user_name";
$password = "db_password";
$dbname = "db_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php

class Database {
    private string $host = 'localhost';
    private string $username = 'user_name';
    private string $password = 'db_password';
    private string $dbname = 'db_name';
    private ?mysqli $connection = null;

    public function __construct() {
        $this->connect();
    }

    private function connect(): void {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($this->connection->connect_error) {
            // Log actual error in logs, not on the screen
            error_log('Database connection error: ' . $this->connection->connect_error);
            throw new Exception('Database connection failed.');
        }
    }

    public function getConnection(): mysqli {
        if ($this->connection === null || $this->connection->connect_error) {
            $this->connect();
        }
        return $this->connection;
    }

    public function close(): void {
        if ($this->connection !== null) {
            $this->connection->close();
            $this->connection = null;
        }
    }

    public function __destruct() {
        $this->close();
    }
}
?>
