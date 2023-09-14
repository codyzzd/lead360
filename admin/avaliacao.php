<? include '../include/_a_check_login.php' ?>
<?
//Conexão com o banco de dados

require_once '../include/_dbcon.php';

//pega survey id e id do usuario
session_start();
$user_id = $_SESSION['user_id'];
$survey_id = $_GET['id'];

//consulta no banco
$query = "SELECT id, nome, descri, id_creator FROM testes WHERE id_creator = '$user_id' AND id = '$survey_id'";

$result = $conn->query($query);
//guarda o resultado
$data = array();
while
($row = $result->fetch_assoc()) {
  $data[] = $row;
} // Se nao achar nada redireciona o usuario
if ($result->num_rows === 0) {
  header('Location: index.php');
} //pegar dados para os selects de participantes
$queryPart =
  "SELECT id, nome FROM participantes WHERE id_creator = '" . $_SESSION['user_id'] . "' order by nome ASC";
$resultPart = $conn->query($queryPart);
$parts = array();
while (
  $rowPart
  = $resultPart->fetch_assoc()
) {
  $parts[] = $rowPart;
} ?>
<!DOCTYPE html>
<html lang="pt-BR">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0" />
    <title>LiderScan - Avaliação
      <?php echo $parts[0]['nome']; ?>
    </title>
    <?php include '../include/_head.php' ?>
  </head>

  <body>
    <?php include '../include/_toasters.php' ?>
    <?php include '../include/_nav.php' ?>

    <div class="container mt-3 mt-sm-5 mb-3">
      <div class="row">
        <div class="col-md">
          <small>Avaliação</small>
          <h2>
            <?php echo $data[0]['nome']; ?>
          </h2>
          <p>
            <?php echo $data[0]['descri']; ?>
          </p>
        </div>
        <div class="col-md-auto text-md-end text-end d-grid gap-2 d-md-block">
          <div class="btn-group"
               role="group"
               aria-label="Basic radio toggle button group">
            <input type="radio"
                   class="btn-check"
                   name="btnradio"
                   id="btn_cadastro"
                   autocomplete="off"
                   checked />
            <label class="btn btn-outline-primary"
                   for="btn_cadastro"><span class="btn-label"><i class="fa fa-user-group me-2"></i></span>Cadastro</label>

            <input type="radio"
                   class="btn-check"
                   name="btnradio"
                   id="btn_envio"
                   autocomplete="off" />
            <label class="btn btn-outline-primary"
                   for="btn_envio"><span class="btn-label"><i class="fa fa-envelope me-2"></i></span>Emails</label>

            <!--<input type="radio"
                   class="btn-check"
                   name="btnradio"
                   id="btn_relatorio"
                   autocomplete="off" />
            <label class="btn btn-outline-primary"
                   for="btn_relatorio"><span class="btn-label"><i class="fa fa-chart-simple me-2"></i></span>Relatório</label>-->
          </div>
        </div>
      </div>
    </div>

    <!-- form criar -->
    <div class="container mb-5">
      <div class="row">
        <div class="col-12 col-md-4 col-lg-3 esq_cadastro">
          <h4 class="mb-3">Criar grupos</h4>

          <form id="form_new"
                name="form_new">
            <div class="form-group">
              <label for="lider">Lider</label>
              <select class="form-select mb-3 select-validation"
                      id="lider"
                      name="lider"
                      required>
                <option value="">Selecione...</option>
                <!-- Preenche as opções do select com os dados da consulta -->
                <?php foreach ($parts as $participante) { ?>
                  <option value="<?php echo $participante['id']; ?>">
                    <?php echo $participante['nome']; ?>
                  </option>
                <?php } ?>
              </select>
            </div>

            <label for="part1">Participante 1</label>
            <select class="form-select mb-3 select-validation"
                    id="part1"
                    name="part1"
                    required>
              <option value="">Selecione...</option>
              <!-- Preenche as opções do select com os dados da consulta -->
              <?php foreach ($parts as $participante) { ?>
                <option value="<?php echo $participante['id']; ?>">
                  <?php echo $participante['nome']; ?>
                </option>
              <?php } ?>
            </select>

            <label for="part2">Participante 2</label>
            <select class="form-select mb-3 select-validation"
                    id="part2"
                    name="part2">
              <option value="">Selecione...</option>
              <!-- Preenche as opções do select com os dados da consulta -->
              <?php foreach ($parts as $participante) { ?>
                <option value="<?php echo $participante['id']; ?>">
                  <?php echo $participante['nome']; ?>
                </option>
              <?php } ?>
            </select>

            <label for="part3">Participante 3</label>
            <select class="form-select mb-3 select-validation"
                    id="part3"
                    name="part3">
              <option value="">Selecione...</option>
              <!-- Preenche as opções do select com os dados da consulta -->
              <?php foreach ($parts as $participante) { ?>
                <option value="<?php echo $participante['id']; ?>">
                  <?php echo $participante['nome']; ?>
                </option>
              <?php } ?>
            </select>

            <label for="part4">Participante 4</label>
            <select class="form-select mb-3 select-validation"
                    id="part4"
                    name="part4">
              <option value="">Selecione...</option>
              <!-- Preenche as opções do select com os dados da consulta -->
              <?php foreach ($parts as $participante) { ?>
                <option value="<?php echo $participante['id']; ?>">
                  <?php echo $participante['nome']; ?>
                </option>
              <?php } ?>
            </select>

            <div class="d-grid gap-2 d-block mb-3">
              <button class="btn btn-primary"
                      id="submit_form"
                      type="submit">
                <span class="btn-label"><i class="fa fa-plus me-2"></i></span>Criar grupo
              </button>

            </div>
          </form>
          <div class="d-grid gap-2 d-block mb-3">
            <button class="btn btn-outline-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modal_link_csv"
                    id="link_csv"><span class="btn-label"><i class="fa fa-file-csv me-2"></i></span>Criar por CSV</button>
          </div>
        </div>

        <div class="col-12 col-md-8 col-lg-9 dir_cards">
          <div class="row">
            <div class="col-md">
              <h4 class="mb-3">Grupos</h4>
            </div>
          </div>

          <div class="row row-cols-2 row-cols-sm-2 row-cols-lg-2 row-cols-xl-3 g-4"
               id="tabela-cards"></div>
        </div>
      </div>

      <div class="row envios"
           style="display: none">
        <div class="col-12">
          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col"
                    class="col-auto">Lider</th>
                <th scope="col"
                    class="col-auto">Participante</th>
                <th scope="col"
                    class="col-auto">Status</th>

                <th scope="col"
                    class="col-3 col-sm-2 text-end">Opções</th>
              </tr>
            </thead>
            <tbody id="tabela-envios">
              <!-- Os dados serão preenchidos aqui via Ajax -->
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- modal excluir -->
    <div class="modal fade"
         tabindex="-1"
         id="modal_excluir">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Excluir grupo</h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                    id="modal_del_close"></button>
          </div>
          <div class="modal-body">
            Você vai deletar a avaliação de <strong id="nome"></strong>.<br />
          </div>
          <div class="modal-footer">
            <button type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal">
              Não, Cancelar
            </button>
            <button type="button"
                    class="btn btn-danger"
                    id="group_del2">
              Sim, Excluir!
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- modal editar -->
    <div class="modal fade"
         tabindex="-1"
         id="modal_edit">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Editar grupo</h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                    id="modal_edit_close"></button>
          </div>
          <form id="form_edit">
            <div class="modal-body">
              <div class="form-group">
                <label for="lider-edit">Lider</label>
                <select class="form-select mb-3 select-validation"
                        id="lider-edit"
                        name="lider-edit"
                        required>
                  <option value="">Selecione...</option>
                </select>
              </div>

              <label for="part1-edit">Participante 1</label>
              <select class="form-select mb-3 select-validation"
                      id="part1-edit"
                      name="part1-edit"
                      required>
                <option value="">Selecione...</option>
              </select>

              <label for="part2-edit">Participante 2</label>
              <select class="form-select mb-3 select-validation"
                      id="part2-edit"
                      name="part2-edit">
                <option value="">Selecione...</option>
              </select>

              <label for="part3-edit">Participante 3</label>
              <select class="form-select mb-3 select-validation"
                      id="part3-edit"
                      name="part3-edit">
                <option value="">Selecione...</option>
              </select>

              <label for="part4-edit">Participante 4</label>
              <select class="form-select select-validation"
                      id="part4-edit"
                      name="part4-edit">
                <option value="">Selecione...</option>
              </select>
            </div>
            <div class="modal-footer">
              <button type="button"
                      class="btn btn-outline-secondary"
                      data-bs-dismiss="modal">
                Cancelar
              </button>
              <button type="submit"
                      class="btn btn-primary submit-button"
                      id="group_edit2">
                Salvar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- modal resultado -->
    <div class="modal fade modal-xl"
         tabindex="-1"
         id="modal_rel">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Resultado do grupo</h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                    id="modal_edit_close"></button>
          </div>

          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-xl-4">
                <div id="t_parts"
                     class="mb-5"></div>
                <table class="table mb-5 d-none">
                  <thead>
                    <tr>
                      <th scope="col"
                          class="col-auto">Lider</th>
                      <th scope="col"
                          class="col-auto">Participante</th>
                      <th scope="col"
                          class="col-auto">Status</th>

                      <th scope="col"
                          class="col-3 col-sm-2 text-end">Opções</th>
                    </tr>
                  </thead>
                  <tbody id="tabela-parts-status">
                    <!-- Os dados serão preenchidos aqui via Ajax -->
                  </tbody>
                </table>
              </div>

              <div class="col-12 col-xl-8">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th scope="col">Critério</th>
                      <th scope="col"
                          class="text-end">Autoavaliação</th>
                      <th scope="col"
                          class="text-end">Grupo</th>
                    </tr>
                  </thead>
                  <tbody id="tabela-rel">

                  </tbody>
                </table>
              </div>
            </div>

          </div>
          <div class="modal-footer">

            <button class="btn btn-outline-secondary "
                    data-bs-dismiss="modal">
              Fechar
            </button>
            <button class="btn btn-outline-primary"
                    id="down_photo">
              Baixar resultados
            </button>
            <a href="grupo_detalhe.php?id="
               target="_blank"
               class="btn btn-primary"
               id="link_grupo_detalhe">
              <span class="btn-label"><i class="fa fa-file-lines me-2"></i></span>
              Relatório detalhado
            </a>

          </div>

        </div>
      </div>
    </div>

    <?php include '../include/_jscripts.php' ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>

    <script>
      $(document).ready(function () {

        $('#down_photo').click(function () {
          // Create a temporary container div
          const containerDiv = document.createElement('div');
          containerDiv.classList.add('modal-container'); // You can style this class for presentation if needed

          // Clone the modal content and append it to the container
          const modalContent = document.querySelector('#myModal .modal-content');
          const clonedContent = modalContent.cloneNode(true);
          containerDiv.appendChild(clonedContent);

          // Append the container div to the body (to render off-screen)
          document.body.appendChild(containerDiv);

          // Use html2canvas to capture the content of the container div as an image
          html2canvas(containerDiv).then(function (canvas) {
            // Create an anchor element to download the image
            const downloadLink = document.createElement('a');
            downloadLink.href = canvas.toDataURL('image/jpeg'); // or 'image/png' for PNG format
            downloadLink.download = 'captured_modal_image.jpg'; // or 'captured_image.png' for PNG format

            // Trigger a click event on the anchor element to download the image
            downloadLink.click();

            // Remove the temporary container div
            document.body.removeChild(containerDiv);
          });
        });





        new ClipboardJS(".avalurl", {
          text: function (trigger) {
            return $(trigger).data("clipboard-text");
          },
        })
          .on("success", function (e) {
            criarToastmini("success", "Link copiado!");
          })
          .on("error", function (e) {
            criarToastmini("danger", "Erro ao copiar link.");
          });

        //modo cadastro
        $("#btn_cadastro").click(function () {
          $(".esq_cadastro, .dir_cards").show();
          $(".envios").hide();
        });
        //modo envio
        $("#btn_envio").click(function () {
          $(".envios").show();
          $(".esq_cadastro, .dir_cards").hide();
        });

        function atualizarTabela() {
          $.ajax({
            type: "POST", // Alterado para POST
            url: "../include/api.php", // Substitua pela URL correta
            data: {
              indicador: "group_tabela", // Adicione um parâmetro de ação para identificar a ação no servidor
              user_id: "<?php echo $_SESSION['user_id']; ?>",
              aval_id: "<?php echo $survey_id; ?>", // Recupera o valor do 'id' da URL
            }, // Inclua o user_id como parâmetro
            dataType: "json",
            success: function (resultados) {
              var tabelaCorpo = $("#tabela-cards");
              var tabelaEnvios = $("#tabela-envios");

              tabelaCorpo.empty(); // Limpar a tabela antes de preencher
              tabelaEnvios.empty(); // Limpar a tabela antes de preencher

              // Preencher a tabela com os dados recebidos
              $.each(resultados, function (index, row) {

                for (let i = 0; i < row.participantes.length; i++) {

                  if (row.participantes[i].enviou_email !== null) {
                    datac = moment(row.participantes[i].enviou_email).format("DD/MM/YYYY - HH:mm:ss");
                    status =
                      '<span class="badge text-bg-success" title="' +
                      datac +
                      '">Email OK</span>';
                  } else {
                    status =
                      '<span class="badge text-bg-warning">Email OFF</span>';
                  }

                  if (row.participantes[i].fez_teste !== null) {
                    datac = moment(row.participantes[i].fez_teste).format("DD/MM/YYYY - HH:mm:ss");
                    fezaval =
                      '<span class="badge text-bg-success" title="' +
                      datac +
                      '">Teste OK</span>';
                  } else {
                    fezaval =
                      '<span class="badge text-bg-warning">Teste OFF</span>';
                  }

                  if (row.participantes[i].nome !== null) {
                    tabelaEnvios.append(`
                      <tr>
                      <td>${row.participantes[0].nome}</td>
                      <td>${row.participantes[i].nome} <small class="text-body-secondary">(${row.participantes[i].email})</small></td>
                      <td>${status} ${fezaval}</td>
                      <td class="text-end">

                      <button class="btn btn-light btn-sm avalurl" type="button" data-clipboard-text="https://LiderScan.com.br/teste.php?id_part=${row.participantes[i].id}&id_survey=<?php echo $survey_id; ?>&id_grupo=${row.id_grupo}"><span class="btn-label"><i class="fa fa-link me-2"></i></span>Link</button>

                      <button type="button" class="btn btn-outline-success btn-sm enviar_link" data-survey-id="<?php echo $survey_id; ?>" data-group-id="${row.id_grupo}" data-participant-id="${row.participantes[i].id}"><span class="btn-label"><i class="fa fa-paper-plane me-2"></i></span>Enviar</button>
                      </td>
                      </tr>
                      `);
                  }
                }

                tabelaCorpo.append(`
                  <div class="col">
                  <div class="card h-100">
                  <div class="card-body" style="display: flex; flex-direction: column; height: 100%;">

                  <div class="text_itens">
                  <h5 class="card-title">${row.participantes[0].nome}</h5>
                  ${row.participantes && row.participantes[1]
                    ? `<p class="card-subtitle mb-2 text-body-secondary lineclamp2">${row.participantes[1].nome !== null ? row.participantes[1].nome : 'Ausente'}</p>`
                    : '<p class="card-subtitle mb-2 text-body-secondary lineclamp2">Ausente</p>'
                  }
                  ${row.participantes && row.participantes[2]
                    ? `<p class="card-subtitle mb-2 text-body-secondary lineclamp2">${row.participantes[2].nome !== null ? row.participantes[2].nome : 'Ausente'}</p>`
                    : '<p class="card-subtitle mb-2 text-body-secondary lineclamp2">Ausente</p>'
                  }
                  ${row.participantes && row.participantes[3]
                    ? `<p class="card-subtitle mb-2 text-body-secondary lineclamp2">${row.participantes[3].nome !== null ? row.participantes[3].nome : 'Ausente'}</p>`
                    : '<p class="card-subtitle mb-2 text-body-secondary lineclamp2">Ausente</p>'
                  }
                  ${row.participantes && row.participantes[4]
                    ? `<p class="card-subtitle mb-2 text-body-secondary lineclamp2">${row.participantes[4].nome !== null ? row.participantes[4].nome : 'Ausente'}</p>`
                    : '<p class="card-subtitle mb-2 text-body-secondary lineclamp2">Ausente</p>'
                  }


                  </div>

                  <div class="buttons_itens" style="margin-top: auto;">

                  <button type="button" class="btn btn-sm btn-outline-primary group_rel" data-bs-toggle="modal" data-bs-target="#modal_rel" data-group-id="${row.id_grupo
                  }"><span class="btn-label"><i class="fa fa-chart-simple me-2"></i></span>Resultado</button>

                  <!--<button type="button" class="btn btn-sm btn-light editar_group"  data-bs-toggle="modal" data-bs-target="#modal_edit" data-group-id="${row.id_grupo
                  }"><span class="btn-label"><i class="fa fa-pen-to-square"></i></span></button>-->

                  <button type="button" class="btn btn-sm btn-light group_del" data-bs-toggle="modal" data-bs-target="#modal_excluir" data-group-id="${row.id_grupo
                  }"><span class="btn-label"><i class="fa fa-trash"></i></span></button>

                  </div>

                  </div>
                  </div>
                  </div>
                            `);
              });
            },
          });
        }
        // Fazer a requisição Ajax ao carregar a tabela ao iniciar
        atualizarTabela();

        $(".container").on("click", ".enviar_link", function () {
          var partId = $(this).attr("data-participant-id"); // pega o participante
          var groupId = $(this).attr("data-group-id"); //pega o grupo
          var surveyId = $(this).attr("data-survey-id"); //pega a survey

          // Enviar a solicitação Ajax
          $.ajax({
            type: "POST",
            url: "../include/api.php", // Página PHP que irá processar os dados
            data: {
              part_id: partId,
              group_id: groupId,
              survey_id: surveyId,
              indicador: "enviar_email",
            },
            success: function (response) {
              // Manipular a resposta da solicitação Ajax aqui
              if (response === "ok") {
                criarToastmini("success", "E-mail enviado!");
                atualizarTabela(); // Atualizar a tabela após a criação da avaliação
              } else {
                criarToastmini("danger", "Erro: " + response);
              }
            },
            error: function (xhr, status, error) {
              criarToastmini("danger", error);
            },
          });

        });

        //criar grupo
        $("#form_new").submit(function (event) {
          event.preventDefault(); // Impedir que o formulário seja enviado tradicionalmente
          var formData = $(this).serialize(); // Serializar os campos do formulário

          var user_id = "<?php echo $_SESSION['user_id']; ?>"; // Obtém o ID do usuário da sessão
          var aval_id = "<?php echo $survey_id; ?>"; // Obtém survey id

          // Adiciona a variável user_id ao formData
          formData += "&user_id=" + user_id;
          formData += "&aval_id=" + aval_id;
          formData += "&indicador=group_new";

          // Enviar a solicitação Ajax
          $.ajax({
            type: "POST",
            url: "../include/api.php", // Página PHP que irá processar os dados
            data: formData,
            success: function (response) {
              // Manipular a resposta da solicitação Ajax aqui

              if (response === "ok") {
                criarToastmini("success", "Novo grupo preparado!");
                atualizarTabela(); // Atualizar a tabela após a criação da avaliação
              } else {
                criarToastmini("danger", "Erro: " + response);
              }
            },
            error: function (xhr, status, error) {
              criarToastmini("danger", error);
            },
          });
        });

        // Primeiro excluir
        $(".container").on("click", ".group_del", function () {
          var groupId = $(this).attr("data-group-id"); // Obter o valor do atributo data-participant-id
          var groupName = $(this).closest(".card").find(".card-title").text();
          $("#group_del2").attr("data-group-id", groupId); // Por exemplo, passar o valor para outro botão com a classe "btn-process"
          $("#nome").text(groupName); // Passar o valor para o <span>
        });
        // Excluir participante
        $("#group_del2").on("click", function () {
          var groupId = $(this).attr("data-group-id");
          $.ajax({
            type: "POST",
            url: "../include/api.php",
            data: {
              id_group_del: groupId,
              indicador: "group_del",
            },

            success: function (response) {
              if (response === "ok") {
                $("#modal_del_close").click(); //fechar o canvas
                criarToastmini("success", "Grupo excluído do sistema!");
                atualizarTabela(); // Atualizar a tabela após a criação da avaliação
              } else {
              }
            },
            error: function (xhr, status, error) {
              criarToastmini("danger", error);
            },
          });
        });

        // Função para popular select e selecionar a opção correta
        function populateSelect(inputId, Ids) {
          var select = $("#" + inputId);

          // Criar um objeto de dados para enviar via POST
          var postData = {
            indicador: "group_view_select",
            creator_id: "<?php echo $_SESSION['user_id']; ?>",
          };

          // Popula as opções do select
          $.ajax({
            type: "POST",
            url: "../include/api.php", // Substitua pela URL correta para buscar as opções
            data: postData, // Enviar o objeto de dados via POST
            dataType: "json",
            success: function (options) {
              select.empty();
              select.append(new Option("Selecione...", ""));

              $.each(options, function (index, option) {
                select.append(new Option(option.nome, option.id));
              });

              // Seleciona a opção correta
              if (Ids === null) {
                select.val("");
              } else {
                select.val(Ids);
              }

              //console.log(Ids);
            },
            error: function (xhr, status, error) {
              console.log(error); // Tratar erros, se necessário
            },
          });
        }

        function setupValidation(formId) {
          var form = $("#" + formId);
          var selects = form.find(".select-validation");
          var submitButton = form.find(".submit-button");

          selects.on("change", function () {
            var selectedValues = [];

            selects.removeClass("is-invalid");
            submitButton.prop("disabled", false);

            selects.each(function () {
              var value = $(this).val();

              if (value !== "") {
                if (selectedValues.indexOf(value) !== -1) {
                  $(this).addClass("is-invalid");
                  submitButton.prop("disabled", true);
                }
                selectedValues.push(value);
              }
            });
          });
        }
        setupValidation("form_new");
        setupValidation("form_edit");

        // Primeiro editar
        $(".container").on("click", ".editar_group", function () {
          //pega o valor que esta no botao de icone do card
          var groupId = $(this).attr("data-group-id");
          //coloca o valor no botao #group_edit2
          $("#group_edit2").attr("data-group-id", groupId);

          // Criar um objeto de dados para enviar via POST
          var postData = {
            group_id: groupId, // Usar 'group_id' como chave no objeto de dados
            indicador: "group_view",
          };

          // Fazer uma solicitação AJAX para buscar os dados do participante pelo ID
          $.ajax({
            type: "POST",
            url: "../include/api.php", // Substitua pela URL correta para buscar os dados do servidor
            data: postData, // Enviar o objeto de dados via POST
            dataType: "json",
            success: function (data) {
              // Popula os selects e seleciona as opções corretas
              var selectIds = [
                "lider-edit",
                "part1-edit",
                "part2-edit",
                "part3-edit",
                "part4-edit",
              ];

              selectIds.forEach(function (selectId, index) {
                var idParticipante = data[index]
                  ? data[index].id_participante
                  : null;
                populateSelect(selectId, idParticipante);
              });

              //$("#id_edit").val(data[0].id);
              //console.log(data);
            },
            error: function (xhr, status, error) {
              console.log(error); // Tratar erros, se necessário
            },
          });
        });
        // Salvar alterações
        $("#form_edit").submit(function (event) {
          event.preventDefault(); // Impedir que o formulário seja enviado tradicionalmente
          var formData = $(this).serialize(); // Serializar os campos do formulário
          var groupId = $("#group_edit2").attr("data-group-id");

          // Adicionar o groupId ao formData
          formData += "&groupId=" + groupId;

          $.ajax({
            type: "POST",
            url: "../include/api.php",
            data: formData + "&indicador=group_edit", // Incluir os campos serializados e o indicador
            success: function (response) {
              if (response === "ok") {
                $("#modal_edit_close").click(); //fechar o canvas
                criarToastmini("success", "Participante atualizado com sucesso!");
                atualizarTabela(); // Atualizar a tabela após a criação da avaliação

              } else {
                criarToastmini("danger", response);
                //console.log(response);
              }
            },
            error: function (xhr, status, error) {
              //notyf.error(error);
              criarToastmini("danger", error);
              //console.log(response);
            },
          });
        });

        // Ver resultados
        $(".container").on("click", ".group_rel", function () {
          var groupId = $(this).attr("data-group-id"); // Obter o valor do atributo data-participant-id

          // Substitua "groupId" pelo valor desejado
          var novoHref = "grupo_detalhe.php?id=" + groupId;

          // Selecione o elemento <a> pelo ID "link_grupo_detalhe" e atualize o atributo href
          $("#link_grupo_detalhe").attr("href", novoHref);

          var postData = {
            id_grupo: groupId, // Usar 'group_id' como chave no objeto de dados
            indicador: "rel_view",
          };

          // Função para gerar uma string com estrelas com base no nível
          function getStarString(nivel) {
            var starString = "";
            for (var i = 0; i < nivel; i++) {
              starString += "<i class='fa fa-star'></i>";
            }
            return starString;
          }

          //vai fazer a tabela de notas
          $.ajax({
            type: "POST",
            url: "../include/api.php", // Substitua pela URL correta para buscar os dados do servidor
            data: postData, // Enviar o objeto de dados via POST
            dataType: "json",
            success: function (response) {
              var tabelaRel = $("#tabela-rel");
              tabelaRel.empty();
              // Itera sobre o JSON e constrói a tabela
              $.each(response, function (index, item) {
                var categoria = item.categoria;
                var participante1 = item.participante1;
                var outros_participantes = item.outros_participantes;
                var nivel_lider = item.nivel_lider;
                var nivel_outros = item.nivel_outros;
                var nivelLiderClass = "nota_" + item.nivel_lider; // Concatena o valor de item.nivel_lider
                var nivelOutrosClass = "nota_" + item.nivel_outros; // Concatena o valor de item.nivel_lider

                var newRow = $("<tr>");
                newRow.append("<td>" + categoria + "</td>");
                // Adicionar estrelas com base no nível
                newRow.append("<td  class='" + nivelLiderClass + " text-end'><span class='me-2'>" + getStarString(nivel_lider) + "<br class='empurra d-sm-none'></span>" + participante1 + " </td>");
                newRow.append("<td  class='" + nivelOutrosClass + " text-end'> <span class='me-2'>" + getStarString(nivel_outros) + "</span><br class='empurra d-sm-none'>" + outros_participantes + "</td>");

                //newRow.append("<td class='" + nivelLiderClass + "'>" + participante1 + "</td>");
                //newRow.append("<td class='" + nivelOutrosClass + "'>" + outros_participantes + "</td>");
                $("#tabela-rel").append(newRow);
              });

            },
            error: function (xhr, status, error) {
              console.log(error); // Tratar erros, se necessário
            },
          });

          // Prencher os dados dos participantes
          $.ajax({
            type: "POST",
            url: "../include/api.php",
            data: {
              indicador: "group_view",
              group_id: groupId,
            },
            dataType: "json",
            success: function (resultados) {
              var tabela_grupo = $("#tabela-parts-status");
              tabela_grupo.empty(); // Limpar a tabela antes de preencher
              /*
                            $.each(resultados, function (index, row) {
                              //console.log(row);

                                              for (let i = 0; i < row.participantes.length; i++) {
                                                if (row.participantes[i].nome !== null) {
                                                  tabela_grupo.append(`
                                                                    <tr>
                                                                    <td>${row.participantes[0].nome}</td>
                                                                    <td>${row.participantes[i].nome} <small class="text-body-secondary">(${row.participantes[i].email})</small></td>
                                                                    <td>${status} ${fezaval}</td>
                                                                    <td class="text-end">
                                                                    <button class="btn btn-light btn-sm avalurl" type="button" data-clipboard-text="https://LiderScan.com.br/teste.php?id_part=${row.participantes[i].id}&id_survey=<?php echo $survey_id; ?>&id_grupo=${row.id_grupo}"><span class="btn-label"><i class="fa fa-link me-2"></i></span>Link</button>
                                                                    <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_excluir" data-participant-id="${row.participantes[i].id}"><span class="btn-label"><i class="fa fa-paper-plane me-2"></i></span>Enviar</button>
                                                                    </td>
                                                                    </tr>
                                                                    `);
                                                  console.log(row.participantes[0].nome);
                                                }
                                              }
                            });*/
              var t_parts = $("#t_parts");
              t_parts.empty(); // Limpar a tabela antes de preencher

              // Verifique se há pelo menos um resultado
              if (resultados.length > 0) {
                // Coloque o primeiro "nome" em um elemento h5
                t_parts.append(`<h5 class="card-title" id="t_lider">${resultados[0].nome}</h5>`);

                // Crie um elemento p para cada um dos demais "nomes"
                resultados.slice(1).forEach(function (row) {
                  const nome = row.nome !== null ? row.nome : "Ausente";
                  t_parts.append(`<p class="card-subtitle text-body-secondary">${nome}</p>`);
                });
              }
            },
          });

        });

      });
    </script>
  </body>

</html>