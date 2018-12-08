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
        <a class="nav-link" href="admin.php"><i class="fas fa-table"></i> Table</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin_add.php"><i class="fas fa-plus-circle"></i> Add</a>
      </li>
	<li class="nav-item">
        <a class="nav-link" href="#"><i class="fas fa-exchange-alt"></i> Orders</a>
      </li>
	<li class="nav-item">
        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </li>
    </ul>
  </div>
  

  <div class="col-10">
		<div class="panel">
			<div class="float-right">
				<a class="btn btn-success btn-filter" href="admin_add.php">Add</a>
			</div>
			<table class="table table-filter">
				<thead>
					<tr class="row">
						<th class="col-1">oid</th>
						<th class="col-2">user</th>
						<th class="col-5">digest</th>
						<th class="col-2">salt</th>
						<th class="col-2">tid</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$conn = new mysqli("localhost", "root", "toor", "IERG4210");
						$sql = "SELECT * FROM orders ORDER BY oid ASC";
						$result = $conn->query($sql);
						if($result->num_rows > 0){
							while($row = $result->fetch_assoc()){
								echo "<tr class=\"row\"><td class=\"col-1\"> <p class=\"id\">".htmlspecialchars($row["oid"]) ."</p></td><td class=\"col-2\"> <p class=\"user\">".htmlspecialchars($row["user"]) ."</p></td><td class=\"col-5\"> <p class=\"digest\">".htmlspecialchars($row["digest"]) ."</p></td><td class=\"col-2\"> <p class=\"salt\">".htmlspecialchars($row["salt"]) ."</p></td><td class=\"col-2\"> <p class=\"tid\">".htmlspecialchars($row["tid"]) ."</p></td></tr>";
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
