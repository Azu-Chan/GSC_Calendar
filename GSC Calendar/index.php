<?php 
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if(!isset($_SESSION["mail"])) header("location:login.php");
else(header("location:calendrier.php"));
?>