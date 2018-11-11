<!Doctype html>
<html>

<head>

	<meta charset="utf-8">

	<!--title for the page-->
	<link rel="shortcut icon" href="img/web_icon.ico">
	<title>Categories</title>

	<!--imported css-->
	<link rel="stylesheet" href="import/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="import/fontawesome/css/all.css">
  <link rel="stylesheet" href="import/OwlCarousel/dist/assets/owl.carousel.min.css">
  <link rel="stylesheet" href="import/OwlCarousel/dist/assets/owl.theme.default.min.css">
  <link rel="stylesheet" href="import/jquery-ui/jquery-ui.css">
	
	<!--self-written css-->
	<link rel="stylesheet" href="css/index_theme.css">
	<link rel="stylesheet" href="css/product_theme.css">
  
</head>

<body>
<!-----------------    header    --------------------------------->
	<header class="header">
		<div class="user_header row">
			<div class="container-fluid">
        <div class="row">
          <ul id="cat_list">
            <li><a href="index.php">Home</a></li>
						<?php
							$conn = new mysqli("localhost","root","toor","IERG4210");
							$sql = "SELECT * FROM categories";
							$result = $conn->query($sql);
							if($result->num_rows > 0){
								while($row = $result->fetch_assoc()){
									echo "<li><a href=\"category.php?cat=" . $row["catid"] . "\">" . $row["name"] . "</a></li>";
								}
							}
							$conn->close();
						?>
          </ul>
          <ul id="user_list">
            <?php
              session_start();
              if(isset($_SESSION['4210proj'])){
						   echo "<li><a>" . $_SESSION['4210proj']['em'] . "<i class=\"fas fa-user\"></i></a></li>" . 
                    "<li><a href=\"logout.php\">Sign out<i class=\"fas fa-sign-out-alt\"></i></a></li>";
							}else{
               echo "<li><a href=\"login.html\">Sign in<i class=\"fas fa-sign-in-alt\"></i></a></li>"; 
              }
            ?>
            <li>
              <a id="cart" href="#">
                Shopping cart
                <i class="fas fa-shopping-cart"></i>
              </a>
            </li>
          </ul>
        </div>
      </div>
		</div>
		<div class="main_header">
			<nav class="navbar navbar-expand-lg navbar-light">
        <div class="row">
          <div class="col-1">
            <a class="navbar-brand" href="index.php">
              <img class="logo img-fluid" src="img/web_icon.png">
            </a>
          </div>
          <div class="col-11">
            <form class="search_container" action="">
              <div class="search_pt1"><input type="text" placeholder="Find what you like here.."></div>
              <div class="search_pt2"><button class="btn" type="submit"><i class="fa fa-search"></i></button></div>
            </form>
          </div>
        </div>
			</nav>
		</div>
	</header>

 <!--------------------    Expended cart    --------------------------------------->
  <div class="expand_menu">
    <div id="content">
      <ul>
      </ul>
      <div class="row total">
        <p>Total:$0</p>
        <a href="https://www.paypal.com" class="btn btn-success float-right">Checkout</a>
      </div>
    </div>
  </div>
<!--------------------    contents    --------------------------------------->
<?php
	$pid = isset($_GET['pid']) ? htmlspecialchars($_GET['pid']) : 1;
	$conn = new mysqli("localhost", "root", "toor", "IERG4210");
	$sql = "SELECT * FROM categories WHERE catid IN (SELECT catid FROM products WHERE pid=" . $pid . ")";
	$result = $conn->query($sql);
	$bread = $result->fetch_assoc();
	$sql = "SELECT * FROM products WHERE pid=". $pid;
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		$row = $result->fetch_assoc();
	}
	$conn->close();
?>

<div class="container-fluid">
  <div class="row cat_title">
    <ul class="breadcrumb">
      <li><a href="index.php">Home</a></li>
      <li><a href="category.php?cat=<?php echo $bread['catid'];?>"><?php echo $bread['name'];?></a></li>
      <li><a><?php echo $row['name'];?></a></li>
    </ul>
  </div>
  <div class="row item">
    <div class="col-4 img-zoom">
      <img id="myimage" src="<?php echo "img/products/".$row['image']; ?>">
      <div id="myresult" class="img-zoom-result"></div>
    </div>
    <div class="col-8 item-details">
      <div class="row item-title"><?php echo $row['name']; ?></div>
      <div class="row">
        <div class="col-2 item-tag">Price: </div>
        <div class="col-10 item-price">$<?php echo $row['price']; ?></div>
      </div>
      <div class="row">
        <div class="col-2 item-tag">Description: </div>
        <div class="col-10 item-description"><?php echo $row['description']; ?></div>
      </div>
      <div class="row">
        <div class="col-2 item-tag">Quantity: </div>
        <div class="col-10 quantity">
          <input class="qty" min="0" value="1" readonly>
        </div>  
      </div>
      <div class="row">
        <div class="col-2 item-tag"></div>
        <div class="col-10 item-action" id="<?php echo $row["pid"];?>">
          <button class="btn btn-primary add_cart"><i class="fa fa-plus"></i> Add to cart</button>
        </div>  
      </div>
    </div>
  </div>

<!-------------------------     Footer     ------------------------>
<footer>
  <p>Created by Seto Tsz Kin, IERG4210 proj. Hope you like it! :)</p>
  <p class="p2">(I dont know what to put here actually. but the page will seem odd if there is no footage.)</p>
</footer>



<!--imported js-->
<script src="import/jquery.js"></script>
<script src="import/bootstrap/js/bootstrap.js"></script>
<script src="import/bootstrap/js/bootstrap.bundle.js"></script>
<script src="import/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="import/OwlCarousel/dist/owl.carousel.min.js"></script>
<script src="import/jquery-ui/jquery-ui.js"></script>


<!--self-written js-->
<script src="js/index_theme.js"></script>
<script src="js/shopping_cart_index.js"></script>

</body>
</html>
