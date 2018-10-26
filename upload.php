<?php
//credit to alex
//https://stackoverflow.com/questions/11376315/creating-a-thumbnail-from-an-uploaded-image
function makeThumbnails($updir, $img)
{
    $thumbnail_width = 134;
    $thumbnail_height = 189;
    $thumb_beforeword = "thumb";
    $arr_image_details = getimagesize("$updir" . "$img"); // pass id to thumb name
    $original_width = $arr_image_details[0];
    $original_height = $arr_image_details[1];
    if ($original_width > $original_height) {
        $new_width = $thumbnail_width;
        $new_height = intval($original_height * $new_width / $original_width);
    } else {
        $new_height = $thumbnail_height;
        $new_width = intval($original_width * $new_height / $original_height);
    }
    $dest_x = intval(($thumbnail_width - $new_width) / 2);
    $dest_y = intval(($thumbnail_height - $new_height) / 2);
    if ($arr_image_details[2] == IMAGETYPE_GIF) {
        $imgt = "ImageGIF";
        $imgcreatefrom = "ImageCreateFromGIF";
    }
    if ($arr_image_details[2] == IMAGETYPE_JPEG) {
        $imgt = "ImageJPEG";
        $imgcreatefrom = "ImageCreateFromJPEG";
    }
    if ($arr_image_details[2] == IMAGETYPE_PNG) {
        $imgt = "ImagePNG";
        $imgcreatefrom = "ImageCreateFromPNG";
    }
    if ($imgt) {
        $old_image = $imgcreatefrom("$updir" . "$img");
        $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
        imagecopyresized($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
        $imgt($new_image, "$updir" . "$thumb_beforeword" . "$img");
    }
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
        try {
          makeThumbnails("img/products/" . $file_name);
        }catch (Exception $e) {
          echo $e->getMessage();
        }
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
