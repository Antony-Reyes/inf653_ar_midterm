package main

import (
	"database/sql"
	"log"
	"net/http"

	"github.com/gin-gonic/gin"
	_ "github.com/go-sql-driver/mysql" // Import MySQL driver
)

var db *sql.DB

// Initialize the MySQL database connection
func initDB() {
	var err error
	// Replace with actual Render database credentials
	dsn := "your-username:your-password@tcp(your-database-hostname:3306)/quotesdb"
	db, err = sql.Open("mysql", dsn) // Assign to global `db`
	if err != nil {
		log.Fatal("Error opening database connection: ", err)
	}

	// Ensure connection is alive
	if err := db.Ping(); err != nil {
		log.Fatal("Error connecting to database: ", err)
	}
	log.Println("Database connection established successfully")
}

// Route for fetching all authors
func getAuthors(c *gin.Context) {
	rows, err := db.Query("SELECT id, author FROM authors") // Fixed column name
	if err != nil {
		log.Println("Error fetching authors:", err)
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to fetch authors"})
		return
	}
	defer rows.Close()

	var authors []map[string]interface{}
	for rows.Next() {
		var id int
		var author string
		if err := rows.Scan(&id, &author); err != nil {
			log.Println("Error scanning author row:", err)
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to read author data"})
			return
		}
		authors = append(authors, map[string]interface{}{
			"id":     id,
			"author": author,
		})
	}

	c.JSON(http.StatusOK, authors)
}

// Route for fetching all quotes
func getQuotes(c *gin.Context) {
	rows, err := db.Query("SELECT id, quote, author_id, category_id FROM quotes")
	if err != nil {
		log.Println("Error fetching quotes:", err)
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to fetch quotes"})
		return
	}
	defer rows.Close()

	var quotes []map[string]interface{}
	for rows.Next() {
		var id, authorID, categoryID int
		var quote string
		if err := rows.Scan(&id, &quote, &authorID, &categoryID); err != nil {
			log.Println("Error scanning quote row:", err)
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to read quote data"})
			return
		}
		quotes = append(quotes, map[string]interface{}{
			"id":          id,
			"quote":       quote,
			"author_id":   authorID,
			"category_id": categoryID,
		})
	}

	c.JSON(http.StatusOK, quotes)
}

func main() {
	// Initialize the database connection
	initDB()
	defer db.Close()

	// Set up Gin router
	r := gin.Default()

	// Define API routes
	r.GET("/api/quotes", getQuotes)
	r.GET("/api/authors", getAuthors)

	// Start the server on port 8080
	log.Println("Server is running on port 8080...")
	if err := r.Run(":8080"); err != nil {
		log.Fatal("Server failed to start: ", err)
	}
}
