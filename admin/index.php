<?php include '../include/_a_check_login.php' ?>
<!DOCTYPE html>
<html lang="pt-BR">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>LiderScan - Avaliação</title>
    <?php include '../include/_head.php' ?>
  </head>

  <body>
    <?php include '../include/_toasters.php' ?>
    <?php include '../include/_nav.php' ?>

    <div class="container mt-3 mt-sm-5 mb-3">
      <div class="row ">
        <div class="col-md">
          <h2>Avaliações</h2>
          <p>Crie avaliações e seus grupos de participantes.</p>
        </div>
        <div class="col-md-auto text-md-end text-end d-grid gap-2 d-md-block">
          <button class="btn btn-primary"
                  data-bs-toggle="modal"
                  data-bs-target="#modal_new"
                  id="aval_new"><span class="btn-label"><i class="fa fa-plus me-2"></i></span>Criar avaliação</button>
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
                    class="col-auto">Descrição</th>

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

    <!-- modal criar -->
    <div class="modal fade"
         tabindex="-1"
         id="modal_new">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Criar avaliação</h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                    id="modal_new_close"></button>
          </div>
          <form id="form_new">
            <div class="modal-body">
              <div class="mb-3">
                <label for="nome_aval"
                       class="form-label">Nome da Avaliação</label>
                <input type="text"
                       class="form-control"
                       id="nome_aval"
                       name="nome_aval"
                       required
                       maxlength="65"
                       placeholder="ex: Setor Administrativo 1">
              </div>
              <div class="">
                <label for="aval_desc"
                       class="form-label">Descrição</label>
                <textarea class="form-control"
                          id="aval_desc"
                          name="aval_desc"
                          rows="4"
                          maxlength="200"
                          placeholder="ex: Avaliando o progresso dos lideres pós treinamento da empresa..."></textarea>
                <small class="form-text text-muted"><span id="contador">0</span>/200 caracteres</small>
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

    <!-- modal excluir -->
    <div class="modal fade"
         tabindex="-1"
         id="modal_excluir">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Excluir avaliação</h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                    id="modal_del_close"></button>
          </div>
          <div class="modal-body">
            Você vai excluir a avaliação <strong id="nome"></strong> junto com todos os grupos respectivos criados.<br>
          </div>
          <div class="modal-footer">
            <button type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal">Não, Cancelar</button>
            <button type="button"
                    class="btn btn-danger"
                    id="aval_del2">Sim, Excluir!</button>
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
              indicador: "aval_tabela", // Adicione um parâmetro de ação para identificar a ação no servidor
              user_id: "<?php echo $_SESSION['user_id']; ?>"
            }, // Inclua o user_id como parâmetro
            dataType: "json",
            success: function (resultados) {
              var tabelaCorpo = $("#tabela-corpo");
              tabelaCorpo.empty(); // Limpar a tabela antes de preencher

              // Preencher a tabela com os dados recebidos
              $.each(resultados, function (index, row) {
                //var dataFormatada = moment(row.data).format("DD/MM/YYYY - HH:mm");

                tabelaCorpo.append(`
                  <tr>
                  <td>${row.nome}</td>
                  <td class="descricao_td" title="${row.descri}">${row.descri}</td>
                  <td class="text-end">
                  <a href="avaliacao.php?id=${row.id}" class="btn btn-sm btn-outline-primary">Entrar</a>
                  <button type="button" class="btn btn-sm btn-light part_del" data-bs-toggle="modal" data-bs-target="#modal_excluir" data-aval-id="${row.id}"><span class="btn-label"><i class="fa fa-trash"></i></span></button>
                  </td>
                  </tr>
                            `);
              });
            }
          });
        }
        // Atualizar ao abrir a pagina
        atualizarTabela();

        // Função para criar avaliação
        $("#form_new").submit(function (event) {
          event.preventDefault(); // Impedir que o formulário seja enviado tradicionalmente
          var formData = $(this).serialize(); // Serializar os campos do formulário
          var user_id = "<?php echo $_SESSION['user_id']; ?>"; // Obtém o ID do usuário da sessão

          $.ajax({
            type: "POST",
            url: "../include/api.php",
            data: formData + "&indicador=aval_new&user_id=" + user_id, // Incluir os campos serializados e o indicador
            success: function (response) {
              if (response === "ok") {
                $("#modal_new_close").click(); //fechar o canvas
                $("#form_new")[0].reset();
                criarToastmini('success', 'Nova avaliação cadastrada com sucesso!');
                atualizarTabela(); // Atualizar a tabela após a criação da avaliação
              } else {

              }
            },
            error: function (xhr, status, error) {
              criarToastmini('danger', error);
            }
          });
        });

        // Primeiro excluir
        $(".container").on("click", ".part_del", function () {
          var avalId = $(this).attr("data-aval-id"); // Obter o valor do atributo data-participant-id
          var avalName = $(this).closest(".card").find(".card-title").text();

          $("#aval_del2").attr("data-aval-id", avalId) // Por exemplo, passar o valor para outro botão com a classe "btn-process"
          $("#nome").text(avalName); // Passar o valor para o <span>
        });
        // Excluir participante
        $("#aval_del2").on("click", function () {
          var avalId = $(this).attr("data-aval-id");


          $.ajax({
            type: "POST",
            url: "../include/api.php",
            data: {
              id_aval_del: avalId,
              indicador: "aval_del"
            },

            success: function (response) {
              if (response === "ok") {
                $("#modal_del_close").click(); //fechar o canvas
                criarToastmini('success', 'Avaliação e grupos excluídos do sistema!');
                atualizarTabela(); // Atualizar a tabela após a criação da avaliação
              } else {

              }
            },
            error: function (xhr, status, error) {
              criarToastmini('danger', error);

            }
          });
        });

        //contar letras
        $("#aval_desc").on("input", function () {
          var contador = $(this).val().length;
          $("#contador").text(contador);
        });
      });
    </script>

  </body>

</html>