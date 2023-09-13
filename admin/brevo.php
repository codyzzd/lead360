<?php
//atualizar banco
header("Access-Control-Allow-Origin: *");
// Conexão com o banco de dados
require_once '_dbcon.php';

$dataatual = getDataAtualSaoPaulo();

$sql_cota = "UPDATE brevo, data
    SET cota = 300, data = '$dataatual'";
$res_cota = $conn->query($sql_cota);


function getDataAtualSaoPaulo()
{
  date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo
  return date('Y-m-d H:i:s'); // Obtém a data e hora atual formatada
}

?>