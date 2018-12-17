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
      <h3>Reset Password</h3>
      <form method="POST" action="resetpwchk.php">
	<input type="hidden" name="nonce" value="<?php echo csrf_getNonce('reset_pw');?>"/>
	<input type="hidden" name="reset" value="<?php echo htmlspecialchars($_GET['data']);?>"/>
        <input type="text" name="username" class="form-control" placeholder="Username" required/>
        <input type="password" name="password" class="form-control" placeholder="password" required/>
        <input type="submit" class="btnSubmit" value="Reset" />
      </form>
    </div>
  </div>
</div>

<!--imported js-->
<script src="import/jquery.js"></script>
<script src="import/bootstrap/js/bootstrap.js"></script>
<script src="import/bootstrap/js/bootstrap.bundle.js"></script>
<script src="import/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="import/OwlCarousel/dist/owl.carousel.min.js"></script>
<script src="import/jquery-ui/jquery-ui.js"></script>


<!--self-written js-->


</body>
</html>
