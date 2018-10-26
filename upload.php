<?php
//https://stackoverflow.com/questions/11376315/creating-a-thumbnail-from-an-uploaded-image
function make_thumb($src, $dest, $desired_width) {

    /* read the source image */
    $source_image = imagecreatefromjpeg($src);
    $width = imagesx($source_image);
    $height = imagesy($source_image);

    /* find the "desired height" of this thumbnail, relative to the desired width  */
    $desired_height = floor($height * ($desired_width / $width));

    /* create a new, "virtual" image */
    $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

    /* copy source image at a resized size */
    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

    /* create the physical thumbnail image to its destination */
    imagejpeg($virtual_image, $dest);
}
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
		header("Location: admin.php");
	}else{
		header("Location: admin_add.php");
	}
}else if($mode == "1"){
	$sql = "INSERT INTO products (catid, name, price, description, image) VALUES ( $cat, \"$name\", \"$price\", \"$desc\", \"" . $_FILES['image']['name'] . "\")";
	if( $conn->query($sql) === TRUE ){
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
				$sql = "UPDATE products SET image=\"" . $file_name . "\" WHERE pid=" . $pid;
				$conn->query($sql);
				move_uploaded_file($file_tmp,"img/products/" . $file_name);
        make_thumb("img/products/" . $file_name, "img/products/" . "thumb_" . $file_name, "200");
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
