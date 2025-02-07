<?php
session_start();
require_once "db.php"; // Aby se to nenačítalo víckrát
?>
<html>
<header>
    <h1><a href="index.php">Svatoušek</a></h1>
    <nav>
        <ul>
            <li><a href="index.php">Úvod</a></li>
            <li><a href="contact.php">Napište mi</a></li>
            <li><a href="news.php">Novinky</a></li>
            <li><a href="admin.php">Administrace</a></li>
            <li><a href="error404.php">Nenalezeno</a></li>
            <li><a href="guestbook.php">Návštěvní kniha</a></li>
        </ul>
    </nav>
</header>
