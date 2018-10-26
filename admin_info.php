<!Doctype html>
<html>

<head>

	<meta charset="utf-8">

	<!--title for the page-->
	<link rel="shortcut icon" href="img/web_icon.ico">
	<title>Admin</title>

	<!--imported css-->
	<link rel="stylesheet" href="import/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="import/fontawesome/css/all.css">
  <link rel="stylesheet" href="import/jquery-ui/jquery-ui.css">
	
	<!--self-written css-->
	<link rel="stylesheet" href="css/admin_theme.css">
	<link rel="stylesheet" href="css/admin_add_theme.css">
  
</head>

<body>
<!--------------------    contents    --------------------------------------->
<div class="container-fluid">
	<div class="row">
<!-----------------    Navigation    --------------------------------->
    <div class="col-2 header">
      <h2>Admin</h2>
      <h3>Product</h3>
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link" href="admin.php"><i class="fas fa-table"></i> Table</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="admin_add.php"><i class="fas fa-plus-circle"></i> Add</a>
        </li>
      </ul>
    </div>

    <div class="col-10">
<!-----------------    Add products    --------------------------------->
<?php
	$pid=isset($_REQUEST["pid"]) ? htmlspecialchars($_REQUEST["pid"]) : 1; 
	$conn = new mysqli("localhost","root", "toor", "IERG4210");
	$sql = "SELECT * FROM products WHERE pid=$pid";
	$result=$conn->query($sql);
	if($result->num_rows > 0){
		$row = $result->fetch_assoc();
		$catid = $row["catid"];
	}else{
		$conn->close();
		header("Location: admin.php");
	}
?>
     <div class="card">
        <div class="card-header">
          Change products info - product <?php echo $pid; ?>
        </div>
        <div class="card-body">
          <form action="update.php" method="post" enctype="multipart/form-data">
						<input type="hidden" value="<?php echo $pid; ?>" name="pid"/>
            Choose a Category: 
            <select name="catid"> 
             <?php 
                $sql = "SELECT catid, name from categories ORDER BY catid ASC";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                  while($row = $result->fetch_assoc()){
                    echo "<option value=\"" . $row["catid"] . "\" ";
										if($row["catid"] == $catid){echo "selected";}
										echo ">" . $row["name"] . "</option>";
                  }
                }
								$sql = "SELECT * FROM products WHERE pid=$pid";
								$result=$conn->query($sql);
								if($result->num_rows > 0){
									$row = $result->fetch_assoc();
								}else{
									$conn->close();
									header("Location: admin.php");
								}
              ?>
           </select>
						<input type="submit" class="btn btn-warning" name="catdelete" value="Delete this cateory and all its products"/>
            <br><br>
            Change Name: <input type="text" name="name" required="required" value="<?php echo $row["name"];?>"/><br><br>
            Change Price: $<input type="number" min="0" name="price" value="<?php echo $row["price"];?>"/><br><br>
            Change Description: <br><textarea name="desc" cols="50" rows="10"><?php echo $row["description"];?></textarea><br><br>
            Change image (Current is <?php echo $row["image"];?>): <input type="file" name="image"/><br><br>
            <input type="submit" class="btn btn-secondary" name="change" value="Change"/>
            <input type="submit" class="btn btn-warning" name="delete" value="Delete"/>
          </form>
        </div>
      </div>
<?php
$conn->close();
?>
    </div>
    
  </div>
</div>




<!--imported js-->
<script src="import/jquery.js"></script>
<script src="import/bootstrap/js/bootstrap.js"></script>
<script src="import/bootstrap/js/bootstrap.bundle.js"></script>
<script src="import/jquery-ui/jquery-ui.js"></script>


<!--self-written js-->


</body>
</html>
