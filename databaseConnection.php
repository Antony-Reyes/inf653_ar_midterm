<?php
class DatabaseConnection {
    private $username;
    private $password;
    private $dbname;
    private $host;
    private $port;
    private $conn;

    public function __construct() {
        // Path to config.json file
        $configPath = __DIR__ . '/config.json';

        if (file_exists($configPath)) {
            $config = json_decode(file_get_contents($configPath), true);

            $this->username = $config['db_user'] ?? getenv('USERNAME');
            $this->password = $config['db_password'] ?? getenv('PASSWORD');
            $this->dbname = $config['db_name'] ?? getenv('DBNAME');
            $this->host = $config['db_host'] ?? getenv('HOST');
            $this->port = $config['db_port'] ?? getenv('PORT') ?: '5432';  // Default to 5432 if not set
        } else {
            // Use environment variables as a fallback
            $this->username = getenv('USERNAME');
            $this->password = getenv('PASSWORD');
            $this->dbname = getenv('DBNAME');
            $this->host = getenv('HOST');
            $this->port = getenv('PORT') ?: '5432';  // Default to 5432 if not set
        }
    }

    public function connect() {
        if ($this->conn === null) {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
            
            try {
                $this->conn = new PDO($dsn, $this->username, $this->password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
                return $this->conn;
            } catch (PDOException $e) {
                error_log("Database connection failed: " . $e->getMessage());
                return null; 
            }
        }
        return $this->conn;
    }
}

// Optional: Test the database connection
$dbConnection = new DatabaseConnection();
$conn = $dbConnection->connect();
if ($conn) {
    // Uncomment the line below to debug
    // echo "Connected successfully!";
} else {
    // Uncomment the line below to debug
    // echo "Failed to connect to database!";
}
?>
