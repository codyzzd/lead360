<? require_once '../include/_dbcon.php'; ?>
<?
//pega os dados e busca a prova em detalhes
$id_grupo = $_GET['id'];
?>
<?
$dados_grupo = "SELECT participantes.nome FROM participantes_grupo JOIN participantes ON participantes.id = participantes_grupo.id_participante WHERE participantes_grupo.id_grupo = '$id_grupo' AND participantes_grupo.tipo_participante = 'participante1'";
$q_grupo = $conn->query($dados_grupo);
$dados_grupo = $q_grupo->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-BR">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>LiderScan - Relatório detalhado
      <? echo $dados_grupo['nome']; ?>
    </title>
    <?php include '../include/_head.php' ?>
  </head>

  <body>
    <? include '../include/_toasters.php' ?>
    <nav class="navbar navbar-expand-lg
                        sticky-top
                        mb-3 mb-sm-5"
         style="background-color: #6929F3;"
         data-bs-theme="dark">
      
  </body>

</html>