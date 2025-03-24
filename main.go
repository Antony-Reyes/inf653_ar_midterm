package main

import (
	"fmt"
	"log"
	"net/http"

	"github.com/gin-gonic/gin"
)

func main() {
	// Create a new Gin router
	r := gin.Default()

	// Define a simple route
	r.GET("/api", func(c *gin.Context) {
		c.JSON(http.StatusOK, gin.H{
			"message": "Hello, world!",
		})
	})

	// Start the server on port 8080
	if err := r.Run(":8080"); err != nil {
		log.Fatalf("Error starting server: %v", err)
	}

	fmt.Println("Server running at http://localhost:8080")
}
