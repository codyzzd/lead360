<?php
session_start();

$time_now = time();
$session_timeout = 60 * 60 * 12; // 12 horas em segundos
$time_last_activity = session_last_activity();

if ($time_now - $time_last_activity > $session_timeout) {
  // O usuário está inativo, então deslogá-lo.
  session_destroy();
  header("Location: ../admin/login.php");
  exit();
}

// O usuário está ativo, então continue.