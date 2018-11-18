<?php
require_once("authchk.php");
if(!authchk()){header('location: index.php');}
?>
<?php
function csrf_verifynonce($action, $nonce){
	if(isset($nonce) && $_SESSION['csrf_nonce'][$action] == $nonce){
		if($_SESSION['4210proj'] == null)
			unset($_SESSION['csrf_nonce'][$action]);
		return true;
	}
	return false;
}

$nonce = htmlspecialchars($_REQUEST["nonce"]);
$new_cat = isset($_REQUEST["new_category"]) && preg_match("^[\w\-]+$",htmlspecialchars($_REQUEST["new_category"])) ? htmlspecialchars($_REQUEST["new_category"]) : "0";
$cat = isset($_REQUEST["category"]) && preg_match("^\d+$",htmlspecialchars($_REQUEST["new_category"])) ? htmlspecialchars($_REQUEST["category"]): "0";
$name = isset($_REQUEST["name"]) && preg_match("^[\w\-]+$",htmlspecialchars($_REQUEST["new_category"])) ? htmlspecialchars($_REQUEST["name"]): "0";
$price = isset($_REQUEST["price"]) && preg_match("^\d+\.?\d*$",htmlspecialchars($_REQUEST["new_category"])) ? htmlspecialchars($_REQUEST["price"]): "0";
$desc = isset($_REQUEST["description"]) ? htmlspecialchars($_REQUEST["description"]): "0";
$mode = isset($_REQUEST["mode"]) && preg_match("^\d$",htmlspecialchars($_REQUEST["new_category"])) ? htmlspecialchars($_REQUEST["mode"]): "0";

$conn = new mysqli("localhost", "root", "toor", "IERG4210");
if( $mode == "0" ){
	if(!csrf_verifynonce('add_cat',$nonce)){header('location: login.php');}
	$q = "INSERT INTO categories (name) VALUES ( ? )";
	$sql = $conn->prepare($q);
	$sql->bind_param('s', $new_cart);
	$sql->execute();
	if( $sql->affected_rows === 0 ){
		header("Location: admin_add.php");
	}else{
		header("Location: admin.php");
	}
}else if($mode == "1"){
	if(!csrf_verifynonce('add_product',$nonce)){header('location: login.php');}
	$q = "INSERT INTO products (catid, name, price, description, image) VALUES (?,?,?,?,?)";
	$sql = $conn->prepare($q);
	$sql->bind_param('issss',$cat, $name, $price, $desc, $_FILES['image']['name']);
	$sql->execute();
	if( $sql->affected_rows  === 0 ){
		echo "fail insert";
		//header("Location: admin_add.php");
	}else{
		$pid = $conn->insert_id;
		if(isset($_FILES['image'])){
			$errors = array();
			$file_size = $_FILES['image']['size'];
			$file_tmp = $_FILES['image']['tmp_name'];
			$file_type = $_FILES['image']['type'];
			$file_ext = strtolower(end(explode('.',$_FILES['image']['name'])));
			$expensions = array("jpeg", "jpg", "png");
			$file_name = $pid . "." . $file_ext;
			if(in_array($file_ext,$expensions)===false){
				$errors[]="extensions not allowed";
			}
			if($file_size > 2097152){
				$errors[]="File larger than 2MB";
			}
			if(empty($errors)==true){
				$q2 = "UPDATE products SET image=? WHERE pid=?";
				$sql2 = $conn->prepare($q2);
				$sql2->bind_param('si', $file_name, $pid);
				$sql2->execute();
				move_uploaded_file($file_tmp,"img/products/" . $file_name);
				header("Location: admin.php");	
			}else{
				echo "fail file upload";
				//header("Location: admin_add.php");	
			}
		}
	}
}

$conn->close();
?>
