<?php

header("Access-Control-Allow-Origin: *");

//mantem como json
//header("Content-Type: application/json");

// Conexão com o banco de dados
require_once '_dbcon.php';

//pegar indicador
$indicador = $_POST['indicador'];

// Login e Create
if ($indicador == 'user_new') {
  //pegar variaveis do form
  $email = $_POST['email'];
  $senha = strtolower($_POST['senha']);

  // Gere um sal aleatório para cada usuário (por exemplo, 16 bytes)
  $salt = random_bytes(16);
  //echo $salt;
  // Use bin2hex() para obter uma representação legível em hexadecimal do sal
  $salt_hex = bin2hex($salt);
  //echo "," . $salt_hex . ",";
  // Combine a senha com o salt
  $senhaComSalt = $senha . $salt_hex;

  // Faça o hash da senha + salt (usando bcrypt)
  $hashSenha = password_hash($senhaComSalt, PASSWORD_BCRYPT);

  //prepara a query
  $query = "INSERT INTO usuarios (id, email, senha, salt)
  VALUES (UUID(), '$email', '$hashSenha', '$salt_hex')";

  // Verificar se o email já existe
  $emailExistsQuery = "SELECT email FROM usuarios WHERE email = '$email'";
  $emailExistsResult = $conn->query($emailExistsQuery);

  if ($emailExistsResult->num_rows > 0) {

    // Email já existe, exibir mensagem de erro
    echo "Essa conta de email já está registrada.";

  } else {

    //executa a query
    if ($conn->query($query) === TRUE) {
      echo "ok";
    } else {
      echo "erro: " . $conn->error;
    }

  }

}

if ($indicador == 'user_login') {
  //pegar variáveis do formulário
  $email_web = $_POST['email'];
  $senha_web = strtolower($_POST['senha']);

  // Consulta SQL para obter o salt e o hash da senha armazenados no banco de dados
  $sql = "SELECT id,salt, senha FROM usuarios WHERE email = '$email_web'";
  $resultado = $conn->query($sql);

  if ($resultado) {
    if ($resultado->num_rows > 0) {
      $row = $resultado->fetch_assoc();

      $salt_db = $row['salt'];
      $hash_db = $row['senha'];

      // Combine a senha fornecida com o salt armazenado
      $senhaComSalt = $senha_web . $salt_db;

      //echo $hashSenha;
      // Verifique se a senha fornecida pelo usuário corresponde ao hash armazenado
      if (password_verify($senhaComSalt, $hash_db)) {
        // Senha correta, permita o acesso
        session_start();
        $_SESSION['user_email'] = $email_web; //Armazena o EMAIL na sessão
        $_SESSION['user_id'] = $row['id']; // Armazena o ID na sessão
        echo "ok";
      } else {
        // Senha incorreta
        echo "Erro:" . $conn->error;
      }
    } else {
      // Email não encontrado no banco de dados
      echo "E-mail não encontrado.";
    }
  } else {
    // Erro ao executar a consulta
    echo "Erro ao executar a consulta: " . $conn->error;
  }
}

// Partipantes
if ($indicador == 'part_edit') {

  //pegar parametros
  $id = $_POST['id_edit'];
  $nome = $_POST['nome_part_edit'];
  $email = strtolower($_POST['email_part_edit']);

  //prepara a query
  $query = "UPDATE participantes
    SET nome = '$nome', email = '$email'
        WHERE id = '$id';";

  //executa a query
  if ($conn->query($query) === TRUE) {
    echo "ok";
  } else {
    echo "erro: " . $conn->error;
  }

  //devolve dados
  //header('Content-Type: application/json');
  //echo json_encode($data);
}

if ($indicador == 'part_del') {

  //pegar parametros
  $id = $_POST['id_part_del'];

  //prepara a query
  $query = "DELETE FROM participantes
    WHERE id = '$id';";

  //executa a query
  if ($conn->query($query) === TRUE) {
    echo "ok";
  } else {
    echo "erro: " . $conn->error;
  }
}

if ($indicador == 'part_new') {

  // Recuperar os dados do formulário
  $nomepart = $_POST['nome_part_new'];
  $emailpart = strtolower($_POST['email_part_new']);
  $user_id = $_POST['user_id'];

  // Aqui você pode realizar a lógica de criação de conta e inserção no banco de dados
  $query = "INSERT INTO participantes (id, nome, email, id_creator) VALUES (UUID(), '$nomepart', '$emailpart', '$user_id')";


  //executa a query
  if ($conn->query($query) === TRUE) {
    echo "ok";
  } else {
    echo "erro: " . $conn->error;
  }
}

if ($indicador == 'part_new_csv') {
  // Abra o arquivo CSV
  $file = fopen($_FILES["file"]["tmp_name"], "r");
  $id_creator = $_POST['id_creator'];

  // Inicializa as variáveis
  $qtd_duplicados = 0;
  $qtd_invalidos = 0;
  $qtd_validos = 0;

  // Leia o arquivo linha por linha
  while (($participante = fgetcsv($file)) !== false) {

    // Converte o email para minúscula
    $participante[1] = strtolower($participante[1]);
    // Remove espaços em branco do início e do fim do email
    $participante[1] = trim($participante[1]);

    // Consulta para saber se o email já existe
    $sql = "SELECT email FROM participantes
              WHERE email = '{$participante[1]}'
              AND id_creator = '$id_creator'";
    $result = $conn->query($sql);
    //echo $result->num_rows;
    //echo $sql;
    // Armazena a quantidade em uma variável
    if ($result->num_rows > 0) {
      // Email já existe
      $qtd_duplicados++;
    } else {
      // Verifica se o email é válido
      //echo $participante[1];
      if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/", $participante[1])) {
        // Email inválido

        $qtd_invalidos++;
      } else {
        // Email válido
        // Inserir o participante no banco de dados
        $sql = "INSERT INTO participantes (id, nome, email, id_creator, data)
                 VALUES (UUID(), '{$participante[0]}', '{$participante[1]}', '$id_creator', NOW())";
        $conn->query($sql);

        $qtd_validos++;
      }
    }
  }

  // Feche o arquivo
  fclose($file);

  // Cria um array com os resultados
  $resultados = [
    'qtd_duplicados' => $qtd_duplicados,
    'qtd_invalidos' => $qtd_invalidos,
    'qtd_validos' => $qtd_validos,
  ];

  header('Content-Type: application/json');
  // Retorna o JSON com os resultados
  echo json_encode($resultados);

}

if ($indicador == 'part_tabela') {

  $user_id = $_POST['user_id'];

  $query = "SELECT id,nome,email FROM participantes WHERE id_creator = '$user_id' ORDER BY data DESC";
  $result = $conn->query($query);

  $data = array();
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }

  header('Content-Type: application/json');
  echo json_encode($data);
}

// Avaliações
if ($indicador == 'aval_tabela') {

  $user_id = $_POST['user_id'];
  $query = "SELECT id,nome,descri,id_creator, DATE_FORMAT(DATE_ADD(data, INTERVAL 1 HOUR), '%d/%m/%Y') AS data_formatada FROM testes WHERE id_creator = '$user_id' ORDER BY data DESC";
  $result = $conn->query($query);

  $data = array();
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }

  header('Content-Type: application/json');
  echo json_encode($data);
}

if ($indicador == 'aval_new') {

  // Recuperar os dados do formulário
  $aval = $_POST['nome_aval'];
  $descri = $_POST['aval_desc'];
  $user_id = $_POST['user_id'];

  // Aqui você pode realizar a lógica de criação de conta e inserção no banco de dados
  $query = "INSERT INTO testes (id, nome, descri, id_creator) VALUES (UUID(), '$aval', '$descri', '$user_id')";


  //executa a query
  if ($conn->query($query) === TRUE) {
    echo "ok";
  } else {
    echo "erro: " . $conn->error;
  }
}

if ($indicador == 'aval_del') {

  //pegar parametros
  $id = $_POST['id_aval_del'];

  //prepara a query
  $query = "DELETE FROM testes
    WHERE id = '$id';";

  //executa a query
  if ($conn->query($query) === TRUE) {
    echo "ok";
  } else {
    echo "erro: " . $conn->error;
  }
}
// Groups
if ($indicador == 'group_tabela') {
  // Pegar variáveis
  $user_id = $_POST['user_id'];
  $aval_id = $_POST['aval_id'];

  // Buscar grupos
  $query_grupos = "SELECT id, id_aval, id_creator FROM grupos WHERE id_creator = '$user_id'";
  $result_grupos = $conn->query($query_grupos);

  $grupos_data = array();
  if ($result_grupos->num_rows > 0) {
    while ($grupo = $result_grupos->fetch_assoc()) {
      $grupo_data = array(
        "id_grupo" => $grupo['id'],
        "id_aval" => $grupo['id_aval'],
        "id_creator" => $grupo['id_creator'],
        "participantes" => array()
      );

      // Buscar participantes
      $query_participantes = "SELECT pg.id_participante, pg.tipo_participante, p.nome,pg.enviou_email,pg.fez_teste,p.email
    FROM participantes_grupo pg
    LEFT JOIN participantes p ON pg.id_participante = p.id
    WHERE id_grupo = '{$grupo['id']}'";
      $result_participantes = $conn->query($query_participantes);

      if ($result_participantes->num_rows > 0) {
        while ($participante = $result_participantes->fetch_assoc()) {
          $participante_data = array(
            "id" => $participante['id_participante'],
            "tipo" => $participante['tipo_participante'],
            "nome" => $participante['nome'],
            "email" => $participante['email'],
            "enviou_email" => $participante['enviou_email'],
            "fez_teste" => $participante['fez_teste'],
          );
          $grupo_data["participantes"][] = $participante_data;
        }
      }

      $grupos_data[] = $grupo_data;
    }
  }

  header('Content-Type: application/json');
  echo json_encode($grupos_data);
}

if ($indicador == 'group_tabela_envio') {

  $user_id = $_POST['user_id'];
  $aval_id = $_POST['aval_id'];

  $query = "SELECT
  subquery.id_group,
  subquery.tipo,
  subquery.nome,
  subquery.email,
  subquery.envio,
  subquery.id_part,
  a.data as nome_AVAL
FROM (

  SELECT
      id as id_group,
      coluna as tipo,
      valor as nome,
      email, envio,id_part
      FROM (
      SELECT
      g.id,
      'lider_nome' AS coluna,
      p1.nome AS valor,
      p1.email,
      g.lider_envio as envio,
      p1.id as id_part
      FROM
      groups g
      LEFT JOIN participantes p1 ON g.lider = p1.id
      WHERE g.id_creator = '$user_id' AND g.id_aval = '$aval_id' AND p1.nome IS NOT NULL

      UNION ALL

      SELECT
      g.id,
      'part1_nome' AS coluna,
      p2.nome AS valor,
      p2.email,
      g.part1_envio as envio,
      p2.id as id_part
      FROM
      groups g
      LEFT JOIN participantes p2 ON g.part1 = p2.id
      WHERE g.id_creator = '$user_id' AND g.id_aval = '$aval_id' AND p2.nome IS NOT NULL

      UNION ALL

      SELECT
      g.id,
      'part2_nome' AS coluna,
      p3.nome AS valor,
      p3.email,
      g.part2_envio as envio,
      p3.id as id_part
      FROM
      groups g
      LEFT JOIN participantes p3 ON g.part2 = p3.id
      WHERE g.id_creator = '$user_id' AND g.id_aval = '$aval_id' AND p3.nome IS NOT NULL

      UNION ALL

      SELECT
      g.id,
      'part3_nome' AS coluna,
      p4.nome AS valor,
      p4.email,
      g.part3_envio as envio,
      p4.id as id_part
      FROM
      groups g
      LEFT JOIN participantes p4 ON g.part3 = p4.id
      WHERE g.id_creator = '$user_id' AND g.id_aval = '$aval_id' AND p4.nome IS NOT NULL

      UNION ALL

      SELECT
      g.id,
      'part4_nome' AS coluna,
      p5.nome AS valor,
      p5.email,
      g.part4_envio as envio,
      p5.id as id_part
      FROM
      groups g
      LEFT JOIN participantes p5 ON g.part4 = p5.id
      WHERE g.id_creator = '$user_id' AND g.id_aval = '$aval_id' AND p5.nome IS NOT NULL
      ) AS subquery
      ORDER BY nome

      ) AS subquery
LEFT JOIN respostas a ON subquery.id_part = a.id_part AND subquery.id_aval = a.id_aval
ORDER BY subquery.nome;";

  $result = $conn->query($query);

  $data = array();
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }

  header('Content-Type: application/json');
  echo json_encode($data);
}

if ($indicador == 'group_new') {

  // Recuperar os dados do formulário
  $part1 = $_POST['lider'];
  $part2 = $_POST['part1'];
  $part3 = $_POST['part2'];
  $part4 = $_POST['part3'];
  $part5 = $_POST['part4'];

  $aval_id = $_POST['aval_id'];
  $id_creator = $_POST['user_id'];

  //criar um uuid no mysql
  $query_uuid = "SELECT UUID() as uuid";
  $result_uuid = $conn->query($query_uuid);
  $row_uuid = $result_uuid->fetch_assoc();
  $grupo_uuid = $row_uuid['uuid'];

  //criar um grupo
  $query_grupo = "INSERT INTO grupos (id, id_aval, id_creator)
                    VALUES ('$grupo_uuid', '$aval_id', '$id_creator')";
  $conn->query($query_grupo);

  //criar participantes_grupo
  $query_participantes = "INSERT INTO participantes_grupo (id, id_grupo, id_participante, tipo_participante) VALUES ";

  $participantes = array($part1, $part2, $part3, $part4, $part5);
  $tipos = array('participante1', 'participante2', 'participante3', 'participante4', 'participante5');

  for ($i = 0; $i < count($participantes); $i++) {
    $valorParticipante = !empty($participantes[$i]) ? "'$participantes[$i]'" : "''";
    $query_participantes .= " (UUID(), '$grupo_uuid', $valorParticipante, '$tipos[$i]'),";
  }

  $query_participantes = rtrim($query_participantes, ','); // Remover a vírgula final


  //echo $query_participantes;
  if ($conn->query($query_participantes) === true) {
    echo "ok"; // Indicador de sucesso
  } else {
    echo "error: " . mysqli_error($conn); // Enviar a mensagem de erro
  }
}

if ($indicador == 'group_del') {

  //pegar parametros
  $id = $_POST['id_group_del'];

  //prepara a query
  $query = "DELETE FROM grupos
    WHERE id = '$id';";

  //executa a query
  if ($conn->query($query) === TRUE) {
    echo "ok";
  } else {
    echo "erro: " . $conn->error;
  }
}

if ($indicador == 'group_view') {

  // Recuperar os dados do formulário
  $group_id = $_POST['group_id'];

  // Aqui você pode realizar a lógica de criação de grupo e inserção no banco de dados
  $query = "SELECT pg.id_participante, pg.tipo_participante, p.nome,pg.enviou_email,pg.fez_teste,p.email FROM participantes_grupo pg LEFT JOIN participantes p ON pg.id_participante = p.id WHERE id_grupo = '$group_id'";

  $result = $conn->query($query);

  $data = array();
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }

  header('Content-Type: application/json');
  echo json_encode($data);
}

if ($indicador == 'group_view_select') {

  $creator_id = $_POST['creator_id'];

  $query = "SELECT * FROM participantes WHERE id_creator = '$creator_id' ORDER BY nome";

  $result = $conn->query($query);

  $data = array();
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }

  header('Content-Type: application/json');
  echo json_encode($data);
}

if ($indicador == 'group_edit') {

  //pegar parametros
  $id_grupo = $_POST['groupId'];
  $part1 = $_POST['lider-edit'];
  $part2 = $_POST['part1-edit'];
  $part3 = $_POST['part2-edit'];
  $part4 = $_POST['part3-edit'];
  $part5 = $_POST['part4-edit'];

  // Construir uma consulta SQL grande com todas as operações
  $query = "SET autocommit=0; START TRANSACTION;";

  $participantes = array($part1, $part2, $part3, $part4, $part5);
  $tipos = array('participante1', 'participante2', 'participante3', 'participante4', 'participante5');

  for ($i = 0; $i < count($participantes); $i++) {
    if (!empty($participantes[$i])) {
      $query .= "UPDATE participantes_grupo SET id_participante = '$participantes[$i]' WHERE id_grupo = '$id_grupo' AND tipo_participante = '$tipos[$i]';";
    } else {
      $query .= "UPDATE participantes_grupo SET id_participante = '' WHERE id_grupo = '$id_grupo' AND tipo_participante = '$tipos[$i]';";
    }
  }

  $query .= "COMMIT;";

  //executa a query
  if ($conn->multi_query($query) === TRUE) {
    echo "ok";
  } else {
    echo "erro: " . $conn->error;
  }

  //echo $query;

  //devolve dados
  //header('Content-Type: application/json');
  //echo json_encode($data);
}

if ($indicador == 'aval_send') {

  // Recuperar os dados do formulário
  $id_part = $_POST['id_part'];
  $id_survey = $_POST['id_survey'];
  $id_grupo = $_POST['id_grupo'];
  $resposta = $_POST['resposta'];


  $errors = array();

  // Aqui você pode realizar a lógica de criação de grupo e inserção no banco de dados
  $query = "INSERT INTO respostas (id, id_part, id_aval,id_grupo, resposta,data)
  VALUES (UUID(), '$id_part', '$id_survey','$id_grupo', '$resposta',NOW());";

  $query2 = "UPDATE participantes_grupo SET fez_teste = CURRENT_TIMESTAMP
  WHERE id_participante = '$id_part'
  AND id_grupo = '$id_grupo';
  ";

  if ($conn->query($query) !== true) {
    $errors[] = "Query 1 error: " . $conn->error;
  }

  if ($conn->query($query2) !== true) {
    $errors[] = "Query 2 error: " . $conn->error;
  }

  if (empty($errors)) {
    echo "ok"; // Ambas as queries foram bem-sucedidas
  } else {
    echo "Errors:<br>";
    foreach ($errors as $error) {
      echo $error . "<br>";
    }
  }

}

if ($indicador == 'rel_view') {
  $id_grupo = $_POST['id_grupo'];

  // Consulta para contar o número de perguntas em cada categoria
  $q_contagem_perguntas = "SELECT pc.nome AS categoria, COUNT(p.id) AS quant_perguntas
  FROM perguntas p
  JOIN perguntas_categoria pc ON p.id_category = pc.id
  GROUP BY pc.nome";

  $result_contagem_perguntas = $conn->query($q_contagem_perguntas);

  // Inicialize um array para armazenar a contagem de perguntas por categoria
  $contagem_perguntas = [];

  while ($row_contagem_perguntas = $result_contagem_perguntas->fetch_assoc()) {
    $categoria = $row_contagem_perguntas['categoria'];
    $quantidade = $row_contagem_perguntas['quant_perguntas'];
    $contagem_perguntas[$categoria] = $quantidade;
  }



  // Consulta para obter todas as respostas de todos os participantes do grupo
  $respostas = "SELECT r.id_part, r.resposta, pg.tipo_participante
    FROM respostas r
    INNER JOIN participantes_grupo pg ON r.id_part = pg.id_participante AND r.id_grupo = pg.id_grupo
    WHERE r.id_grupo = '$id_grupo';";

  $q_respostas = $conn->query($respostas);

  // Loop pelos resultados das respostas dos participantes
  while ($resposta = $q_respostas->fetch_assoc()) {
    $tipo_participante = $resposta['tipo_participante'];
    $json_data = json_decode($resposta['resposta'], true);


    // Loop pelos dados das respostas de cada participante
    foreach ($json_data as $pergunta_id => $valor) {
      // Consulta para obter a categoria da pergunta
      $q_categoria = "SELECT pc.nome AS categoria FROM perguntas p
                       JOIN perguntas_categoria pc ON p.id_category = pc.id
                       WHERE p.id = '$pergunta_id'";

      $result_categoria = $conn->query($q_categoria);
      $row_categoria = $result_categoria->fetch_assoc();

      $categoria_pergunta = $row_categoria['categoria']; // Renomeada para evitar conflitos

      // Verifica o tipo de participante e adiciona o valor à soma apropriada
      if (!isset($categories_sum[$categoria_pergunta])) {
        $categories_sum[$categoria_pergunta] = [
          'participante1' => 0,
          'outros_participantes' => 0

        ];
      }

      if ($tipo_participante !== 'participante1') {
        $categories_sum[$categoria_pergunta]['outros_participantes'] += $valor;
      } else {
        $categories_sum[$categoria_pergunta]['participante1'] += $valor;
      }
    }

  }

  // Função para determinar o nível com base no valor de quant_perguntas
  function determinarNivel($nota, $quant_perguntas)
  {
    // Determinar niveis
    $nivel1 = $quant_perguntas;
    $nivel2 = $quant_perguntas * 2;
    $nivel3 = $quant_perguntas * 3;
    $nivel4 = $quant_perguntas * 4;
    $nivel5 = $quant_perguntas * 5;

    if ($nota >= $nivel5) {
      return "5";
    } elseif ($nota >= $nivel4) {
      return "4";
    } elseif ($nota >= $nivel3) {
      return "3";
    } elseif ($nota >= $nivel2) {
      return "2";
    } elseif ($nota >= $nivel1) {
      return "1";
    } else {
      return "desconhecido";
    }
  }

  // Construir o JSON final
  $json_final = array();
  foreach ($categories_sum as $categoria => $valores) {
    // Calcula a média dos valores dos outros participantes
    $outros_participantes_sum = $valores['outros_participantes'];
    $outros_participantes_count = $q_respostas->num_rows - 1; // Exclui o participante1
    $outros_participantes_average = ($outros_participantes_count !== 0) ? ($outros_participantes_sum / $outros_participantes_count) : 0;

    // Converte o valor de quant_perguntas para um número inteiro
    $valores['quant_perguntas'] = (int) $contagem_perguntas[$categoria];

    // Determina o nível com base na quant_perguntas
    $nivelp1 = determinarNivel($valores['participante1'], $valores['quant_perguntas']);
    $nivelop = determinarNivel($outros_participantes_average, $valores['quant_perguntas']);

    // Converte nivel_lider e nivel_outros para inteiros
    $nivel_lider = (int) $nivelp1;
    $nivel_outros = (int) $nivelop;

    // Cria um novo objeto para adicionar ao JSON final
    $json_final[] = [
      'categoria' => $categoria,
      'participante1' => round($valores['participante1']),
      'outros_participantes' => round($outros_participantes_average),
      'quant_perguntas' => $valores['quant_perguntas'],
      'nivel_lider' => $nivel_lider,
      'nivel_outros' => $nivel_outros
    ];
  }

  // Codificar o JSON final
  $json_result = json_encode($json_final, JSON_PRETTY_PRINT);

  // Definir o cabeçalho como JSON
  header('Content-Type: application/json');

  // Imprimir o JSON resultante
  echo $json_result;

}

if ($indicador == 'enviar_email') {

  // Recuperar os dados do formulário
  $part = $_POST['part_id'];
  $group = $_POST['group_id'];
  $aval = $_POST['survey_id'];

  //descobrir os dados do particpiante
  $sql_part = "SELECT p.nome ,p.email
  FROM participantes p
  WHERE id = '$part'";

  $res_part = $conn->query($sql_part);

  $data_part = array();
  while ($row_part = $res_part->fetch_assoc()) {
    $data_part[] = $row_part;
  }

  $nome_part = $data_part[0]['nome']; // Nome do participante
  $email_part = $data_part[0]['email']; // Email do participante

  //descobrir os dados do lider do grupo
  $sql_lider = "SELECT p.nome
  FROM participantes_grupo pg
  JOIN participantes p ON pg.id_participante = p.id
  WHERE pg.tipo_participante = 'participante1'
  AND pg.id_grupo = '$group'";

  $res_lider = $conn->query($sql_lider);

  $data_lider = array();
  while ($row_lider = $res_lider->fetch_assoc()) {
    $data_lider[] = $row_lider;
  }

  $nome_lider = $data_lider[0]['nome']; // Nome do lider do grupo

  $link_email = "https://LiderScan.com.br/teste.php?id_part=$part&id_survey=$aval&id_grupo=$group";

  /*
    //montar e mandar email
    include 'config.php';
    $apiKey = API_KEY;
    */
  /*
  // Configuração cURL
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, 'https://api.brevo.com/v3/smtp/email');
  curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER,
    array(
      'accept: application/json',
      'api-key: ' . $apiKey,
      'content-type: application/json',
    )
  );
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  // Dados da solicitação
  $requestData = array(
    "to" => array(
      array(
        "email" => $email_part,
        "name" => $nome_part
      )
    ),
    "templateId" => 2,
    "params" => array(
      "PART" => $nome_part,
      "LIDER" => $nome_lider,
      "LINK" => $link_email
    ),
    "headers" => array(
      "charset" => "iso-8859-1"
    )
  );

  $jsonData = json_encode($requestData);

  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

  // Executar a solicitação cURL
  $response = curl_exec($ch);

  /*if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
  } else {
    echo 'Response: ' . $response;
  }*/

  //    curl_close($ch);


  // Processa a solicitação AJAX e obtém o resultado
  $resultado = array('mensagem' => $apiKey);

  // Retorna a resposta como JSON
  header('Content-Type: application/json');
  echo json_encode($resultado);

}

$conn->close();