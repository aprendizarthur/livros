<?php

    declare(strict_types=1);
    require("../../../vendor/autoload.php");
    use controllers\{AuthController, LibraryController};
    use core\SessionManager;
    use database\{Database, UserDAO, BookDAO}; 
    use models\UserModel;

    $sessionManager = new SessionManager;
    $sessionManager->redirectNOTLoggedIN();

    $database = new Database("localhost", "livros", "root", "");
    $bookDAO = new BookDAO($database);
    
    $AuthController = new AuthController;
    //aguardando possível logout no POST submit-logout
    $AuthController->LogoutUser();

    $LibraryController = new LibraryController;

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
</head>
<body>
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-11 col-md-12 box-nav d-flex justify-content-around align-items-center">
                <a href="newbook.php"><i class="fa-solid fa-plus fa-xl"></i></a>
                <a class="selected" href=""><i class="fa-solid fa-book-bookmark fa-xl"></i></a>
                <a href=""><i class="fa-solid fa-chart-simple fa-xl"></i></a>
                <a href=""><i class="fa-solid fa-user fa-xl"></i></a>
                <form id="form-logout" method="POST"><button class="btn-submit-alt" name="submit-logout" type="submit" style="color:rgb(255, 178, 178);"><i class="fa-solid fa-power-off fa-xl"></i></button></form>
            </div>
            <div class="col-11 col-md-12 box-content">
                <h1 class="dm-sans-bold">Minha biblioteca</h1>
                
                <form id="form-search" method="GET" class="dm-sans-regular d-inline">
                    <div class="form-group">
                        <input required placeholder="  Pesquisar por título ou gênero" type="search" name="search" id="search">
                    </div>
                </form>
                
                <div class="row d-flex justify-content-left box-list-books">
                    <?php 
                        //exibindo resultado de pesquisa do usuário
                        try {
                            $LibraryController->SearchBook($bookDAO) ;
                        } catch (\Exception $e) {echo $e->getMessage();}

                        //exibindo biblioteca do usuário (quando não houver pesquisa no GET)
                        try {
                            $LibraryController->ShowLibrary($bookDAO);
                        } catch (\Exception $e) {echo $e->getMessage();}
                    ?>
                </div>
                <!-- 
                <div class="box-details">
                    <i class="fa-solid fa-spinner girar mr-2"></i><h2 class="dm-sans-regular d-inline">Relatório </h2>
                    <p class="dm-sans-regular mt-2 mb-0">Atualmente você possui <strong>89</strong> livros na biblioteca: <span class="lidos-livros"><i class="fa-solid fa-circle-check fa-xsm mr-1"></i> 75 lidos</span>, <span class="em-leitura-livros"><i class="fa-solid fa-clock fa-xsm mr-1"></i> 2 em leitura</span> e <span class="por-ler-livros"><i class="fa-solid fa-circle-exclamation fa-xsm mr-1"></i> 12 por ler</span>.</p>
                </div> 
                -->
            </div>
        </div>
    </div>
</body>
</html>