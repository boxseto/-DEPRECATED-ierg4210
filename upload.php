<?php
$new_cat = isset($_REQUEST["new_category"]) ? htmlspecialchars($_REQUEST["new_category"]): "0";
$cat = isset($_REQUEST["category"]) ? htmlspecialchars($_REQUEST["category"]): "0";
$name = isset($_REQUEST["name"]) ? htmlspecialchars($_REQUEST["name"]): "0";
$price = isset($_REQUEST["price"]) ? htmlspecialchars($_REQUEST["price"]): "0";
$desc = isset($_REQUEST["description"]) ? htmlspecialchars($_REQUEST["description"]): "0";
$mode = isset($_REQUEST["mode"]) ? htmlspecialchars($_REQUEST["mode"]): "0";

$conn = new mysqli("localhost", "root", "toor", "IERG4210");
if( $mode == "0" ){
	$sql = "INSERT INTO categories (name) VALUES ( \"$new_cat\" )";
	if( $conn->query($sql) === TRUE ){
		header("Location: admin.html");
	}else{
		header("Location: admin_add.php");
	}
}else if($mode == "1"){
	$sql = "INSERT INTO products (catid, name, price, description, image) VALUES ( $cat, \"$name\", \"$price\", \"$desc\", \"" . $_FILES['image']['name'] . "\")";
	if( $conn->query($sql) === TRUE ){
		if(isset($_FILES['image'])){
			$errors = array();
			$file_name = $_FILES['image']['name'];
			$file_size = $_FILES['image']['size'];
			$file_tmp = $_FILES['image']['tmp_name'];
			$file_type = $_FILES['image']['type'];
			$file_ext = strtolower(end(explode('.',$_FILES['image']['name'])));
			$expensions = array("jpeg", "jpg", "png");
			if(in_array($file_ext,$expensions)===false){
				$errors[]="extensions not allowed";
			}
			if($file_size > 2097152){
				$errors[]="File larger than 2MB";
			}
			if(empty($errors)==true){
				move_uploaded_file($file_tmp,"img/products/" . $file_name);
				header("Location: admin.php");	
			}else{
				echo "fail file upload";
				//header("Location: admin_add.php");	
			}
		}
	}else{
		echo $sql . "fail insert";
		//header("Location: admin_add.php");
	}
}

$conn->close();
?>
