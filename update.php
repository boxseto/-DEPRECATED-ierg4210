<?php
function generateThumbnail($img, $width, $height, $quality = 90)
{
    if (is_file($img)) {
        $imagick = new Imagick(realpath($img));
        $imagick->setImageFormat('jpeg');
        $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
        $imagick->setImageCompressionQuality($quality);
        $imagick->thumbnailImage($width, $height, false, false);
        $filename_no_ext = reset(explode('.', $img));
        if (file_put_contents($filename_no_ext . '_thumb' . '.jpg', $imagick) === false) {
            throw new Exception("Could not put contents.");
        }
        return true;
    }
    else {
        throw new Exception("No valid image provided with {$img}.");
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
