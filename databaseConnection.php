<?php
class DatabaseConnection {
    private $username;
    private $password;
    private $dbname;
    private $host;
    private $port;
    private $conn;

    public function __construct() {
        // Check if config.json exists and read from it
        $configPath = __DIR__ . '/config.json';
        if (file_exists($configPath)) {
            $config = json_decode(file_get_contents($configPath), true);
            $this->username = $config['db_user'] ?? getenv('USERNAME');
            $this->password = $config['db_password'] ?? getenv('PASSWORD');
            $this->dbname = $config['db_name'] ?? getenv('DBNAME');
            $this->host = $config['db_host'] ?? getenv('HOST');
            $this->port = $config['db_port'] ?? getenv('PORT') ?? '5432';  // Default to 5432 if not set
        } else {
            // Fall back to environment variables
            $this->username = getenv('USERNAME');
            $this->password = getenv('PASSWORD');
            $this->dbname = getenv('DBNAME');
            $this->host = getenv('HOST');
            $this->port = getenv('PORT') ?? '5432';  // Default to 5432 if not set
        }
    }

    // Establish connection to the database
    public function connect() {
        if ($this->conn === null) {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";

            try {
                // Create a new PDO instance and set the error mode to exception
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // Uncomment below for debugging
                // echo "Database connection successful!";
            } catch (PDOException $e) {
                // Log the error message and return null on failure
                error_log("Database connection failed: " . $e->getMessage());
                return null;
            }
        }
        return $this->conn;
    }
}

// To use the connection, instantiate DatabaseConnection and call connect
// This will allow you to use this object to connect to the database in other files (e.g., authors.php, categories.php)

// Example usage:
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
