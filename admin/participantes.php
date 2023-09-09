<? include '../include/_a_check_login.php' ?>
<!DOCTYPE html>
<html lang="pt-BR">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>LiderScan - Participantes </title>
    <?php include '../include/_head.php' ?>
  </head>

  <body>

    <?php include '../include/_toasters.php' ?>

    <?php include '../include/_nav.php' ?>

    <div class="container  mb-3">
      <div class="row ">
        <div class="col-md">
          <h2>Participantes</h2>
          <p>Adicione os participantes que irão participar das avaliações.</p>
        </div>
        <div class="col-md-auto text-md-end text-end d-grid gap-2 d-md-block">
          <button class="btn btn-outline-primary"
                  data-bs-toggle="modal"
                  data-bs-target="#modal_new_csv"
                  id="part_new_csv"><span class="btn-label"><i class="fa fa-file-csv me-2"></i></span>Adicionar por CSV</button>

          <button class="btn btn-primary"
                  data-bs-toggle="modal"
                  data-bs-target="#modal_new"
                  id="part_new"><span class="btn-label"><i class="fa fa-plus me-2"></i></span>Adicionar participante</button>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-12">
          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col"
                    class="col-auto">Nome</th>
                <th scope="col"
                    class="col-auto">E-mail</th>
                <th scope="col"
                    class="col-3 col-sm-2 text-end">Opções</th>
              </tr>
            </thead>
            <tbody id="tabela-corpo">
              <!-- Os dados serão preenchidos aqui via Ajax -->

            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- modal criar CSV -->
    <div class="modal fade"
         tabindex="-1"
         id="modal_new_csv">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Adicionar por CSV</h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                    id="modal_new_close_csv"></button>
          </div>
          <form id="form_new_csv">
            <div class="modal-body">
              <div id="csv_result"
                   class="mb-3"></div>
              <div class="mb-3">

                <label for="formFile"
                       class="form-label">Arquivo CSV</label>
                <input class="form-control"
                       type="file"
                       id="formFile"
                       accept=".csv">
              </div>
              <div class="progress"
                   role="progressbar"
                   aria-label="Basic example"
                   aria-valuenow="0"
                   aria-valuemin="0"
                   aria-valuemax="100">
                <div class="progress-bar"
                     style="width: 0%"></div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button"
                      class="btn btn-outline-secondary"
                      data-bs-dismiss="modal">Fechar</button>
              <button type="submit"
                      class="btn btn-primary">Adicionar CSV</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- modal criar -->
    <div class="modal fade"
         tabindex="-1"
         id="modal_new">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Adicionar participante</h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                    id="modal_new_close"></button>
          </div>
          <form id="form_new">
            <div class="modal-body">

              <div class="mb-3">
                <label for="nome_part_new"
                       class="form-label">Nome</label>
                <input type="text"
                       class="form-control"
                       id="nome_part_new"
                       name="nome_part_new"
                       required
                       maxlength="65"
                       placeholder="ex: Maria Fatima">
              </div>
              <div class="">
                <label for="email_part_new"
                       class="form-label">Email</label>
                <input type="email"
                       class="form-control"
                       id="email_part_new"
                       name="email_part_new"
                       required
                       maxlength="65"
                       placeholder="ex: maria@gmail.com">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button"
                      class="btn btn-outline-secondary"
                      data-bs-dismiss="modal">Cancelar</button>
              <button type="submit"
                      class="btn btn-primary">Adicionar</button>
            </div>
          </form>
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
            <h5 class="modal-title">Editar participante</h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                    id="modal_edit_close"></button>
          </div>
          <form id="form_edit">
            <div class="modal-body">
              <div class="mb-3 d-none">
                <label for="id_edit"
                       class="form-label">Id</label>
                <input type="text"
                       class="form-control"
                       id="id_edit"
                       name="id_edit"
                       required
                       maxlength="65">
              </div>
              <div class="mb-3">
                <label for="nome_part_edit"
                       class="form-label">Nome</label>
                <input type="text"
                       class="form-control"
                       id="nome_part_edit"
                       name="nome_part_edit"
                       required
                       maxlength="65"
                       placeholder="ex: Maria Fatima">
              </div>
              <div class="">
                <label for="email_part_edit"
                       class="form-label">Email</label>
                <input type="email"
                       class="form-control"
                       id="email_part_edit"
                       name="email_part_edit"
                       required
                       maxlength="65"
                       placeholder="ex: maria@gmail.com">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button"
                      class="btn btn-outline-secondary"
                      data-bs-dismiss="modal">Cancelar</button>
              <button type="submit"
                      class="btn btn-primary">Salvar</button>
            </div>
          </form>
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
            <h5 class="modal-title">Excluir participante</h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                    id="modal_del_close"></button>
          </div>
          <div class="modal-body">
            Você vai deletar o participante <strong id="nome"></strong>.<br>
          </div>
          <div class="modal-footer">
            <button type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal">Não, Cancelar</button>
            <button type="button"
                    class="btn btn-danger"
                    id="part_del2">Sim, Excluir!</button>
          </div>
        </div>
      </div>
    </div>

    <?php include '../include/_jscripts.php' ?>

    <script>
      $(document).ready(function () {




        // Atualizar tabela
        function atualizarTabela() {
          $.ajax({
            type: "POST", // Alterado para POST
            url: "../include/api.php", // Substitua pela URL correta
            data: {
              indicador: "part_tabela", // Adicione um parâmetro de ação para identificar a ação no servidor
              user_id: "<?php echo $_SESSION['user_id']; ?>"
            }, // Inclua o user_id como parâmetro
            dataType: "json",
            success: function (resultados) {
              var tabelaCorpo = $("#tabela-corpo");
              tabelaCorpo.empty(); // Limpar a tabela antes de preencher

              // Preencher a tabela com os dados recebidos
              $.each(resultados, function (index, row) {
                tabelaCorpo.append(`
                            <tr>
                            <td class="descricao_td" title="${row.nome}">${row.nome}</td>
                            <td class="descricao_td" title="${row.email}">${row.email}</td>
                            <td class="text-end">
                            <button class="btn btn-light btn-sm editar_part" type="button" data-bs-toggle="modal" data-bs-target="#modal_edit" data-participant-id="${row.id}"><span class="btn-label"><i class="fa fa-pen-to-square"></i></span></button>
                            <button type="button" class="btn btn-light btn-sm part_del" data-bs-toggle="modal" data-bs-target="#modal_excluir" data-participant-id="${row.id}"><span class="btn-label"><i class="fa fa-trash"></i></span></button>
                            </td>
                            </tr>
                            `);
              });
            }
          });
        }
        // Atualizar ao abrir a pagina
        atualizarTabela();

        // Primeiro editar
        $(".container").on("click", ".editar_part", function () {
          var participantId = $(this).attr("data-participant-id");

          var $row = $(this).closest("tr"); // Encontra a linha (tr) onde o botão foi clicado
          var nome = $row.find("td:eq(0)").text(); // Obtém o valor da segunda célula
          var email = $row.find("td:eq(1)").text(); // Obtém o valor da terceira célula

          $("#id_edit").val(participantId); // Preenche o campo escondido com o ID do participante
          $("#nome_part_edit").val(nome); // Preenche o campo de nome de edição
          $("#email_part_edit").val(email); // Preenche o campo de email de edição
        });
        // Salvar alterações
        $("#form_edit").submit(function (event) {
          event.preventDefault(); // Impedir que o formulário seja enviado tradicionalmente
          var formData = $(this).serialize(); // Serializar os campos do formulário

          $.ajax({
            type: "POST",
            url: "../include/api.php",
            data: formData + "&indicador=part_edit", // Incluir os campos serializados e o indicador
            success: function (response) {
              if (response === "ok") {
                $("#modal_edit_close").click(); //fechar o canvas
                //notyf.success('Participante atualizado com sucesso!');
                criarToastmini('success', 'Participante atualizado com sucesso!');

                atualizarTabela(); // Atualizar a tabela após a criação da avaliação
              } else {

              }
            },
            error: function (xhr, status, error) {
              //notyf.error(error);
              criarToastmini('danger', error);
            }
          });
        });

        // Primeiro excluir
        $(".container").on("click", ".part_del", function () {
          var participantId = $(this).attr("data-participant-id"); // Obter o valor do atributo data-participant-id
          var participantName = $(this).closest("tr").find("td:first").text(); // Obter o valor da primeira coluna da linha na qual o botão está localizado
          $("#part_del2").attr("data-participant-id", participantId) // Por exemplo, passar o valor para outro botão com a classe "btn-process"
          $("#nome").text(participantName); // Passar o valor para o <span>
        });
        // Excluir participante
        $("#part_del2").on("click", function () {
          var participantId = $(this).attr("data-participant-id");

          $.ajax({
            type: "POST",
            url: "../include/api.php",
            data: {
              id_part_del: participantId,
              indicador: "part_del"
            },

            success: function (response) {
              if (response === "ok") {
                $("#modal_del_close").click(); //fechar o canvas
                criarToastmini('success', 'Participante excluído do sistema!');
                atualizarTabela(); // Atualizar a tabela após a criação da avaliação
              } else {

              }
            },
            error: function (xhr, status, error) {
              criarToastmini('danger', error);

            }
          });
        });

        //criar participante
        $("#form_new").submit(function (event) {
          event.preventDefault(); // Impedir que o formulário seja enviado tradicionalmente
          var formData = $(this).serialize(); // Serializar os campos do formulário
          var user_id = "<?php echo $_SESSION['user_id']; ?>"; // Obtém o ID do usuário da sessão

          $.ajax({
            type: "POST",
            url: "../include/api.php",
            data: formData + "&indicador=part_new&user_id=" + user_id, // Incluir os campos serializados e o indicador
            success: function (response) {
              if (response === "ok") {
                $("#modal_new_close").click(); //fechar o canvas
                $("#form_new")[0].reset();
                criarToastmini('success', 'Novo participante cadastrado com sucesso!');
                atualizarTabela(); // Atualizar a tabela após a criação da avaliação
              } else {

              }
            },
            error: function (xhr, status, error) {
              criarToastmini('danger', error);
            }
          });
        });

        //CSV
        $("#form_new_csv").submit(function (event) {
          event.preventDefault(); // Impedir que o formulário seja enviado tradicionalmente
          var user_id = "<?php echo $_SESSION['user_id']; ?>"; // Obtém o ID do usuário da sessão

          //apaga o que tem dentro
          $('#csv_result').empty();

          // Obter o arquivo selecionado
          var file = $("#formFile")[0].files[0];

          // Criar um objeto FormData para enviar o arquivo para o arquivo PHP
          var formData = new FormData();
          formData.append("file", file);
          formData.append("indicador", "part_new_csv");
          formData.append("id_creator", user_id);

          // Enviar o arquivo para o arquivo PHP
          $.ajax({
            url: "../include/api.php",
            type: "POST",
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function (resultados) {
              // Executar alguma ação após o sucesso
              $(".progress-bar").css("width", "100%");
              $(".progress-bar").css("width", "0%");
              criarToastmini('success', 'CSV incluido!');
              atualizarTabela(); // Atualizar a tabela após a criação da avaliação

              // Adicionar os resultados ao elemento #result_csv
              $("#csv_result").append(`
  <table class='table'>
    <tr>
      <th>Participantes</th>
      <th>Quantidade</th>
    </tr>
    <tr>
      <td>Válidos</td>
      <td>${resultados.qtd_validos}</td>
    </tr>
    <tr>
      <td>Inválidos</td>
      <td>${resultados.qtd_invalidos}</td>
    </tr>
    <tr>
      <td>Duplicados</td>
      <td>${resultados.qtd_duplicados}</td>
    </tr>
  </table>
`);

            },
            error: function (err) {
              // Executar alguma ação em caso de erro
              criarToastmini('danger', err);
            },
            beforeSend: function () {
              // Iniciar o progress bar
              $(".progress-bar").css("width", "0%");
            },
            progress: function (progress) {
              // Atualizar o progress bar
              $(".progress-bar").css("width", progress + "%");
              //console.log(progress);
            }
          });

        });

      });
    </script>

  </body>

</html>