<?php
// Database credentials
$host     = 'localhost';
$username = 'root';        // Change if using a different user
$password = '';            // Change if your MySQL user has a password
$dbname   = 'workorganizer_db';
$conn;

// Internal APIs 
function openDB() {
  global $servername, $username, $password, $dbname, $conn;

// Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
        return $conn->connect_error;
    } else {
        return "Connected";
    }
}

function closeDB() {
  global $conn;
  $conn->close();
}

// API to modify DB
function modifyDB($sql) {
  global $conn;
  $message = openDB();
  if ($message == "Connected") {
    if ($conn->query($sql) === TRUE)
      $message = "Update Successful";
    else
      $message = $conn->error;
    closeDB();
  }
  return $message . "<br>";
}

//API to modify and get last id 
function modifyReturnIDDB($sql, $sql2) { // returns an object or a string
  global $conn;
  $message = openDB();
  if ($message == "Connected") 
  {
      if ($conn->query($sql) === TRUE) {
        $result = $conn->query($sql2);
        if (gettype($result) == "object") 
        {
            $message = $result;
        } 
        else {
            $message = $conn->error . "<br>Your SQL:" . $sql;
        }
      }
    else
      $message = $conn->error;
  closeDB();     
  }
  return $message;
}

// API to query DB
function queryDB($sql) { // returns an object or a string
  global $conn;
  $message = openDB();
  if ($message == "Connected") {
    $result = $conn->query($sql);
    if (gettype($result) == "object") {
            $message = $result;
        } else {
            $message = $conn->error . "<br>Your SQL:" . $sql;
        }
        closeDB();
  }
  return $message;
}

// API for login with prepared statement
function loginDB($sql, $user, $pwd) {
  global $conn;
  $message = openDB();
  if ($message == "Connected") {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user, $pwd);
    $stmt->execute();
    $result = $stmt->get_result();
    if (gettype($result) == "object") {
            $message = $result;
        } else {
            $message = $conn->error . "<br>Your SQL:" . $sql;
        }
        closeDB();
  }
  return $message;
}

