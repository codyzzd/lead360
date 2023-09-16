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
    <style>
      /* Estilo para a cor roxa no restante da tela */
      .splash {
        background-color: purple;
        background-image: url('/i/back_login.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
      }
    </style>
    <? include '../include/_toasters.php' ?>

    <div class="container-fluid">
      <div class="row">

        <!-- Coluna para a cor roxa -->
        <div class="col-xl-8 col-lg-7 col-md-5 col-sm-7 splash p-5 vh-100">

          <figure class="bg-opacity-10 bg-dark">
            <blockquote class="blockquote">
              <p>”O melhor investimento que uma empresa pode fazer é na capacitação e desenvolvimento de seus líderes, pois são eles que moldam o futuro da organização.”</p>
            </blockquote>
            <figcaption class="blockquote-footer">
              Diana Moreira <!--<cite title="Source Title">Source Title</cite>-->
            </figcaption>
          </figure>

        </div>

        <!-- Coluna para o login -->
        <div class="col-xl-4 col-lg-5 col-md-7 col-sm-5 p-5">
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