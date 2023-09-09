<?php
// Configurações do banco de dados
$host = 'sql200.infinityfree.com';         // Nome do servidor MySQL (geralmente 'localhost')
$database = 'if0_34821014_lead360'; // Nome do banco de dados
$username = 'if0_34821014';   // Nome de usuário do MySQL
$password = 'daEPMi3ZKE8AK0';     // Senha do MySQL

// Conexão com o banco de dados
$conn = new mysqli($host, $username, $password, $database);

// Verificar se a conexão teve sucesso
if ($conn->connect_error) {
  die('Erro na conexão: ' . $conn->connect_error);
}

// Definir o conjunto de caracteres para UTF-8
$conn->set_charset('utf8');
