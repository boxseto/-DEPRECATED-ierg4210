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
$email = htmlspecialchars($_REQUEST["email"]);
$nonce = htmlspecialchars($_REQUEST["nonce"]);
if(!csrf_verifynonce('forget_pw',$nonce)){header('location: login.php');}

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
	$q2 = "SELECT user FROM accounts WHERE user=? and email=?";
	$sql2 = $conn->prepare($q2);
	$sql2->bind_param('ss',$user, $email);
	$sql2->execute();
	$result2 = $sql2->get_result();
	if($result2->num_rows > 0){
		if(filter_var($email, FILTER_VALIDATE_EMAIL)){
			$digest = hash('sha256', mt_rand().mt_rand());
			$message = 'We receieved a notification from you to change account password, if this is not you, please ignore the email.\r\n If you would like to change password, please click the link below:\r\n secure.s53.ierg4210.ie.cuhk.edu.hk/resetpw.php?data=' . $digest;
			$q3 = "UPDATE accounts SET reset=? WHERE user=?";
			$sql3 = $conn->prepare($q3);
			$sql3->bind_param('ss', $digest, $user);
			$sql3->execute();
			mail($email, 'PASSWORD RESET', $message);	
		}
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
