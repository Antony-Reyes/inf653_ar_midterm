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

// Initialize the MySQL database connection
func initDB() {
	var err error

	// Load database credentials from Render's environment variables
	dbUser := os.Getenv("DB_USER")
	dbPassword := os.Getenv("DB_PASSWORD")
	dbHost := os.Getenv("DB_HOST")
	dbName := os.Getenv("DB_NAME")

	// Check for missing variables
	if dbUser == "" || dbPassword == "" || dbHost == "" || dbName == "" {
		log.Fatal("❌ Missing required database environment variables")
	}

	// MySQL connection string
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

// GET /api/quotes/ - Fetch all quotes or filter by parameters
func getQuotes(c *gin.Context) {
	id := c.Query("id")
	authorID := c.Query("author_id")
	categoryID := c.Query("category_id")

	query := "SELECT quotes.id, quotes.quote, authors.author, categories.category FROM quotes " +
		"JOIN authors ON quotes.author_id = authors.id " +
		"JOIN categories ON quotes.category_id = categories.id"

	var args []interface{}

	if id != "" {
		query += " WHERE quotes.id = ?"
		args = append(args, id)
	} else if authorID != "" {
		query += " WHERE quotes.author_id = ?"
		args = append(args, authorID)
	} else if categoryID != "" {
		query += " WHERE quotes.category_id = ?"
		args = append(args, categoryID)
	}

	rows, err := db.Query(query, args...)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	defer rows.Close()

	var quotes []map[string]interface{}
	for rows.Next() {
		var id int
		var quote, author, category string
		if err := rows.Scan(&id, &quote, &author, &category); err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
			return
		}
		quotes = append(quotes, map[string]interface{}{
			"id":       id,
			"quote":    quote,
			"author":   author,
			"category": category,
		})
	}

	if len(quotes) == 0 {
		c.JSON(http.StatusNotFound, gin.H{"message": "No Quotes Found"})
		return
	}

	c.JSON(http.StatusOK, quotes)
}

// GET /api/authors/ - Fetch all authors
func getAuthors(c *gin.Context) {
	id := c.Query("id")

	query := "SELECT id, author FROM authors"
	var args []interface{}

	if id != "" {
		query += " WHERE id = ?"
		args = append(args, id)
	}

	rows, err := db.Query(query, args...)
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

	if len(authors) == 0 {
		c.JSON(http.StatusNotFound, gin.H{"message": "Author Not Found"})
		return
	}

	c.JSON(http.StatusOK, authors)
}

// GET /api/categories/ - Fetch all categories
func getCategories(c *gin.Context) {
	id := c.Query("id")

	query := "SELECT id, category FROM categories"
	var args []interface{}

	if id != "" {
		query += " WHERE id = ?"
		args = append(args, id)
	}

	rows, err := db.Query(query, args...)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	defer rows.Close()

	var categories []map[string]interface{}
	for rows.Next() {
		var id int
		var category string
		if err := rows.Scan(&id, &category); err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
			return
		}
		categories = append(categories, map[string]interface{}{
			"id":       id,
			"category": category,
		})
	}

	if len(categories) == 0 {
		c.JSON(http.StatusNotFound, gin.H{"message": "Category Not Found"})
		return
	}

	c.JSON(http.StatusOK, categories)
}

func main() {
	// Initialize database
	initDB()
	defer db.Close()

	// Set up Gin router
	r := gin.Default()

	// Define API routes
	r.GET("/api/quotes", getQuotes)
	r.GET("/api/authors", getAuthors)
	r.GET("/api/categories", getCategories)

	// Get Render-assigned port (default to 8080)
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
