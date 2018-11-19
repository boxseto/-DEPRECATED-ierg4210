<?php
require_once("authchk.php");
if(!authchk()){header('location: index.php');}
?>
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
  
</head>

<body>
<!--------------------    contents    --------------------------------------->
<div class="container-fluid">
	<div class="row">
<!-----------------    Navigation    --------------------------------->
  <div class="col-2 header">
    <h2>Admin</h2>
    <h3>Table</h3>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="#"><i class="fas fa-table"></i> Table</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin_add.php"><i class="fas fa-plus-circle"></i> Add</a>
      </li>
    </ul>
  </div>
  

  <div class="col-10">
		<div class="panel">
			<div class="float-right">
				<a class="btn btn-success btn-filter" href="admin_add.php">Add</a>
			</div>
			<table class="table table-filter">
				<tbody>
					<?php
						$conn = new mysqli("localhost", "root", "toor", "IERG4210");
						$sql = "SELECT * FROM products ORDER BY catid, pid ASC";
						$result = $conn->query($sql);
						if($result->num_rows > 0){
							while($row = $result->fetch_assoc()){
								echo "<tr class=\"row\">\n<td class=\"col-1\">\n
              <img src=\"img/products/" . htmlspecialchars($row["image"]) . "\">\n
            </td>\n<td class=\"col-10\">\n<div class=\"media-body\">\n
									<h4 class=\"title\">\n" . htmlspecialchars($row["name"]) . "\n</h4>\n
									<p class=\"summary\">" . htmlspecialchars($row["description"]) . "</p>\n
								</div>\n
            </td>\n
						<td class=\"col-1\">\n
             <form method=\"post\" action=\"admin_info.php\">\n
							 <input type=\"hidden\" name=\"pid\" value=\"" . htmlspecialchars($row["pid"]) . "\" ></input>\n
							 <input type=\"submit\" class=\"btn btn-secondary\" value=\"More\" ></input>\n</form>
            </td>
					</tr>";
							}
						}
					?>
			  </tbody>
			</table>
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
