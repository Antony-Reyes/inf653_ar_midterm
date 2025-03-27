<?php
class Database {
    private $host = "dpg-cvhm5l1opnds73fl4170-a";  // Change to Render or Localhost
    private $port = '5432';
    private $db_name = "inf653_ar_midterm_7e6a";
    private $username = "inf653_ar_midterm_7e6a_user";
    private $password = "5zCP7GMDvHwtrpXF9D0hpgWFr6TGpQSu";
    public $conn;
}

public function _construct() {
    if ($this->conn) {
        return $this->conn;
    } else {
        $dsn = "pgsl:host = {$this->host};port={$this->dbname};";
        try {
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR-ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch(PDOException $e){
            echo 'Connection Error: ' . $e->getMessage();
        }
    }
}
?>
