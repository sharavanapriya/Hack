<?php
session_start();
@$pid=$_SESSION["pid"];
require("db/db.php");
$result = mysqli_query($con, "SELECT * FROM comments WHERE pid= '$pid' ORDER BY id DESC");
if (!$result) {
    printf("Error: %s\n", mysqli_error($con));
    exit();
}
while($row=mysqli_fetch_array($result)){
	
echo "<div id='panel'>";
echo "<h5 class='panel-title'>" . $row['name'] . "</h5>";
echo "<h6>" . $row['date_publish'] . "</h6>";
echo  $row['comments'] ."<br><br>";
echo "</div>";
}
mysqli_close($con);

?>