<?php
session_start();
function authchk(){
	if(!empty($_SESSION['4210proj']))
		return $_SESSION['4210proj']['em'];
	if(!empty($_COOKIE['4210proj'])){
		if($t = json_decode(stripslashes($_COOKIE['4210proj']), true)){
			if(time() > $t['exp']) return false;
			$conn = new mysqli("localhost","root", "toor", "IERG4210");
			$q = "SELECT * FROM accounts WHERE user=?";
			$sql = $conn->prepare($q);
			$sql->bind_param('s',$t['em']);
			$sql->execute();
			$result = $sql->get_result();
			if($result->num_rows > 0){
				$row = $result->fetch_assoc();
				$realk=hash_hmac('sha1', $t['exp'].$row['password'], $row['salt']);
				if($realk == $t['k']){
					$_SESSION['4210proj'] = $t;
					return $t['em'];
				}
			}
		}
	}
	return false;
}
?>
