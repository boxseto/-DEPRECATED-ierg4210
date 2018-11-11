<?php
require_once("authchk.php");
if(!authchk()){header('location: index.php');}
$user = htmlspecialchars($_REQUEST["username"]);
$pass = htmlspecialchars($_REQUEST["password"]);
$newpass = htmlspecialchars($_REQUEST["new_password"]);
$renewpass = htmlspecialchars($_REQUEST["re_new_password"]);
if($newpass != $renewpass){header("location: logout.php");}

$conn = new mysqli("localhost","root","toor", "IERG4210");

if(!$conn){
	die("connection failed: " . mysqli_connect_error());
}

$q = "SELECT salt from accounts where user=?";
$sql = $conn->prepare($q);
$sql->bind_param('s',$user);
$sql->execute();
$result = $sql->get_result();
if($result->num_rows > 0){
	$row = $result->fetch_assoc();
	$salt= $row['salt'];
	$password = hash_hmac('sha256', $pass, $salt);
	$q2 = "SELECT user FROM accounts WHERE user=? and password=?";
	$sql2 = $conn->prepare($q2);
	$sql2->bind_param('ss',$user, $password);
	$sql2->execute();
	$result2 = $sql2->get_result();
	if($result2->num_rows > 0){
    $newpassword = hash_hmac('sha256', $newpass, $salt);
    $q3 = "UPDATE accounts SET password=? WHERE user=? and password=?";
    $sql3 = $conn->prepare($q3);
    $sql3->bind_param('sss', $newpassword, $user, $password);
    $sql3->execute();
    header("location: logout.php");
	}else{
    header("location: logout.php");
  }
}else{
	$sql->close();
	$conn->close();
  header("location: logout.php");
}
$conn->close();
?>
