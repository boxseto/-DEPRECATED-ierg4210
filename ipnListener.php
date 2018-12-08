<?php
namespace Listener;
require('PaypalIPN.php');
use PaypalIPN;

$ipn = new PaypalIPN();

$ipn->useSandbox();
$verified = $ipn->verifyIPN();
if ($verified){
$conn = new mysqli("localhost", "root", "toor", "IERG4210");
$q = "UPDATE orders SET tid=? WHERE digest=?";
$sql = $conn->prepare($q);
$sql->bind_param('ssi', $_POST["txn_id"], $_POST["custom"]);
$sql->execute();
$conn->close();
}

header("HTTP/1.1 200 OK");
echo "abc";
?>
