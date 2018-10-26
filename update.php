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
$pid = htmlspecialchars($_POST['pid']);
$conn = new mysqli("localhost", "root", "toor", "IERG4210");

if( isset($_POST['change'])){
	$sql = "UPDATE products SET catid=" . $_POST['catid'] . ", name=\"" . $_POST['name'] . "\", price=" . $_POST['price'] . ", description=\"" . trim($_POST['desc']) . "\" WHERE pid=" . $pid;
} else if ( isset($_POST['delete'])){
	$sql = "SELECT * FROM products WHERE pid=" . $pid;
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$row = $result->fetch_assoc();
			unlink('img/products/' . $row['image']);
		}
	$sql = "DELETE FROM products WHERE pid=" . $pid;
} else if(isset($_POST['catdelete'])){
	$sql = "DELETE FROM products WHERE catid=" . $_POST['catid'];
}else{
	echo "idk what are you doing";
}
if($conn->query($sql) === TRUE){
	if(!empty($_FILES['image']['name']) && isset($_FILES['image']) && isset($_POST['change'])){
		$sql = "UPDATE products SET image=\"" . $_FILES['image']['name'] . "\" WHERE pid=" . $pid;
		if($conn->query($sql) === TRUE){
			$errors = array(); 
			$file_size = $_FILES['image']['size'];
			$file_tmp = $_FILES['image']['tmp_name'];
			$file_type = $_FILES['image']['type'];
			$file_ext = strtolower(end(explode('.',$_FILES['image']['name'])));
			$expensions = array("jpeg", "jpg", "png");
			if(in_array($file_ext,$expensions)===false){
				$errors[]="extensions not allowed";
			}
			$file_name = $pid . "." . $file_ext;
			if($file_size > 2097152){
				$errors[]="File larger than 2MB";
			}
			if(empty($errors)==true){
				$sql = "UPDATE products SET image=\"" . $file_name  . "\" WHERE pid=" . $pid;
				$conn->query($sql);
				move_uploaded_file($file_tmp,"img/products/" . $file_name);
        make_thumb("img/products/" . $file_name, "img/products/" . "thumb_" . $file_name, "200");
				header("Location: admin.php");	
			}else{
				echo "fail file upload";
				//header("Location: admin_add.php");	
			}
		}
	} else if(isset($_POST['delete'])){
		
	} else if(isset($_POST['catdelete'])){
		$sql = "DELETE FROM categories WHERE catid=" . $_POST['catid'];
		$conn->query($sql);

	}
	$conn->close();
	header("Location: admin.php");
}else{
	$conn->close();
	header("location: admin_info.php");
}

?>
