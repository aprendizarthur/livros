<?php
//arquivo php que trasnforma dados php em json, estes dados serão utilizados nos gráficos (JS)
    header('Content-Type: application/json');
    require("../../vendor/autoload.php");
    use core\SessionManager;
    use database\{Database, BookDAO}; 

    $sessionManager = new SessionManager;
    $sessionManager->redirectNOTLoggedIN();

    $database = new Database("localhost", "livros", "root", "");
    $bookDAO = new BookDAO($database);

    //pegando dados que vão ser usados nos gráficos e transformando em json
    $BooksData = $bookDAO->getBooksDataforCharts((int)$_SESSION['user-id']);
    echo json_encode($BooksData);
    