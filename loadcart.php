<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	$final = '';
	$json = json_decode(file_get_contents('php://input'), true);
	$conn = new mysqli("localhost", "root", "toor", "IERG4210");
	for ($i = 0 ; $i < sizeof($json["cart"]) ; $i++){
		$sql = "SELECT * FROM products WHERE pid=" . $json["cart"][$i]["pid"];
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$row = $result->fetch_assoc();
			$final .= '<li><div class="row">' .
					'  <a href="product.php?pid='. htmlspecialchars($row["pid"]) .'"> <img src="img/products/'. htmlspecialchars($row["image"]) .'">  </a>' .
					'  <a href="product.php" class="title">'. htmlspecialchars($row["name"]) .'</a>' .
        				'  <div class="quantity"> <input class="qty" min="0" value="1" > </div>' .
        				'  <div class="info"> <div class="price">$'. htmlspecialchars($row["price"]) .'</div> <a href="#"> Delete </a> </div>' .
        				'</div></li>';

		}
	}
	$conn->close();
	echo $final;
?>
