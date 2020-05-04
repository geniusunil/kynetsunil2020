<?php
include('connection.php');





?>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
    <h3>all messages !</h3>
    <div >
    <table id="allmess">

    </table>
    </div>
</body>
<script type="text/javascript">

function doSomething() {
    // alert('This pops up every 5 seconds and is annoying!');
    $.ajax({ //create an ajax request to display.php
type: "GET",
url: "display.php", 
dataType: "html", //expect html to be returned 
success: function(response){ 
$("#allmess").html(response); 
// alert(response);
}


});
}


$(document).ready(function() {
    doSomething();

    setInterval(doSomething, 1000); // Time in milliseconds


});

</script>
</html>