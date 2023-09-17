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
    <? include '../include/_navoff.php' ?>

    <div class="d-flex flex-column flex-shrink-0 p-3 bg-light"
         style="width: 280px;">
      <a href="/"
         class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
        <svg class="bi me-2"
             width="40"
             height="32">
          <use xlink:href="#bootstrap"></use>
        </svg>
        <span class="fs-4">Sidebar</span>
      </a>
      <hr>
      <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
          <a href="#"
             class="nav-link active"
             aria-current="page">
            <svg class="bi me-2"
                 width="16"
                 height="16">
              <use xlink:href="#home"></use>
            </svg>
            Home
          </a>
        </li>
        <li>
          <a href="#"
             class="nav-link link-dark">
            <svg class="bi me-2"
                 width="16"
                 height="16">
              <use xlink:href="#speedometer2"></use>
            </svg>
            Dashboard
          </a>
        </li>
        <li>
          <a href="#"
             class="nav-link link-dark">
            <svg class="bi me-2"
                 width="16"
                 height="16">
              <use xlink:href="#table"></use>
            </svg>
            Orders
          </a>
        </li>
        <li>
          <a href="#"
             class="nav-link link-dark">
            <svg class="bi me-2"
                 width="16"
                 height="16">
              <use xlink:href="#grid"></use>
            </svg>
            Products
          </a>
        </li>
        <li>
          <a href="#"
             class="nav-link link-dark">
            <svg class="bi me-2"
                 width="16"
                 height="16">
              <use xlink:href="#people-circle"></use>
            </svg>
            Customers
          </a>
        </li>
      </ul>
      <hr>
      <div class="dropdown">
        <a href="#"
           class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle"
           id="dropdownUser2"
           data-bs-toggle="dropdown"
           aria-expanded="false">
          <img src="https://github.com/mdo.png"
               alt=""
               width="32"
               height="32"
               class="rounded-circle me-2">
          <strong>mdo</strong>
        </a>
        <ul class="dropdown-menu text-small shadow"
            aria-labelledby="dropdownUser2">
          <li><a class="dropdown-item"
               href="#">New project...</a></li>
          <li><a class="dropdown-item"
               href="#">Settings</a></li>
          <li><a class="dropdown-item"
               href="#">Profile</a></li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li><a class="dropdown-item"
               href="#">Sign out</a></li>
        </ul>
      </div>
    </div>

    <div class="container">
      <div class="row justify-content-center">

        <div class="col-12 col-lg-4">
          <div class="mb-3">
            <div id="resultado"></div> <!-- Aqui será exibida a mensagem de sucesso/erro -->
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
          <!-- <hr>
          <div>
            <p>Não tem conta? <a href="criar_conta.php"
                 class="">Criar conta</a></p>
          </div>-->
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