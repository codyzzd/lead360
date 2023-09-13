<?php
//atualizar banco
header("Access-Control-Allow-Origin: *");
// Conexão com o banco de dados
require_once '_dbcon.php';

$sql_cota = "UPDATE brevo
      SET cota = 300";
$res_cota = $conn->query($sql_cota);


?>