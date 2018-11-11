<?php
echo ($salt = mt_rand())."<br/>";
echo hash_hmac('sha256', 'user',$salt) . "<br/>";
echo sha256('admin'.$salt);
?>
