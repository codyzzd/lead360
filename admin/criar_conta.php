<!DOCTYPE html>
<html lang="pt-BR">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>LiderScan - Criar conta</title>
    <?php include '../include/_head.php' ?>
  </head>

  <body>
    <? include '../include/_toasters.php' ?>
    <? include '../include/_navoff.php' ?>

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-4">
          <div class="mb-3">

            <h2>Criar conta</h2>

            <form id="criarForm">
              <div class="mb-3">
                <label for="email"
                       class="form-label">Email</label>
                <input type="email"
                       class="form-control"
                       id="email"
                       name="email"
                       required
                       maxlength="60">
              </div>
              <div class="mb-3">
                <label for="senha"
                       class="form-label">Senha</label>
                <input type="password"
                       class="form-control"
                       id="senha"
                       name="senha"
                       required
                       maxlength="60">
              </div>
              <button type="submit"
                      class="btn btn-primary">Criar conta</button>
            </form>
          </div>
          <div>
            <p>Já possui conta? <a href="login.php"
                 class="">Entrar</a></p>
          </div>
        </div>
      </div>
    </div>

    <?php include '../include/_jscripts.php' ?>

    <!-- criar conta -->
    <script>
      $(document).ready(function () {
        $("#criarForm").submit(function (event) {
          event.preventDefault();

          // Obtenha os dados do formulário
          var formData = $(this).serialize();
          formData += "&indicador=user_new";

          // Solicitação Ajax para obter o sal do servidor
          $.ajax({
            type: "POST",
            url: "../include/api.php",
            data: formData,

            success: function (response) {
              if (response === "ok") {
                $("#modal_del_close").click(); //fechar o canvas
                criarToastmini('success', 'Usuario criado! Redirecionando em 3 segundos...');
                setTimeout(function () {
                  window.location.href = "login.php";
                }, 3000); // 3000 milissegundos = 3 segundos

              } else {
                criarToastmini('danger', response);
              }
            },
            error: function (xhr, status, error) {
              criarToastmini('danger', error);

            }
          });
        });
      });
    </script>

  </body>

</html>