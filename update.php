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
        try {
          generateThumbnail("img/products/" . $file_name, 100, 50, 65);
        }catch (ImagickException $e) {
          echo $e->getMessage();
        }catch (Exception $e) {
          echo $e->getMessage();
        }
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
