<?php
class Quote {
    private $conn;
    private $table = "quotes";

    public $id;
    public $quote;
    public $author_id;
    public $category_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all quotes
    public function read() {
        $query = "SELECT 
                    q.id, 
                    q.quote, 
                    q.author_id, 
                    q.category_id, 
                    a.name AS author_name, 
                    c.name AS category_name 
                  FROM " . $this->table . " q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id
                  ORDER BY q.id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get a single quote
    public function read_single() {
        $query = "SELECT 
                    q.id, 
                    q.quote, 
                    q.author_id, 
                    q.category_id, 
                    a.name AS author_name, 
                    c.name AS category_name 
                  FROM " . $this->table . " q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id
                  WHERE q.id = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->quote = $row['quote'];
            $this->author_id = $row['author_id'];
            $this->category_id = $row['category_id'];
            $this->author_name = $row['author_name'];
            $this->category_name = $row['category_name'];
        } else {
            $this->quote = null;
        }
    }

    // Create a new quote
    public function create() {
        $query = "INSERT INTO " . $this->table . " (quote, author_id, category_id) 
                  VALUES (:quote, :author_id, :category_id)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);

        return $stmt->execute();
    }

    // Update a quote
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET quote = :quote, author_id = :author_id, category_id = :category_id 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Delete a quote
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }
}
?>
