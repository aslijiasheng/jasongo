package main

import (
	"html/template"
	"io"
	"net/http"

	"github.com/labstack/echo"
	mw "github.com/labstack/echo/middleware"
)

type (
	Template struct {
		templates *template.Template
	}

	user struct {
		ID   string `json:"id"`
		Name string `json:"name"`
	}
)

var (
	users map[string]user
)

func (t *Template) Render(w io.Writer, name string, data interface{}) error {
	return t.templates.ExecuteTemplate(w, name, data)
}

// Handler
func hello(c *echo.Context) error {
	return c.Render(http.StatusOK, "hello", "World")
	// return c.String(http.StatusOK, "Hello, World!\n")
}

func getUsers(c *echo.Context) error {
	return c.JSON(http.StatusOK, users)
}

func main() {

	t := &Template{
		templates: template.Must(template.ParseGlob("../website/public/hello.html")),
	}

	// Echo instance
	e := echo.New()

	// Debug mode
	e.Debug()

	// Middleware
	e.Use(mw.Logger())
	e.Use(mw.Recover())
	e.Use(mw.Gzip())

	e.SetRenderer(t)

	// Routes
	e.Get("/", hello)

	e.Use(func(c *echo.Context) error {
		println(c.Path()) // Prints `/users/:name`
		return nil
	})

	e.Get("/expressListUsers", expressListUsers)
	e.Get("/expressQueryUsers", expressQueryUsers)
	e.Get("/expressEmailMessage", expressEmailMessage)

	e.Get("/users/:name", func(c *echo.Context) error {
		// By name
		name := c.Param("name")
		return c.String(http.StatusOK, name)
	})

	// Start server
	e.Run(":1323")
}

func init() {
	users = map[string]user{
		"1": user{
			ID:   "1",
			Name: "Wreck-It Ralph",
		},
	}
}
