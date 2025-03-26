<?php
class DatabaseConnection {
    private $username;
    private $password;
    private $dbname;
    private $host;
    private $port;
    private $conn;

    // Constructor to initialize class properties
    public function __construct() {
        $this->username = getenv('USERNAME');
        $this->password = getenv('PASSWORD');
        $this->dbname = getenv('DBNAME');
        $this->host = getenv('HOST');
        $this->port = getenv('PORT');
    }

    // Method to establish a connection to the database
    public function connect() {
        if ($this->conn === null) {
            // Create a connection string
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
            try {
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "Connection successful!";
            } catch (PDOException $e) {
                // Handle connection errors
                echo "Connection failed: " . $e->getMessage();
                return null; // Return null on failure
            }
        }
        return $this->conn; // Return the existing connection if it exists
    }
}

// Usage example:
$dbConnection = new DatabaseConnection();
$conn = $dbConnection->connect();
?>