package main

import (
	"fmt"
	"net/http"
	"os"

	"github.com/gin-gonic/gin"
)

func main() {
	// Set Gin to release mode for production
	gin.SetMode(gin.ReleaseMode)

	// Create a new Gin router
	r := gin.Default()

	// Handle the root route "/"
	r.GET("/", func(c *gin.Context) {
		c.JSON(http.StatusOK, gin.H{
			"message": "Welcome to the API!",
		})
	})

	// Handle the "/api" route (your existing API logic)
	r.GET("/api", func(c *gin.Context) {
		// Your API logic here, for example:
		c.JSON(http.StatusOK, gin.H{
			"status": "API is running",
		})
	})

	// Get the port from the environment variable
	port := os.Getenv("PORT")
	if port == "" {
		port = "8080" // Default port if not set
	}

	// Run the server on the specified port
	err := r.Run(":" + port)
	if err != nil {
		fmt.Println("Error starting server: ", err)
	}
}
