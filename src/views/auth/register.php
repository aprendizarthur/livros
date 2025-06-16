<?php

    declare(strict_types=1);
    require("../../../vendor/autoload.php");
    use core\SessionManager;
    use models\UserModel;
    use controllers\AuthController;
    use database\{Database, UserDAO};

    $sessionManager = new SessionManager;
    $sessionManager->redirectLoggedIN();

    $database = new Database("localhost", "livros", "root", "");
    $userDAO = new UserDAO($database);

    $userModel = new UserModel;
    $dadosPOST = $userModel->getDataRegisterPOST();

    $AuthController = new AuthController;
    try {
        $AuthController->RegisterUser($userDAO, $userModel, $dadosPOST);
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
            <div class="col-11 col-md-6 box-form text-center">
                <h1 class="dm-sans-bold d-block">Criar conta</h1>
                
                <form class="form dm-sans-regular text-left" method="post">
                    <div class="form-group">
                        <label class="dm-sans-bold" for="username">Username</label>
                        <input required class="form-control" type="text" name="username" id="username">
                    </div>

                    <div class="form-group">
                        <label class="dm-sans-bold" for="email">E-mail</label>
                        <input required class="form-control" type="email" name="email" id="email">
                    </div>

                    <div class="form-group">
                        <label class="dm-sans-bold" for="pass">Senha</label>
                        <input required class="form-control" type="password" name="pass" id="pass">
                    </div>

                    <div class="form-group">
                        <label class="dm-sans-bold" for="passC">Confirmar senha</label>
                        <input required class="form-control" type="password" name="passC" id="passC">
                    </div>
                    <button class="btn btn-primary dm-sans-regular w-100 mt-3" name="submit" type="submit">Criar</button>
                </form>
                <a class="small-detail" href="login.php"><small class="dm-sans-light ">Já tenho uma conta</small></a>
            </div>
        </div>
    </div>
</body>
</html>