<?php
session_start();
function setAuth($username, $pass, $salt){
	$exp = time() + 3600*24*3;
	$token = array(
		'em'=>$username,
		'exp'=>$exp,
		'k'=>hash_hmac('sha1',$exp.$pass, $salt)
	);
	setcookie('4210proj', json_encode($token), $exp,'','',false,true);
	$_SESSION['4210proj'] = $token;
	session_regenerate_id();
}

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
$mode = htmlspecialchars($_REQUEST["mode"]);
$nonce = htmlspecialchars($_REQUEST["nonce"]);
if( $mode != 0 && $mode != 1){$mode = 0;}
if($mode == 0){
	if(!csrf_verifynonce('usr_login',$nonce)){header('location: login.php');}
}else if (mode == 1){
	if(!csrf_verifynonce('admin_login',$nonce)){header('location: login.php');}
}
$conn = new mysqli("localhost","root","toor", "IERG4210");

if(!$conn){
	die("connection failed: " . mysqli_connect_error());
}

$q = "SELECT salt from accounts where user=? and mode=\"" . $mode . "\"";
$sql = $conn->prepare($q);
$sql->bind_param('s',$user);
$sql->execute();
$result = $sql->get_result();
if($result->num_rows > 0){
	$row = $result->fetch_assoc();
	$salt= $row['salt'];
	$password = hash_hmac('sha256', $pass, $salt);
	$q2 = "SELECT user FROM accounts WHERE user=? and password=? and mode=\"" . $mode . "\"";
	$sql2 = $conn->prepare($q2);
	$sql2->bind_param('ss',$user, $password);
	$sql2->execute();
	$result2 = $sql2->get_result();
	if($result2->num_rows > 0){
		$sql->close();
		$sql2->close();
		$conn->close();
		setAuth($user,$password,$salt);
		if($mode == "0") header('Location: index.php');
		if($mode == "1") header('Location: admin.php');
	}
}else{
	$sql->close();
	$conn->close();
	header('Location: login.php');
}
$conn->close();
?>
