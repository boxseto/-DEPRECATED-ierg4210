<!Doctype html>
<html>
<?php
	function csrf_getNonce($action){
		$nonce = mt_rand() . mt_rand();
		if(!isset($_SESSION['csrf_nonce']))
			$SESSION['csrf_nonce'] = array();
		$_SESSION['csrf_nonce'][$action] = $nonce;
		return $nonce;
	}
?>

<head>

	<meta charset="utf-8">

	<!--title for the page-->
	<link rel="shortcut icon" href="img/web_icon.ico">
	<title>Login</title>

	<!--imported css-->
	<link rel="stylesheet" href="import/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="import/fontawesome/css/all.css">
  <link rel="stylesheet" href="import/OwlCarousel/dist/assets/owl.carousel.min.css">
  <link rel="stylesheet" href="import/OwlCarousel/dist/assets/owl.theme.default.min.css">
  <link rel="stylesheet" href="import/jquery-ui/jquery-ui.css">
	
	<!--self-written css-->
	<link rel="stylesheet" href="css/index_theme.css">
	<link rel="stylesheet" href="css/login_theme.css">
  
</head>

<body>
<!-----------------    header    --------------------------------->
	<header class="header">
		<div class="user_header row">
			<div class="container-fluid">
        <div class="row">
          <ul id="cat_list">
            <li>
              <a href="index.php">
                Home
              </a>
            </li>
          </ul>
          <ul id="user_list">
            <li>
              <a href="login.php">
                Sign in
                <i class="fas fa-sign-in-alt"></i>
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
            <a class="navbar-brand" href="index.html">
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
<!--------------------    contents    --------------------------------------->
<div class="container-fluid">
  <div class="row cat_title">
    <ul class="breadcrumb">
      <li><a href="index.html">Home</a></li>
      <li><a href="login.php">Login</a></li>
      <li><a>Reset Password</a></li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12 login-form-1">
      <h3>Change Password</h3>
      <form method="POST" action="forgetpwchk.php">
	<input type="hidden" name="nonce" value="<?php echo csrf_getNonce('forget_pw');?>"/>
        <input type="text" name="username" class="form-control" placeholder="Username" required/>
        <input type="email" name="email" class="form-control" placeholder="email to recover" required/>
        <input type="submit" class="btnSubmit" value="Change" />
      </form>
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
