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
        // Fetch environment variables for connection
        $this->username = getenv('USERNAME');
        $this->password = getenv('PASSWORD');
        $this->dbname = getenv('DBNAME');
        $this->host = getenv('HOST');
        $this->port = getenv('PORT');
    }

    // Method to establish a connection to the database
    public function connect() {
        if ($this->conn === null) {
            // Create a connection string for PostgreSQL
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
            
            try {
                // Attempt to connect using PDO
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // Uncomment below for debugging to ensure successful connection
                // echo "Connection successful!";
            } catch (PDOException $e) {
                // Handle connection errors and log them
                error_log("Connection failed: " . $e->getMessage());
                // Optionally, you can echo this error on the page for testing
                // echo "Connection failed: " . $e->getMessage();
                return null; // Return null on failure
            }
        }
        return $this->conn; // Return the existing connection if it exists
    }
}

// Usage example (optional, for testing purposes)
$dbConnection = new DatabaseConnection();
$conn = $dbConnection->connect();
if ($conn) {
    // Uncomment below for debugging
    // echo "Connected successfully!";
} else {
    // Uncomment below for debugging
    // echo "Failed to connect to database!";
}
?>
