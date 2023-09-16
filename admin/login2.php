<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>LiderScan - Login</title>
    <?php include '../include/_head.php' ?>
  </head>

  <body>
    <? include '../include/_toasters.php' ?>

    <div class="container-fluid">
      <div class="row">
        <!-- Coluna para o login -->
        <div class="col-lg-4 col-md-5 col-sm-6">
          <div class="mb-3">
            <h2>Fazer Login</h2>
            <form id="loginForm">
              <div class="mb-3">
                <label for="email"
                       class="form-label">Email</label>
                <input type="email"
                       class="form-control"
                       id="email"
                       name="email"
                       required>
              </div>
              <div class="mb-3">
                <label for="senha"
                       class="form-label">Senha</label>
                <input type="password"
                       class="form-control"
                       id="senha"
                       name="senha"
                       required>
              </div>
              <button type="submit"
                      class="btn btn-primary">Entrar</button>
            </form>
          </div>
          <hr>
          <div>
            <p>Não tem conta? <a href="criar_conta.php"
                 class="">Criar conta</a></p>
          </div>
        </div>

        <!-- Coluna para a cor roxa -->
        <div class="col-lg-8 col-md-7 col-sm-6 purple-bg">
          <!-- Conteúdo roxo -->
        </div>
      </div>
    </div>

    <?php include '../include/_jscripts.php' ?>

    <script>
      $(document).ready(function () {
        $("#loginForm").submit(function (event) {
          event.preventDefault();

          var formData = $(this).serialize();

          $.ajax({
            type: "POST",
            url: "../include/api.php",
            data: formData + "&indicador=user_login",
            success: function (response) {
              if (response === "ok") {
                criarToastmini('success', 'Entrando...');
                //iniciar sessao e redirecionar o usuario
                window.location.href = "../admin/index.php";
                //console.log("deu certo");
              } else {
                criarToastmini('danger', response);
                //console.log("deu errado");
              }
            }
          });
        });
      });
    </script>

  </body>

</html>