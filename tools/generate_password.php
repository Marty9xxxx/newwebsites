<?php
$password = "test123"; // testovací heslo
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Heslo: $password\n";
echo "Hash: $hash\n";