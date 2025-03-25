package main

import (
	"database/sql"
	"fmt"
	"log"
	"net/http"
	"os"

	"github.com/gin-gonic/gin"
	_ "github.com/go-sql-driver/mysql" // Import MySQL driver
)

var db *sql.DB

// Initialize the MySQL database connection
func initDB() {
	var err error

	// Read database credentials from environment variables
	dbUser := os.Getenv("DB_USER")
	dbPassword := os.Getenv("DB_PASSWORD")
	dbHost := os.Getenv("DB_HOST") // Use the correct Render database hostname
	dbName := os.Getenv("INF653_AR_Midterm")

	// Ensure all required variables are set
	if dbUser == "" || dbPassword == "" || dbHost == "" || dbName == "" {
		log.Fatal("❌ Missing required database environment variables")
	}

	// Construct DSN (Data Source Name) - Render.com uses this format
	dsn := fmt.Sprintf("%s:%s@tcp(%s:3306)/%s?charset=utf8mb4&parseTime=True&loc=Local",
		dbUser, dbPassword, dbHost, dbName)

	// Open database connection
	db, err = sql.Open("mysql", dsn)
	if err != nil {
		log.Fatal("❌ Error opening database: ", err)
	}

	// Verify database connection
	if err := db.Ping(); err != nil {
		log.Fatal("❌ Error connecting to database: ", err)
	}

	log.Println("✅ Successfully connected to the database!")
}

// Route for fetching all authors
func getAuthors(c *gin.Context) {
	rows, err := db.Query("SELECT id, author FROM authors") // Assuming authors table has id and author
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	defer rows.Close()

	var authors []map[string]interface{}
	for rows.Next() {
		var id int
		var author string
		if err := rows.Scan(&id, &author); err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
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
	log.Println("🚀 Server is running on port 8080...")
	if err := r.Run(":8080"); err != nil {
		log.Fatal("❌ Unable to start server: ", err)
	}
}
