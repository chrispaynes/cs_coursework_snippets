// Based off Todd McLeod's Code Clinic on Lynda.com
// Collects and outputs weather data from Lake Pend Oreille, Idaho.
// Calculates Mean and Median for wind speed, air temperature and barometric pressure
// DATA SOURCE: https://lpo.dt.navy.mil/data/
// https://lpo.dt.navy.mil/data/DM/Environmental_Data_Deep_Moor_2016.txt

package main

import (
  "fmt"
  "net/http"
  "log"
  "encoding/csv"
	"strconv"
)

func main() {
	data_source := "https://raw.githubusercontent.com/lyndadotcom/LPO_weatherdata/master/Environmental_Data_Deep_Moor_2015.txt"

	// Creates HTTP Get request from data_source argument
	// Returns response body or an error
  resp, err := http.Get(data_source)

	// Prints fatal errors and immediately calls os.Exit(1)
  if err != nil {
    fmt.Printf("Did not receive a HTTP response from %v\n", "\"" + data_source + "\"")
    log.Fatal(err)
  }

	// Defers until the surrounding function executes its return statement
		// OR defers until the function reaches its function body ending
		// OR defers because the corresponding goroutine is panicking
	// Closes the reader
  defer resp.Body.Close()

	// Creates NewReader from io.Reader interface
	// Reads records from a CSV-encoded file
	// Return value references the *Reader struct {}
	// By default NewReader returns an &Reader instance with "," set for the Rune "Comma" field
	rdr := csv.NewReader(resp.Body)

	// Reassigns Comma field to tab-delimitation
  rdr.Comma = '\t'

	// Sets object to ignore leading white space in a field
  rdr.TrimLeadingSpace = true

	// ReadAll uses *Reader to read the argument's remaining records
	// Reads and slices each record/line from source file until io.EOF
	// Appends each record/line slice to a "records" array
	// Returns the "records" array as record slices containing string literals
	rows, err := rdr.ReadAll()

	// Begins to panic when an error is present
	// Panic stops the ordinary flow control and begins panicking.
	// Panic stops the function execution
	// Executes defer functions and returns the panicked function to its caller
  if err != nil {
    panic(err)
  }

	// Loops through array printing each row
	// "_" signifies a Blank Identifier
	// Blank Identifiers instruct the program to ignore returned index values
	// "range" iterates through all entries within row
	// for _, row := range rows
	// 	fmt.Println(row)
	//	}

	// declares zero-value variables to hold accumulated totals
	// calculations are adding per each loop iteration
  // :var air_temp_total, baro_pres_total, wind_speed_total, counter float64;

  // RAW INPUT DATA
  // HEADER  [ date        time      Air_Temp  Barometric_Press  Dew_Point  Relative_Humidity   Wind_Dir  Wind_Gust  Wind_Speed ]
	// ROW 1:  [ 2015_06_04  01:09:21  57.70     29.95             51.22      79.00               163.40    12.00      10.00      ]
  // ROW 2:  [ 2015_06_04  01:09:21  57.70     29.95             51.22      79.00               163.40    12.00      10.00      ]
  // ROW N: ...

  // FORMATTED OUTPUT
  //           [Air_Temp   Barometric_Press   Wind_Speed ]
  // # 00001 - [57.70      29.95              10.00      ]
  // # 00002 - [57.70      29.95              10.00      ]
  for i, row := range rows {
    fmt.Println("#", i, " - ", row[1], " ", row[2], " ", row[7])
  }

  fmt.Println("----------------------------------------------")
  fmt.Println("\t   ", rows[0][1], rows[0][2], rows[0][7])
	fmt.Println("TOTAL RECORDS:", len(rows) - 1)
	fmt.Println("----------------------------------------------")
  // calculates the mean for columns 1, 2, 7 (Air_Temp, Barometric_Press, and Wind_Speed)
	fmt.Println("Mean Air Temp:", calcMean(rows, 1))
	fmt.Println("Mean Barometric Pressure:", calcMean(rows, 2))
	fmt.Println("Mean Wind Speed:", calcMean(rows, 7))

} // end main func

// accepts a slice and an index integer
// returns a float64
func calcMean(rows [][]string, index int) float64 {
	var total float64

  // Parses columns/slices from strings to float64 precision
  // calculates total value during each loop iteration
	for i, row := range rows {
		if i != 0 {
			val, _ := strconv.ParseFloat(row[index], 64)
			total += val
		}
	}

	return total / float64(len(rows) - 1)
}