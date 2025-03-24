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
	dsn := "root:password@tcp(localhost:3306)/inf653_ar_midterm" // Update your database credentials if needed
	db, err = sql.Open("mysql", dsn)
	if err != nil {
		log.Fatal("Error opening database: ", err)
	}
	if err := db.Ping(); err != nil {
		log.Fatal("Error pinging database: ", err)
	}
}

// Route for fetching all authors
func getAuthors(c *gin.Context) {
	rows, err := db.Query("SELECT id, name FROM authors") // Assuming authors table has id and name
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	defer rows.Close()

	var authors []map[string]interface{}
	for rows.Next() {
		var id int
		var name string
		if err := rows.Scan(&id, &name); err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
			return
		}
		authors = append(authors, map[string]interface{}{
			"id":   id,
			"name": name,
		})
	}

	c.JSON(http.StatusOK, authors)
}

// Route for fetching all quotes
func getQuotes(c *gin.Context) {
	rows, err := db.Query("SELECT id, quote, author_id, category_id FROM quotes") // Assuming quotes table has these fields
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	defer rows.Close()

	var quotes []map[string]interface{}
	for rows.Next() {
		var id, authorID, categoryID int
		var quote string
		if err := rows.Scan(&id, &quote, &authorID, &categoryID); err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
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

	// Define routes
	r.GET("/api/quotes", getQuotes)
	r.GET("/api/authors", getAuthors)

	// Start the server on port 8080
	if err := r.Run(":8080"); err != nil {
		log.Fatal("Unable to start server: ", err)
	}
}
