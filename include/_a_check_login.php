<?php
session_start();

if (!isset($_SESSION['user_email'])) {
  session_destroy();
  header("Location: ../admin/login.php");
  exit();
}
