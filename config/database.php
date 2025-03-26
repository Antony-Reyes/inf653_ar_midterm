<?php
class Database {
    private $conn;
    private $host;
    private $port;
    private $dbname;
    private $username;
    private $password;
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
