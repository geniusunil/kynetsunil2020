<?php 

session_start();
include('connection.php');
// Check if the user is logged in, if not then redirect him to login page
echo $_SESSION["login_user"];
if(!isset($_SESSION["login_user"]) && empty($_SESSION["login_user"])){
header("location: login.php");
exit;
}
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // username and password sent from form 
    
    $myusername = $_SESSION["login_user"];
    $mymessage = $_POST['mymessage']; 
    $sql = "INSERT INTO mess (username, message)
VALUES ( '$myusername', '$mymessage')";

if (mysqli_query($conn, $sql)) {
echo "New record created successfully";
} else {
echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>
</head>
<body>
<a href="logout.php">logout</a>
<form action="" method="post">
Message:<br>
<textarea name="mymessage" required></textarea><br>
<button type="submit">submit</button>
</form>

</body>
</html>