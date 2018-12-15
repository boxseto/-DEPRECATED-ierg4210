<?php
require_once("authchk.php");
function csrf_verifynonce($action, $nonce){
	if(isset($nonce) && $_SESSION['csrf_nonce'][$action] == $nonce){
		if($_SESSION['4210proj'] == null)
			unset($_SESSION['csrf_nonce'][$action]);
		return true;
	}
	return false;
}


$user = htmlspecialchars($_REQUEST["username"]);
$pass = htmlspecialchars($_REQUEST["password"]);
$nonce = htmlspecialchars($_REQUEST["nonce"]);
$reset = htmlspecialchars($_REQUEST["reset"]);
if(!csrf_verifynonce('reset_pw',$nonce)){header('location: login.php');}

$conn = new mysqli("localhost","root","toor", "IERG4210");

if(!$conn){
	die("connection failed: " . mysqli_connect_error());
}

$q = "SELECT salt from accounts where user=? and reset=?";
$sql = $conn->prepare($q);
$sql->bind_param('ss',$user, $reset);
$sql->execute();
$result = $sql->get_result();
if($result->num_rows > 0){
	$salt = mt_rand() . mt_rand();
	$newpassword = hash_hmac('sha256', $pass, $salt);
	$q3 = "UPDATE accounts SET password=? , salt=? WHERE user=? and reset=?";
	$sql3 = $conn->prepare($q3);
	$sql3->bind_param('ssss', $newpassword, $salt, $user, $reset);
	$sql3->execute();
	header("location: logout.php");
}else{
	$sql->close();
	$conn->close();
  header("location: logout.php");
}
$conn->close();
?>
