<?php
$user = htmlspecialchars($_REQUEST["username"]);
$pass = htmlspecialchars($_REQUEST["password"]);
$mode = htmlspecialchars($_REQUEST["mode"]);

$conn = new mysqli("localhost","root","toor", "IERG4210");

if(!$conn){
	die("connection failed: " . mysqli_connect_error());
}

$sql = "SELECT user from accounts where user=\"$user\" and password=\"$pass\" and mode=\"$mode\"";
$result = $conn->query($sql);

if($result->num_rows > 0){
	if($mode == "0") header('Location: index.html');
	if($mode == "1") header('Location: admin.php');
}else{
	header('Location: login.html');
}




$conn->close();
?>
