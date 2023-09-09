<? require_once 'include/_dbcon.php'; ?>
<?
//pega os dados e busca a prova
$id_part = $_GET['id_part'];
$id_survey = $_GET['id_survey'];
$id_grupo = $_GET['id_grupo'];

//checar se ele ja nao respondeu
$sql_fezprova = "SELECT * FROM respostas WHERE id_part = '$id_part' AND id_aval = '$id_survey' AND id_grupo = '$id_grupo'";
$result = $conn->query($sql_fezprova);

// Verificar se há resultados
if ($result->num_rows > 0) {

  // Redirecionar para a página desejada
  header("Location: teste_concluido.php");
  exit();

} else {

  // Procurar lider do grupo
  $sql_survey = "SELECT p.nome
  FROM participantes_grupo pg
  JOIN participantes p ON pg.id_participante = p.id
  WHERE pg.tipo_participante = 'participante1'
  AND pg.id_grupo = '$id_grupo'";

  $result_survey = $conn->query($sql_survey);

  $data_survey = array();
  while ($row_survey = $result_survey->fetch_assoc()) {
    $data_survey[] = $row_survey;
  }

}

?>
<!DOCTYPE html>
<html lang="pt-BR">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>LiderScan - Avaliação:
      <? echo $data_survey[0]["nome"]; ?>
    </title>
    <?php include 'include/_head.php' ?>
  </head>

  <body class="test">
    <? include 'include/_toasters.php' ?>
    <nav class="navbar navbar-expand-lg
                        sticky-top
                        mb-3 mb-sm-5"
         style="background-color: #6929F3;"
         data-bs-theme="dark">
      <div class="container-fluid">
        <a class="navbar-brand"
           href="#">LiderScan - Avaliação</a>

    </nav>

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
          <div class="mb-3 pergunta">
            <small>Avaliação sobre:</small>
            <h2>
              <? echo $data_survey[0]["nome"]; ?>
            </h2>
          </div>
          <form id="aval_send">
            <?
            // Consulta SQL
            $sql = "SELECT q.id, q.id_category, q.pergunta, cq.nome AS categoria
            FROM perguntas q
            JOIN perguntas_categoria cq ON q.id_category = cq.id;
          ";
            $result = $conn->query($sql);

            // Exibe os resultados
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {

                // Exibe id_category apenas quando mudar
                if ($row["categoria"] !== $previousCategory) {
                  echo '<h4 class="mt-5">' . $row["categoria"] . '</h4>';
                  $previousCategory = $row["categoria"];
                }

                echo '<div class="mb-3 pergunta">';

                // Pergunta
                echo '<p>' . $row["pergunta"] . '</p>';

                // Loop para criar as opções de radio com IDs numéricos únicos
                $options = array(
                  "Discordo Totalmente",
                  "Discordo parcialmente",
                  "Sem opinião",
                  "Concordo parcialmente",
                  "Concordo totalmente"
                );

                // Opções
                $pontos = 0;
                $idCounter = 1;
                foreach ($options as $option) {
                  $inputId = "radio_" . $row["id"] . "_" . $idCounter;
                  $pontos++;

                  echo '<div class="form-check">';
                  echo '<input class="form-check-input" type="radio" name="' . $row["id"] . '" id="' . $inputId . '" value="' . $pontos . '"  required >';
                  echo '<label class="form-check-label" for="' . $inputId . '">' . $option . '</label>';
                  echo '</div>';

                  $idCounter++;
                }

                echo '</div>';
              }
            } else {
              echo "Nenhum resultado encontrado.";
            }
            ?>

            <div class="d-grid gap-2 d-md-block">
              <button class="btn btn-primary mb-3"
                      type="submit"><span class="btn-label"><i class="fa fa-check me-2"></i></span>Enviar Avaliação</button>
            </div>

          </form>
        </div>
      </div>
    </div>
    </div>

    <?php include 'include/_jscripts.php' ?>

    <script>
      $(document).ready(function () {

        $("#aval_send").submit(function (event) {
          event.preventDefault(); // Impedir que o formulário seja enviado tradicionalmente
          var formData = $(this).serialize(); // Serializar os campos do formulário
          var formDataPairs = formData.split('&');
          var formDataObject = {};

          // Loop para processar cada par chave-valor
          formDataPairs.forEach(function (pair) {
            var keyValue = pair.split('=');
            var key = keyValue[0];
            var value = parseInt(keyValue[1]);

            formDataObject[key] = value;
          });

          // Converter o objeto em JSON
          var formDataJson = JSON.stringify(formDataObject);

          $.ajax({
            type: "POST",
            url: "../include/api.php",
            data: {
              resposta: formDataJson, // Incluir o objeto JSON no corpo da solicitação
              id_survey: '<? echo $id_survey ?>',
              id_part: '<? echo $id_part ?>',
              id_grupo: '<? echo $id_grupo ?>',
              indicador: "aval_send"
            },
            success: function (response) {
              if (response === "ok") {
                criarToastmini('success', 'Avaliação feita com sucesso! Redirecionando em 3 segundos...');


                window.location.href = "teste_concluido.php";

              } else {
                criarToastmini('danger', response);
              }
            },
            error: function (xhr, status, error) {
              //notyf.error(error);
              criarToastmini('danger', error);
            }
          });

        });

      });
    </script>

  </body>

</html>