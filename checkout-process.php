<?php
function genDigest(){
error_reporting(E_ALL);
	$salt = mt_rand().mt_rand();
	$shoppingcart_info = "";
	$totalPrice = 0;
	$invoice = 0;
	$cart_info = json_decode($_POST["cart"], true);
        $conn = new mysqli("localhost", "root", "toor", "IERG4210");
	$sql = "SELECT name, price FROM products where pid = ?";
	$query = $conn->prepare($sql);
	$item_name = array();
	$item_price = array();
	foreach ($cart_info['cart'] as &$item){
		$query->bind_param('i', $item['pid']);
		$query->execute();
		$result = $query->get_result();
		$row = $result->fetch_assoc();
		$price = $row['price'];
		$totalPrice = $totalPrice + ($price*$item['quantity']);
		array_push($item_name, $row['name']);
		array_push($item_price, $row['price']);
	}
	$currency = "HKD";
	$email = "seto@link.cuhk.edu.hk";
	$digest = sha1($currency.$email.$salt.$shoppingcart_info.$totalPrice);
	$sql = "INSERT INTO orders (digest,salt,tid) VALUES (?,?,\"NOT YET\")";
	$query = $conn->prepare($sql);
	$query->bind_param('ss',$digest,$salt);
	$query->execute();	
	$invoice = $conn->insert_id;
	$returnval = json_encode(array("name"=>$item_name, "price"=>$item_price, array("digest"=>$digest, "invoice"=>$invoice)));
	return $returnval;

}
if($_GET['action'] == 'genDigest'){
	echo call_user_func('genDigest');
}
?>
