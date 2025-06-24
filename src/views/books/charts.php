<?php

    declare(strict_types=1);
    require("../../../vendor/autoload.php");
    use controllers\{AuthController, LibraryController};
    use core\SessionManager;
    use database\{Database, UserDAO, BookDAO}; 
    use models\UserModel;

    $sessionManager = new SessionManager;
    $sessionManager->redirectNOTLoggedIN();
    
    $AuthController = new AuthController;
    //aguardando possível logout no POST submit-logout
    $AuthController->LogoutUser();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Charset e viewport -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Título, Ícone, Descrição e Cor de tema p/ navegador -->
    <title>Título</title>
    <link rel="icon" type="image/x-icon" href="">
    <meta name="description" content="">
    <meta name="theme-color" content="#FFFFFF">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- Fontawesome JS -->
    <script src="https://kit.fontawesome.com/6afdaad939.js" crossorigin="anonymous">      </script>
    <!-- Folha CSS-->
    <link rel="stylesheet" type="text/css" href="../../../public/css/style.css">
    <!--Google Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <!--Charts JS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-11 col-md-12 box-nav d-flex justify-content-around align-items-center">
                <a href="newbook.php"><i class="fa-solid fa-plus fa-xl"></i></a>
                <a href="library.php"><i class="fa-solid fa-book-bookmark fa-xl"></i></a>
                <a class="selected" href="charts.php"><i class="fa-solid fa-chart-simple fa-xl"></i></a>
                <a href="../auth/update.php"><i class="fa-solid fa-user fa-xl"></i></a>
                <form id="form-logout" method="POST"><button class="btn-submit-alt" name="submit-logout" type="submit" style="color:rgb(255, 178, 178);"><i class="fa-solid fa-power-off fa-xl"></i></button></form>
            </div>
            <div class="col-11 col-md-12 box-content">
                <div class="row d-flex p-2">
                    <div class="col-12 col-md-6 box-canvas">
                        <h2 class="dm-sans-bold mb-3">Livros por Status</h2>
                        <canvas class="mb-5" id="chartStatus"></canvas>
                    </div>
                    
                    <div class="col-12 col-md-6 box-canvas">
                        <h2 class="dm-sans-bold mb-3">Livros por nº de Páginas</h2>
                        <canvas class="mb-5" id="chartTotalPages"></canvas>
                    </div>

                    <div class="col-12 col-md-6 box-canvas">
                        <h2 class="dm-sans-bold mb-3">Páginas por Status</h2>
                        <canvas class="mb-5" id="chartPages"></canvas>
                    </div>
                    
                    <div class="d-none d-lg-block col-12 col-md-6 box-canvas">
                        <h2 class="dm-sans-bold mb-3">Livros por Gênero</h2>
                        <canvas class="mb-5" id="chartGenre"></canvas>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!--Chamando script JS que gera os gráficos-->
    <script src="../../../public/js/charts.js"></script>
</body>
</html>