package main

import (
	"database/sql"
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

	// Load database credentials from environment variables (set in Render)
	dbUser := os.Getenv("DB_USER")
	dbPassword := os.Getenv("DB_PASSWORD")
	dbHost := os.Getenv("DB_HOST")
	dbName := os.Getenv("DB_NAME")

	// Ensure all necessary variables are set
	if dbUser == "" || dbPassword == "" || dbHost == "" || dbName == "" {
		log.Fatal("❌ Missing required database environment variables")
	}

	// Data Source Name (DSN) for MySQL connection
	dsn := dbUser + ":" + dbPassword + "@tcp(" + dbHost + ":3306)/" + dbName + "?parseTime=true"

	db, err = sql.Open("mysql", dsn)
	if err != nil {
		log.Fatal("❌ Error opening database: ", err)
	}

	// Verify database connection
	if err := db.Ping(); err != nil {
		log.Fatal("❌ Error pinging database: ", err)
	}

	log.Println("✅ Connected to MySQL database successfully")
}

// Route for fetching all authors
func getAuthors(c *gin.Context) {
	rows, err := db.Query("SELECT id, author FROM authors") // Updated column name
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
	rows, err := db.Query("SELECT id, quote, author_id, category_id FROM quotes")
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

	// Define API routes
	r.GET("/api/quotes", getQuotes)
	r.GET("/api/authors", getAuthors)

	// Get Render-assigned port (fallback to 8080)
	port := os.Getenv("PORT")
	if port == "" {
		port = "8080"
	}

	log.Println("🚀 Server is running on port", port)

	// Start the server
	if err := r.Run(":" + port); err != nil {
		log.Fatal("❌ Unable to start server: ", err)
	}
}
