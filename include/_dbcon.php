<?php
// Configurações do banco de dados
$host = 'srv1078.hstgr.io'; // Nome do servidor MySQL (geralmente 'localhost')
$database = 'u361508053_liderscan'; // Nome do banco de dados
$username = 'u361508053_liderscan'; // Nome de usuário do MySQL
$password = 'g7[Wp8/*=S@'; // Senha do MySQL

// Conexão com o banco de dados
$conn = new mysqli($host, $username, $password, $database);

// Verificar se a conexão teve sucesso
if ($conn->connect_error) {
  die('Erro na conexão: ' . $conn->connect_error);
}

// Definir o conjunto de caracteres para UTF-8
$conn->set_charset('utf8');