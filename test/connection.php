<?php
// phpinfo();
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
$servername = "localhost";
$username = "sunil";
$password = "pass";

// Create connection
 $conn = mysqli_connect($servername, $username, $password,'testlogin');
//  mysql_select_db("testlogin", $conn);
//  $pdo = new PDO("mysql:host=localhost;dbname=testlogin", "sunil", "pass");
// Check connection
/* if (!$conn) {
die("Connection failed: " . mysqli_connect_error());
}
else{
    echo "else";
}
echo "success!"; */