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
      <div class="container-fluid">
        <a class="navbar-brand"
           href="#">
          <img src="/i/logonav.png"
               alt="Logo"
               height="24"
               class="d-inline-block align-text-top">LiderScan - Relatório detalhado
        </a>
    </nav>

    <div class="container mt-3 mt-sm-5 mb-3">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <small>Lider</small>
              <h2>
                <?php echo $dados_grupo['nome']; ?>
              </h2>
            </div>

            <div class="text-md-end text-end d-grid gap-2 d-md-block">
              <button class="btn btn-primary d-print-none"
                      onclick="window.print()">
                <span class="btn-label"><i class="fa fa-print me-2"></i>Imprimir</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
          <?
          // Inicialize um array para armazenar as informações de cada pergunta
          $info_perguntas = [];

          // Consulta SQL para obter todas as respostas de todos os participantes do grupo
          $respostas = "SELECT r.id_part, r.resposta, pg.tipo_participante
          FROM respostas r
          INNER JOIN participantes_grupo pg ON r.id_part = pg.id_participante AND r.id_grupo = pg.id_grupo
          WHERE r.id_grupo = '$id_grupo';";

          $q_respostas = $conn->query($respostas);

          // Loop pelos resultados das respostas dos participantes
          while ($resposta = $q_respostas->fetch_assoc()) {
            $tipo_participante = $resposta['tipo_participante'];
            $json_data = json_decode($resposta['resposta'], true);

            foreach ($json_data as $pergunta_id => $valor) {
              // Consulta para obter o texto da pergunta e a categoria
              //$q_pergunta = "SELECT p.pergunta, p.id_category FROM perguntas p WHERE p.id = '$pergunta_id'";
              $q_pergunta = "SELECT p.pergunta, x.nome FROM perguntas p JOIN perguntas_categoria x ON p.id_category = x.id WHERE p.id = '$pergunta_id'";
              $result_pergunta = $conn->query($q_pergunta);
              $row_pergunta = $result_pergunta->fetch_assoc();
              $texto_pergunta = $row_pergunta['pergunta'];
              $categoria_pergunta = $row_pergunta['nome'];

              // Adicione as informações da pergunta ao array $info_perguntas
              $info_perguntas[] = [
                'pergunta' => $texto_pergunta,
                'valor' => $valor,
                'tipo_participante' => $tipo_participante,
                'categoria' => $categoria_pergunta
              ];
            }

            // Exiba o array agrupado na tela
            /*echo '<pre>';
            print_r($info_perguntas);
            echo '</pre>';*/
          }

          // Inicialize um novo array para agrupar as perguntas
          $perguntasAgrupadas = [];

          // Inicialize um array para contar o número de participantes únicos
          $participantesUnicos = [];

          foreach ($info_perguntas as $info) {
            $pergunta = $info['pergunta'];
            $valor = $info['valor'];
            $tipoParticipante = $info['tipo_participante'];

            // Adicione o tipo de participante ao array de participantes únicos, se ainda não estiver presente
            if (!in_array($tipoParticipante, $participantesUnicos)) {
              $participantesUnicos[] = $tipoParticipante;
            }

            // Verifique se a pergunta já existe no array de perguntas agrupadas
            if (!isset($perguntasAgrupadas[$pergunta])) {
              $perguntasAgrupadas[$pergunta] = [
                'pergunta' => $pergunta,
                'valor_lider' => 0,
                'valor_outros' => 0
              ];
            }

            // Verifica se o tipo de participante é "participante1" e adiciona o valor apropriado
            if ($tipoParticipante === 'participante1') {
              $perguntasAgrupadas[$pergunta]['valor_lider'] += $valor;
            } else {
              $perguntasAgrupadas[$pergunta]['valor_outros'] += $valor;
            }
            // Adicione a chave 'categoria'
            $perguntasAgrupadas[$pergunta]['categoria'] = $info['categoria'];
          }

          // Calcula a quantidade de participantes únicos
          $numParticipantesUnicos = count($participantesUnicos);

          // Divide o valor em valor_outros pela quantidade de participantes únicos - 1
          foreach ($perguntasAgrupadas as &$perguntaAgrupada) {
            $perguntaAgrupada['valor_outros'] = intval($perguntaAgrupada['valor_outros'] / ($numParticipantesUnicos - 1));
          }


          // Exiba o array agrupado na tela
          echo '<pre style="color:blue">';
          print_r($perguntasAgrupadas);
          echo '</pre>';
          ?>
          <?
          // Inicialize um array para armazenar as perguntas agrupadas por categoria
          $perguntasPorCategoria = [];

          // Itere sobre o array de perguntas agrupadas
          foreach ($perguntasAgrupadas as $perguntaAgrupada) {
            // Obtenha a categoria associada à pergunta
            $categoria = $perguntaAgrupada['categoria'];

            // Verifique se o array de perguntas para a categoria já existe
            if (!isset($perguntasPorCategoria[$categoria])) {
              // Se não existir, inicialize um array vazio para a categoria
              $perguntasPorCategoria[$categoria] = [];
            }

            // Adicione a pergunta ao array de perguntas para a categoria
            $perguntasPorCategoria[$categoria][] = $perguntaAgrupada;
          }

          echo '<pre style="color:red">';
          print_r($perguntasPorCategoria);
          echo '</pre>';
          ?>

          <?php foreach ($perguntasPorCategoria as $categoria => $perguntas): ?>
            <!-- Início da tabela para a categoria <?php echo $categoria; ?> -->
            <table class="table table-hover mb-5 resultados">
              <thead>
                <tr>
                  <th scope="col">
                    <?php echo $categoria; ?>
                  </th>
                  <th scope="col"
                      class="col-2 text-end">Autoavaliação</th>
                  <th scope="col"
                      class="col-2 text-end">Grupo</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($perguntas as $perguntaAgrupada): ?>
                  <!-- Início de uma linha da tabela para a pergunta <?php echo $perguntaAgrupada['pergunta']; ?> -->
                  <tr>
                    <td>
                      <?php echo $perguntaAgrupada['pergunta']; ?>
                    </td>
                    <!-- Comentado porque a categoria já está sendo exibida na tabela acima -->
                    <!--<td>
                        <?php echo $perguntaAgrupada['categoria']; ?>
                    </td>-->
                    <td class=" text-end">
                      <?php echo $perguntaAgrupada['valor_lider']; ?>
                    </td>
                    <td class=" text-end">
                      <?php echo $perguntaAgrupada['valor_outros']; ?>
                    </td>
                  </tr>
                  <!-- Fim da linha da tabela -->
                <?php endforeach; ?>
              </tbody>
            </table>
            <!-- Fim da tabela para a categoria -->
          <?php endforeach; ?>

          <?php include '../include/_jscripts.php' ?>

          <script>

          </script>
        </div>
      </div>
    </div>
  </body>

</html>