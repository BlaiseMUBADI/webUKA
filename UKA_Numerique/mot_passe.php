<?php
$password = "Blaise@mub5991";
$hashed_password = password_hash($password,PASSWORD_BCRYPT);
$hashed_password2 = sha1($password);
echo "HASH AVEC pasword_hash : " . $hashed_password."</br> HASH AVEC SHA1 ".$hashed_password2;
?>