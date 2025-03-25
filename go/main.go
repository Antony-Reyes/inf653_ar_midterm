package main

import (
	"database/sql"
	"fmt"
	"log"
	"net/http"
	"os"

	"github.com/gin-gonic/gin"
	_ "github.com/go-sql-driver/mysql" // MySQL driver
	"github.com/joho/godotenv"
)

var db *sql.DB // Global database connection

// Initialize the MySQL database connection
func initDB() {
	// Load environment variables from .env file (if exists)
	err := godotenv.Load()
	if err != nil {
		log.Println("⚠️ Warning: No .env file found, using system environment variables.")
	}

	// Get database credentials from environment variables
	dbHost := os.Getenv("DB_HOST")
	dbUser := os.Getenv("DB_USER")
	dbPassword := os.Getenv("DB_PASSWORD")
	dbName := os.Getenv("DB_NAME")
	dbPort := os.Getenv("DB_PORT")

	// Default to MySQL port 3306 if DB_PORT is not set
	if dbPort == "" {
		dbPort = "3306"
	}

	// Ensure all required environment variables are set
	if dbUser == "" || dbPassword == "" || dbHost == "" || dbName == "" {
		log.Fatal("❌ Missing required database environment variables. Check DB_HOST, DB_USER, DB_PASSWORD, and DB_NAME.")
	}

	// Log connection details (excluding password)
	log.Println("🔹 DB_USER:", dbUser)
	log.Println("🔹 DB_HOST:", dbHost)
	log.Println("🔹 DB_NAME:", dbName)
	log.Println("🔹 DB_PORT:", dbPort)

	// MySQL connection string
	dsn := fmt.Sprintf("%s:%s@tcp(%s:%s)/%s?charset=utf8mb4&parseTime=True&loc=Local",
		dbUser, dbPassword, dbHost, dbPort, dbName,
	)

	// Open database connection
	dbConn, err := sql.Open("mysql", dsn)
	if err != nil {
		log.Fatal("❌ Error opening database:", err)
	}

	// Verify database connection
	if err := dbConn.Ping(); err != nil {
		log.Fatal("❌ Error pinging database:", err)
	}

	// Assign connection to global db variable
	db = dbConn
	log.Println("✅ Connected to MySQL database successfully")
}

// CORS middleware to allow frontend access
func corsMiddleware() gin.HandlerFunc {
	return func(c *gin.Context) {
		c.Writer.Header().Set("Access-Control-Allow-Origin", "*")
		c.Writer.Header().Set("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS")
		c.Writer.Header().Set("Access-Control-Allow-Headers", "Content-Type, Authorization")

		// Handle preflight requests
		if c.Request.Method == "OPTIONS" {
			c.AbortWithStatus(http.StatusNoContent)
			return
		}

		c.Next()
	}
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
	conditions := []string{}

	// Handle multiple filters
	if id != "" {
		conditions = append(conditions, "quotes.id = ?")
		args = append(args, id)
	}
	if authorID != "" {
		conditions = append(conditions, "quotes.author_id = ?")
		args = append(args, authorID)
	}
	if categoryID != "" {
		conditions = append(conditions, "quotes.category_id = ?")
		args = append(args, categoryID)
	}

	if len(conditions) > 0 {
		query += " WHERE " + conditions[0]
		for _, condition := range conditions[1:] {
			query += " AND " + condition
		}
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

    // Enable CORS (for Netlify/frontend)
    r.Use(func(c *gin.Context) {
        c.Writer.Header().Set("Access-Control-Allow-Origin", "*")
        c.Writer.Header().Set("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS")
        c.Writer.Header().Set("Access-Control-Allow-Headers", "Origin, Content-Type, Authorization")
        c.Next()
    })

    // Define API routes
    r.GET("/api/quotes", getQuotes)
    r.GET("/api/authors", getAuthors)
    r.GET("/api/categories", getCategories)

    // Get PORT from environment variables
    port := os.Getenv("PORT")
    if port == "" {
        log.Fatal("❌ PORT environment variable not set. Ensure it's configured in Render.")
    }

    log.Println("🚀 Server is running on port", port)

    // Run the server on the specified PORT
    r.Run(":" + port)
}

