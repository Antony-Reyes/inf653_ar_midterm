<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables from .env file if it exists
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204);
    exit();
}

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        // Load database credentials from environment variables
        $this->host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost';
        $this->db_name = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'INF653_AR_Midterm';
        $this->username = $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'your_database_username';
        $this->password = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?? 'your_database_password';
    }

    public function connect() {
        $this->conn = null;
        try {
            // Establish PDO connection with error mode set to exception
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection error: " . $e->getMessage());
        }
        return $this->conn;
    }

    public function initializeDatabase() {
        if (!$this->conn) {
            return;
        }

        try {
            // SQL statements to create tables
            $sql = "
                CREATE TABLE IF NOT EXISTS authors (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    author VARCHAR(255) NOT NULL
                );

                CREATE TABLE IF NOT EXISTS categories (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    category VARCHAR(255) NOT NULL
                );

                CREATE TABLE IF NOT EXISTS quotes (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    quote TEXT NOT NULL,
                    author_id INT,
                    category_id INT,
                    FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE,
                    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
                );

                -- Insert sample data if tables are empty
                INSERT INTO authors (author) 
                SELECT * FROM (SELECT 'Dr. Seuss') AS tmp WHERE NOT EXISTS (SELECT 1 FROM authors);
                
                INSERT INTO authors (author) 
                SELECT * FROM (SELECT 'Barack Obama') AS tmp WHERE NOT EXISTS (SELECT 1 FROM authors WHERE author = 'Barack Obama');

                INSERT INTO authors (author) 
                SELECT * FROM (SELECT 'Taylor Swift') AS tmp WHERE NOT EXISTS (SELECT 1 FROM authors WHERE author = 'Taylor Swift');

                INSERT INTO categories (category) 
                SELECT * FROM (SELECT 'Life') AS tmp WHERE NOT EXISTS (SELECT 1 FROM categories WHERE category = 'Life');

                INSERT INTO categories (category) 
                SELECT * FROM (SELECT 'Motivation') AS tmp WHERE NOT EXISTS (SELECT 1 FROM categories WHERE category = 'Motivation');

                INSERT INTO quotes (quote, author_id, category_id) 
                SELECT * FROM (SELECT 'Today you are you!', 1, 1) AS tmp 
                WHERE NOT EXISTS (SELECT 1 FROM quotes WHERE quote = 'Today you are you!');
                
                INSERT INTO quotes (quote, author_id, category_id) 
                SELECT * FROM (SELECT 'The more that you read...', 1, 2) AS tmp 
                WHERE NOT EXISTS (SELECT 1 FROM quotes WHERE quote = 'The more that you read...');
                
                INSERT INTO quotes (quote, author_id, category_id) 
                SELECT * FROM (SELECT 'Money is not the only answer...', 2, 1) AS tmp 
                WHERE NOT EXISTS (SELECT 1 FROM quotes WHERE quote = 'Money is not the only answer...');
            ";

            $this->conn->exec($sql);
        } catch (PDOException $e) {
            die("Database initialization error: " . $e->getMessage());
        }
    }
}

// Initialize the database connection and create tables if needed
$database = new Database();
$conn = $database->connect();
$database->initializeDatabase();
?>
