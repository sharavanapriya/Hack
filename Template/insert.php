<?php
session_start();

$name=$_SESSION["name"];

$uid=$_SESSION["uid"];

$comments = $_REQUEST['comments'];
$pid = $_REQUEST['pid'];
$type = $_REQUEST['type'];

require("db/db.php");

$res=mysqli_query($con, "INSERT INTO comments( comments,pid,uid,name,type) VALUES('$comments','$pid','$uid','$name','$type')");
if (!$res) {
    printf("Error: %s\n", mysqli_error($con));
    exit();
}
$result = mysqli_query($con, "SELECT * FROM comments WHERE pid= '$pid' ORDER BY id DESC");
if (!$result) {
    printf("Error: %s\n", mysqli_error($con));
    exit();
}

while($row=mysqli_fetch_array($result))
{
echo "<div id='panel'>";
echo "<h5>" . $row['name'] . "</h5>";
echo "<h6>" . $row['date_publish'] . "</h6></br></br>";
echo  $row['comments'];
echo "</div>";
}
mysqli_close($con);
header('Location:index.php');

?>