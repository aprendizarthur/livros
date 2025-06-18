<?php

    declare(strict_types=1);
    require("../../../vendor/autoload.php");
    use controllers\LibraryController;
    use core\SessionManager;
    use database\{Database, UserDAO, BookDAO}; 
    use models\UserModel;

    $sessionManager = new SessionManager;
    $sessionManager->redirectNOTLoggedIN();

    $database = new Database("localhost", "livros", "root", "");
    $bookDAO = new BookDAO($database);

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
                <a href="library.php" style="color: #000000"><i class="fa-solid fa-arrow-left fa-xl"></i></a>
                <h2 class="dm-sans-bold p-0 m-0">Editar Livro</h2>
                <a href="library.php" style="color:rgb(255, 178, 178);"><i class="fa-solid fa-trash fa-xl"></i></a>
            </div>
            <div class="col-11 col-md-12 box-content">
                <form id="form-update-book" class="form dm-sans-regular text-left" method="post">
                    
                    <details class="mb-3">
                        <summary><h3 class="dm-sans-bold d-inline mr-2">Status do Livro</h3><small class="d-inline small-details" style="color: #C7C7C7; text-decoration: none;">(Clique para expandir)</small></summary>
                            <hr>
                            <div class="form-group">
                                    <select value="Fantasia" class="form-control pr-1" name="genre" id="genre">
                                    <option selected value=""></option>
                                    <option class="por-ler" value="por-ler">Por Ler</option>
                                    <option class="em-leitura" value="em-leitura">Lendo</option>
                                    <option class="lidos" value="lidos">Lido</option>
                                </select>
                            </div>
                    </details>

                    <details>
                        <summary><h3 class="dm-sans-bold d-inline mr-2">Dados Técnicos</h3><small class="d-inline small-details" style="color: #C7C7C7; text-decoration: none;">(Clique para expandir)</small></summary>
                            <hr>
                            <div class="form-group">
                                <label class="dm-sans-bold" for="title">Título</label>
                                <input class="form-control" type="text" name="title" id="title">
                            </div>
                
                            <div class="form-group">
                                <label class="dm-sans-bold" for="author">Autor</label>
                                <input class="form-control" type="text" name="author" id="author">
                            </div>

                            <div class="form-group w-45" style="float: left;">
                                <label class="dm-sans-bold" for="year ">Ano Publicação</label>
                                <input class="d-inline form-control" type="number" name="year" id="year">
                            </div>
        
                            <div class="form-group w-45" style="float: right;">
                                <label class="dm-sans-bold" for="pages">Páginas</label>
                                <input class="d-inline form-control" type="number" name="pages" id="pages">
                            </div>
                            
                            <div class="form-group">
                                    <select value="Fantasia" class="form-control pr-1" name="genre" id="genre">
                                    <option selected value=""></option>
                                    <option value="Autoajuda">Autoajuda</option>
                                    <option value="Aventura">Aventura</option>
                                    <option value="Biografia">Biografia</option>
                                    <option value="Drama">Drama</option>
                                    <option value="Fantasia">Fantasia</option>
                                    <option value="Ficção">Ficção</option>
                                    <option value="Ficção científica">Ficção Científica</option>
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
                    </details>

                    <div class="form-group mt-3">
                        <label class="dm-sans-bold" for="notes">Ficha de leitura</label>
                        <textarea class="form-control" name="notes" id="notes" rows="8">aaa</textarea>
                    </div>
                            
                    <button class="btn btn-primary dm-sans-bold w-100 mt-3" name="submit" type="submit">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>