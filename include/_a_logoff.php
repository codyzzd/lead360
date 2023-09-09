<?php
// Iniciar a sessão
session_start();

// Destruir todas as variáveis de sessão
session_unset();

// Encerrar a sessão
session_destroy();

// Redirecionar para a página de login
header("Location: ../admin/login.php");
exit();
