package main

import (
	"database/sql"
	"log"
	"net/http"
	"os"

	"github.com/gin-gonic/gin"
	_ "github.com/go-sql-driver/mysql" // MySQL driver
)

var db *sql.DB

// Init function to initialize the database connection
func init() {
	// Connect to the database (replace with your actual credentials)
	var err error
	db, err = sql.Open("mysql", "user:password@tcp(localhost:3306)/dbname") // Update with actual credentials
	if err != nil {
		log.Fatal("Error connecting to the database:", err)
	}
}

// Quote struct to map data
type Quote struct {
	ID       int    `json:"id"`
	Text     string `json:"text"`
	AuthorID int    `json:"author_id"`
}

// Author struct to map data
type Author struct {
	ID   int    `json:"id"`
	Name string `json:"name"`
}

// Get Quotes handler
func getQuotes(c *gin.Context) {
	rows, err := db.Query("SELECT id, text, author_id FROM quotes")
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to fetch quotes"})
		return
	}
	defer rows.Close()

	var quotes []Quote
	for rows.Next() {
		var quote Quote
		if err := rows.Scan(&quote.ID, &quote.Text, &quote.AuthorID); err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to parse quotes"})
			return
		}
		quotes = append(quotes, quote)
	}

	c.JSON(http.StatusOK, quotes)
}

// Get Authors handler
func getAuthors(c *gin.Context) {
	rows, err := db.Query("SELECT id, name FROM authors")
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to fetch authors"})
		return
	}
	defer rows.Close()

	var authors []Author
	for rows.Next() {
		var author Author
		if err := rows.Scan(&author.ID, &author.Name); err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to parse authors"})
			return
		}
		authors = append(authors, author)
	}

	c.JSON(http.StatusOK, authors)
}

// Main function to set up routes and run the server
func main() {
	// Create a new Gin router
	r := gin.Default()

	// Define routes
	r.GET("/api/quotes", getQuotes)   // Route to get all quotes
	r.GET("/api/authors", getAuthors) // Route to get all authors

	// Dynamically use the port assigned by Render
	port := os.Getenv("PORT")
	if port == "" {
		port = "8080" // Default to 8080 if PORT is not set
	}

	// Start the Gin server
	err := r.Run(":" + port)
	if err != nil {
		log.Fatal("Error starting the server:", err)
	}
}
