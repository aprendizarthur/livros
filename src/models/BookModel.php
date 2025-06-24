<?php
declare(strict_types=1);
namespace models;

/**
 * Classe que modela as regras de negócio, valida e instancia um objeto Book
 */ 
class BookModel
{
    /**
     * Propriedade que recebe o id do user que vai estar associado ao livro
     * @var integer
     */
    private int $userID;
    /**
     * Propriedade que recebe o ano de publicação do livro
     * @var integer
     */
    private int $year;  
    /**
     * Propriedade que recebe o número de páginas do livro
     * @var integer
     */
    private int $pages; 
    /**
     * Propriedade que recebe a avaliação do usuário sobre o livro 0-100
     * @var integer
     */
    private string $author; 
    /**
     * Propriedade que recebe o título do livro
     *
     * @var string
     */
    private string $title;  
    /**
     * Propriedade que recebe o gênero do livro
     *
     * @var string
     */
    private string $genre;  
    /**
     * Propriedade que recebe as anotações do usuário sobre o livro
     *
     * @var string
     */
    private string $notes; 
    
    /**
     * Propriedade que recebe o status do livro
     *
     * @var string
     */
    private string $status; 
    
    //Usando trait para validação inicial de dados recebidos form de registro
    use \traits\AuthBook;
    
    /**
     * Método que pega os dados do POST referentes ao registro do livro para enviar ao LibraryController
     * @return array
     */
    public function getDataRegisterPOST() : array{
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-newbook'])){
            $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING));
            $author = trim(filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING));
            $genre = trim(filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_STRING));
            $status = trim(filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING));
            $pages = (int)filter_input(INPUT_POST, 'pages', FILTER_SANITIZE_NUMBER_INT);
            $year = (int)filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT);
            $dadosPOST = [
                'title' => $title,
                'author' => $author,
                'genre' => $genre,
                'pages' => $pages,
                'year' => $year,
                'status' => $status,
            ];
            $this->title = $title;
            $this->author = $author;
            $this->genre = $genre;
            $this->year = $year;
            $this->pages = $pages;
            $this->status = $status;
            return $dadosPOST;   
        }
        return [];
    }

    /**
     * Método que pega os dados do POST referentes ao update do livro para enviar ao LibraryController
     * @return array
     */
    public function getDataUpdatePOST() : array{
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-update'])){
            $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING));
            $author = trim(filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING));
            $genre = trim(filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_STRING));
            $pages = (int)filter_input(INPUT_POST, 'pages', FILTER_SANITIZE_NUMBER_INT);
            $year = (int)filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT);
            $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
            $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING);
            $dadosPOST = [
                'title' => $title,
                'author' => $author,
                'genre' => $genre,
                'pages' => $pages,
                'year' => $year,
                'status' => $status,
                'notes' => $notes,
            ];
            $this->title = $title;
            $this->author = $author;
            $this->genre = $genre;
            $this->year = $year;
            $this->pages = $pages;
            $this->status = $status;
            $this->notes = $notes;
            return $dadosPOST;   
        }
        return [];
    }
}