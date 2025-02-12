<?php
session_start();
session_destroy();
header("Location: login.php");
exit;
// Purpose: To log out the user from the system and redirect to the login page.
?>
