<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

  <head>
    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
    <title></title>
  </head>

  <body>
<h1>College Internship</h1>
<h2>Intern Registration</h2>
<?php

// Validate the e-mail address entered.
$errors = 0;
$email = "";
if (empty($_POST['email'])) {
  ++$errors;
  echo "<p>You need to enter an e-mail address.</p>\n";
} else {
  $email = stripslashes($_POST['email']);
  $pattern = "/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[w-]+)*(\.[a-zA-X]{2, })$/i";
  if (preg_match($pattern, $email)) {
    ++$errors;
    echo "<p>You need to enter a valid e-mail address.</p>\n";
    $email = "";
  }
}

// Validate the password.
if (empty($_POST['password'])) {
  ++$errors;
  echo "<p>You need to enter a password.</p>\n";
  $password = "";
} else {
  $password = stripslashes($_POST['password']);
}

if (empty($_POST['password2'])) {
  ++$errors;
  echo "<p>You need to enter a confirmation password.</p>\n";
  $password2 = "";
} else {
  $password2 = stripslashes($_POST['password2']);
}

if ((!(empty($password))) && (!(empty($password2)))) {
  if (strlen($password) < 6) {
    ++$errors;
    echo "<p>The password is too short.</p>\n";
    $password = "";
    $password2 = "";
  }

  if ($password != $password2) {
    ++$errors;
    echo "<p>The passwords do not match.</p>\n";
    $password = "";
    $password2 = "";
  }
}

// Connect to the internships database server.
$DBConnect = FALSE;
if ($errors == 0) {
  // $DBConnect = @mysql_connect("host", "user", "password");
  if ($DBConnect === FALSE) {
    echo "<p>Unable to connect to the database server."
    . "Error code " . mysql_errno() . ": " .
    mysql_error() . "</p>\n";
    ++$errors;
  } else {
    $DBName = "internships";
    $result = @mysql_select_db($DBName, $DBConnect);
    if ($result === FALSE) {
      echo "<p>Unable to select the database. "
      . "Error code " . mysql_errno() . ": " . mysql_error($DBConnect) . "</p>\n";
      ++$errors;
    }
  }
}

// Verify that the e-mail address entered is not already in the interns table:
$TableName = "interns";
if ($errors == 0) {
  $SQLstring = "SELECT count(*) FROM $TableName where email='$email'";
  $QueryResult = @mysql_query($SQLstring, $DBConnect);
  if ($QueryResult !== FALSE) {
    $Row = mysql_fetch_row($QueryResult);
    if ($Row[0] > 0) {
      echo "<p>The email address entered (" . htmlentities($email)
        . ") is already registered.</p>\n";
      ++$errors;
    }
  }
}

if ($errors > 0) {
  echo "<p>Please use your browser's BACK button to return"
    . " to the form and fix the errors indicated.</p>\n";
}

// Add the new user to the interns table if they don't already exist in the database.
if ($errors == 0) {
  $first = stripslashes($_POST['first']);
  $last = stripslashes($_POST['last']);
  $SQLstring = "INSERT INTO $TableName"
  . " (first, last, email, password_md5) "
  . " VALUES( '$first', '$last', '$email', "
  . " '" . md5($password) . "')";
  $QueryResult = @mysql_query($SQLstring, $DBConnect);
  if ($QueryResult === FALSE) {
    echo "<p>Unable to save your registration "
      . " information. Error code "
      . $mysql_errno($DBConnect) . ": "
      . $mysql_error($DBConnect) . "</p>\n";
    ++$errors;
  } else {
    $InternID = mysql_insert_id($DBConnect);
  }
  mysql_close($DBConnect);
}

// Echo confirmation message upon successful registration.
if ($errors == 0) {
  $InternName = $first . " " . $last;
  echo "<p>Thank you, $InternName. ";
  echo "Your new Intern ID is <strong>"
    . $InternID . "</strong>.</p>\n";
}

// Include the form with the hidden field if there were no errors.
if ($errors == 0) {
  echo "<form method='post' action='AvailableOpportunities.php'>\n";
  echo "<input type='hidden' name='internID' value='$InternID'>\n";
  echo "<input type='submit' name='submit' value='View Available Opportunities'>\n";
  echo "</form>";
}
?>
  </body>

</html>
