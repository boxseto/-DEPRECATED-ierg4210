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
<!-------------------    contents    -------------------------------------->
<div class="container-fluid">
	<div class="row">
<!----------------       Navigation   -------------------------------->
    <div class="col-2 header">
      <h2>Admin</h2>
      <h3>Add</h3>
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link" href="admin.html"><i class="fas fa-table"></i> Table</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><i class="fas fa-plus-circle"></i> Add</a>
        </li>
      </ul>
    </div>

    <div class="col-10">
<!-----------------    Add category    --------------------------------->
      <div class="card">
        <div class="card-header">
          Add category
        </div>
        <div class="card-body">
          <form method="POST" action="upload.php">
            Add a Category: <input type="text" name="new_category"><br><br>
            <input type="submit" class="btn btn-secondary" value="Add">
          </form>
        </div>
      </div>
      
<!-----------------    Add products    --------------------------------->
      <div class="card">
        <div class="card-header">
          Add products
        </div>
        <div class="card-body">
          <form method="POST" action="upload.php">
            Choose a Category: 
            <select name="category">
              <?php 
                $conn = new mysqli("localhost","root", "toor", "IERG4210");
                $sql = "SELECT name from categories ORDER BY catid ASC";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                  while($row = $result->fetch_assoc()){
                    echo "<option value=\"" . $row["name"] . "\">" . $row["name"] . "</option>"
                  }
                }
                $conn->close();
              ?>
            </select>
            <br><br>
            Product Name: <input type="text" name="name"><br><br>
            Product Price: $<input type="number" min="0" name="price"><br><br>
            Product Description: <br><textarea name="paragraph_text" naem="description" cols="50" rows="10"></textarea><br><br>
            Product image: <input type="file" name="image"><br><br>
            <input type="submit" class="btn btn-secondary" value="Add">
          </form>
        </div>
      </div>
      
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