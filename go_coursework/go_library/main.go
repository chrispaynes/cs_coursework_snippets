package main

import (
	"database/sql"
	"fmt"
	_ "github.com/mattn/go-sqlite3"
	"html/template"
	"net/http"
)

type Page struct {
	Name     string
	DBStatus bool
}

func main() {
	// Creates new template and parses the template or panics upon error
	// Must wraps a function call returning (*Template, error)
	templates := template.Must(template.ParseFiles("templates/index.html"))

	// uses "sqlite3" driver to open connection to "dev.db" database
	db, _ := sql.Open("sqlite3", "dev.db")

	// displays connection status

	// HandleFunc registers the handler function for webserver requests on "/"
	// w => The HTTP handler uses ResponseWriter interface to construct an HTTP response
	// r => The HTTP request received by the server or to be sent by a client
	http.HandleFunc("/", func(w http.ResponseWriter, r *http.Request) {
		p := Page{Name: "GopherCon 2017"}

		// uses FormValue and a "key" string to return the URL's query value
		// or returns p if the key's value is empty
		if name := r.FormValue("name"); name != "" {
			p.Name = name
		}
		// Pings db to verify connectivity and attempts to reconnect
		// on connection loss or returns error if it cannot connect to db
		p.DBStatus = db.Ping() == nil

		// w constructs an HTTP response using the "index.html" template to display
		// template's query parameter, p OR uses err.Error() to write a HTTP error status code
		if err := templates.ExecuteTemplate(w, "index.html", p); err != nil {
			// takes error and returns error with internal server error
			http.Error(w, err.Error(), http.StatusInternalServerError)
		}
	})

	// starts webserver to listen and serve content on localhost 8080
	fmt.Println(http.ListenAndServe(":8080", nil))
}
