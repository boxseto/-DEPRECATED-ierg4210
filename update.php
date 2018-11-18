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
if(!csrf_verifynonce('update_product',$nonce)){header('location: login.php');}

$pid = htmlspecialchars($_POST['pid']);
$catid = preg_match("^\d+$",htmlspecialchars($_POST['catid'])) ? htmlspecialchars($_POST['catid']): "0" ;
$name = preg_match("^[\w\-]+$",htmlspecialchars($_POST['name'])) ? htmlspecialchars($_POST['name']): "0";
$price =  preg_match("^\d+\.?\d*$",htmlspecialchars($_POST['price'])) ? htmlspecialchars($_POST['price']): "0";
$desc = trim($_POST['desc']);
$conn = new mysqli("localhost", "root", "toor", "IERG4210");

if( isset($_POST['change'])){
	$q = "UPDATE products SET catid=?, name=?, price=?, description=? WHERE pid=?";
  $sql = $conn->prepare($q);
  $sql->bind_param('isssi', $catid, $name, $price, $desc, $pid);
  $sql->execute();
  if($sql->affected_rows != 0 && !empty($_FILES['image']['name']) && isset($_FILES['image'])){
		$q2 = "UPDATE products SET image=? WHERE pid=?";
    $sql2 = $conn->prepare($q2);
    $sql2->bind_param('si', $_FILES['image']['name'], $pid);
    $sql2->execute();
		if($sql2->affected_rows != 0){
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
        $q3 = "UPDATE products SET image=\"" . $file_name  . "\" WHERE pid=" . $pid;
        $sql3 = $conn->prepare($q3);
        $sql3->bind_param('si', $file_name, $pid);
        $sql3->execute();
				move_uploaded_file($file_tmp,"img/products/" . $file_name);
				header("Location: admin.php");	
			}else{
				echo "fail file upload";
				//header("Location: admin_add.php");	
			}
		}
	}
	header("Location: admin.php");
} else if ( isset($_POST['delete'])){
	$q = "SELECT * FROM products WHERE pid=?";
  $sql = $conn->prepare($q);
  $sql->bind_param('i', $pid);
  $sql->execute();
  $result = $sql->get_result();
	if($result->num_rows > 0){
		$row = $result->fetch_assoc();
		unlink('img/products/' . $row['image']);
	}
	$q = "DELETE FROM products WHERE pid=" . $pid;
  $sql = $conn->prepare($q);
  $sql->bind_param('i', $pid);
  $sql->execute();
	header("Location: admin.php");
} else if(isset($_POST['catdelete'])){
	$q = "DELETE FROM products WHERE catid=" . $catid;
  $sql = $conn->prepare($q);
  $sql->bind_param('i', $catid);
  $sql->execute();
	$q = "DELETE FROM categories WHERE catid=" . $_POST['catid'];
  $sql = $conn->prepare($q);
  $sql->bind_param('i', $catid);
  $sql->execute();
	header("Location: admin.php");
}else{
	echo "idk what are you doing";
	header("location: admin_info.php");
}

?>
