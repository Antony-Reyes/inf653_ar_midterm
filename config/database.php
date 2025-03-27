<?php
class Database {
    private $host = "dpg-cvhm5l1opnds73fl4170-a";  // Change if needed
    private $port = '5432';
    private $db_name = "inf653_ar_midterm_7e6a";
    private $username = "inf653_ar_midterm_7e6a_user";
    private $password = "5zCP7GMDvHwtrpXF9D0hpgWFr6TGpQSu";
    private $conn;

    public function __construct() {
        $this->conn = null;
        $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name};";

        try {
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection Error: " . $e->getMessage());
        }
    }

    public function connect() {
        return $this->conn;
    }
}
?>
