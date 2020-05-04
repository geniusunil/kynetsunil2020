<?php

include('connection.php');


$sql = "SELECT * FROM mess";
if($result = mysqli_query($conn, $sql)){
    if(mysqli_num_rows($result) > 0){ while($row = mysqli_fetch_array($result)){
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . $row['message'] . "</td>";
        // echo "<td>" . $row['email'] . "</td>";
        echo "</tr>";
        }
    }}