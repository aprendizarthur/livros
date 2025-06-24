<?php

    declare(strict_types=1);
    require("../../../vendor/autoload.php");
    use controllers\{LibraryController, AuthController};
    use core\SessionManager;
    use database\{Database, BookDAO}; 
    use models\{UserModel, BookModel};

    $sessionManager = new SessionManager;
    $sessionManager->redirectNOTLoggedIN();

    $database = new Database("localhost", "livros", "root", "");
    $bookDAO = new BookDAO($database);

    $bookModel = new BookModel;
    $dataPOST = $bookModel->getDataRegisterPOST();
    
    $LibraryController = new LibraryController;
    try {
        //aguardando dados para registrar livro
        $LibraryController->RegisterBook($bookModel, $bookDAO, $dataPOST);        
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
                <a href="library.php" style="color: #000000"><i class="fa-solid fa-arrow-left fa-xl"></i></a>
                <h2 class="dm-sans-bold p-0 m-0">Novo Livro</h2>
                <i class="fa-solid fa-trash fa-xl" style="color: white;"></i>
            </div>
            <div class="col-11 col-md-12 box-content">
                <form class="form dm-sans-regular text-left" method="post">
                    <div class="form-group">
                        <label class="dm-sans-bold" for="title">Título</label>
                        <input required class="form-control" type="text" name="title" id="title">
                    </div>
        
                    <div class="form-group">
                        <label class="dm-sans-bold" for="author">Autor</label>
                        <input required class="form-control" type="text" name="author" id="author">
                    </div>

                    <div class="form-group">
                        <label class="dm-sans-bold" for="status">Status</label>
                        <select class="form-control pr-1" name="status" id="status">
                            <option class="por-ler" value="por-ler">Por Ler</option>
                            <option class="em-leitura" value="em-leitura">Lendo</option>
                            <option class="lidos" value="lidos">Lido</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="dm-sans-bold" for="genre">Gênero</label>
                            <select required class="form-control pr-1" name="genre" id="genre">
                            <option selected value="">Selecionar</option>
                            <option value="Autoajuda">Autoajuda</option>
                            <option value="Aventura">Aventura</option>
                            <option value="Biografia">Biografia</option>
                            <option value="Drama">Drama</option>
                            <option value="Fantasia">Fantasia</option>
                            <option value="Ficção">Ficção</option>
                            <option value="Ficção científica">Ficção Científica</option>
                            <option value="Filosofia">Filosofia</option>
                            <option value="Historia">História</option>
                            <option value="Infantil">Infantil</option>
                            <option value="Livro Didaticos">Livros Didáticos</option>
                            <option value="Mistério">Mistério</option>
                            <option value="Não-ficção">Não Ficção</option>
                            <option value="Poesia">Poesia</option>
                            <option value="Romance">Romance</option>
                            <option value="Suspense">Suspense</option>
                            <option value="Tecnologia">Tecnologia</option>
                        </select>
                    </div>

                    <div class="form-group w-45" style="float: left;">
                        <label class="dm-sans-bold" for="year ">Ano <small class="dm-sans-light">(Opcional)</small></label>
                        <input class="d-inline form-control" type="number" name="year" id="year">
                    </div>

                    <div class="form-group w-45" style="float: right;">
                        <label class="dm-sans-bold" for="pages">Páginas <small class="dm-sans-light">(Opcional)</small></label>
                        <input class=" d-inline form-control" type="number" name="pages" id="pages">
                    </div>
                            
                    <button class="btn btn-primary dm-sans-bold w-100 mt-3" style="clear: both;"name="submit-newbook" type="submit">Adicionar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>