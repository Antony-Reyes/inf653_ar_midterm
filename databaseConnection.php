<?php
class DatabaseConnection {
    private $username;
    private $password;
    private $dbname;
    private $host;
    private $port;
    private $conn;

    // Constructor to initialize class properties from environment variables
    public function __construct() {
        // Fetch environment variables set by Render or other services
        $this->username = getenv('USERNAME'); 
        $this->password = getenv('PASSWORD');
        $this->dbname = getenv('DBNAME');
        $this->host = getenv('HOST');
        $this->port = getenv('PORT');
    }

    // Method to establish a connection to the PostgreSQL database
    public function connect() {
        if ($this->conn === null) {
            // Create a connection string for PostgreSQL
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
            try {
                // Establishing the PDO connection
                $this->conn = new PDO($dsn, $this->username, $this->password);
                // Setting error mode to exceptions to handle errors effectively
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // Optionally log success for debugging purposes (consider removing this for production)
                // echo "Connection successful!"; 
            } catch (PDOException $e) {
                // Handle connection errors and log them for troubleshooting
                error_log("Connection failed: " . $e->getMessage()); // Log the error to a file
                // Display a generic message to the user (don't expose actual error in production)
                echo "Database connection failed. Please try again later.";
                return null; // Return null on failure
            }
        }
        return $this->conn; // Return the existing connection if it already exists
    }

    // Optional: Method to close the connection (good practice for resource management)
    public function disconnect() {
        $this->conn = null;
    }
}

// Usage example (can be placed in your app logic to use the connection)
$dbConnection = new DatabaseConnection();
$conn = $dbConnection->connect();

// Perform your database operations here...

// Don't forget to disconnect after operations are complete
//$dbConnection->disconnect();
?>
