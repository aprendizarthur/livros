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
    $userDAO = new UserDAO($database);
    $userModel = new UserModel;
    $dadosPOST = $userModel->getDataUpdatePOST();
    
    $AuthController = new AuthController;
    //aguardando possível logout no POST submit-logout
    $AuthController->LogoutUser();

    //aguardando POST submit-update para atualizar dados do usuário
    try {
        $AuthController->UpdateUser($userModel, $userDAO, $dadosPOST);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }


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
                <a href="../books/library.php" style="color: #000000"><i class="fa-solid fa-arrow-left fa-xl"></i></a>
                <h2 class="dm-sans-bold p-0 m-0">Editar conta</h2>
                <i class="fa-solid fa-trash fa-xl" style="color: white;"></i>
            </div>
            <div class="col-11 col-md-12 box-content">
                 <?php 
                    //mostrando formulário com dados do usuário para editar
                    try {
                        $AuthController->ShowUserUpdateForm($userDAO);
                    } catch (\Exception $e) {
                        echo $e->getMessage();
                    }
                ?>
            </div>
        </div>
    </div>
</body>
</html>