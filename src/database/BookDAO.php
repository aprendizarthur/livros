<?php
declare(strict_types=1);
namespace database;
use database\Database;

/**
 * Classe que consulta e retorna dados do UserModel ao controller
 */
class BookDAO
{
    private $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    /**
     * Método que registra um livro no DB
     * @param integer $userID
     * @param string $title
     * @param string $author
     * @param string $genre
     * @param integer $pages
     * @param integer $year
     * @return void
     */
    public function RegisterBookDB(int $userID, string $title, string $author, string $genre,  int $pages, int $year) : void {
        try {
            $PDO = $this->db->getConnection();
            $res = $PDO->prepare("INSERT INTO books (id_user, title, author, genre, pages, publicationyear) VALUES (:i, :t, :a, :g, :p, :y)");
            $res->bindValue(":i", $userID);
            $res->bindValue(":t", $title);
            $res->bindValue(":a", $author);
            $res->bindValue(":g", $genre);
            $res->bindValue(":p", $pages);
            $res->bindValue(":y", $year);
            $res->execute();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Método que exclui um livro do DB
     * @param integer $bookID
     * @return void
     */
    public function DeleteBookDB(int $bookID) : void {

    }

    /**
     * Método que retorna os livros da biblioteca do user ordenando pelo status do livro
     * @param integer $userID
     * @return array
     */
    public function UserLibrary(int $userID) : array {
        try {
            $PDO = $this->db->getConnection();
            $res = $PDO->prepare("SELECT id, bookstatus, genre, title, author, pages, notes, publicationyear, registerdate, readingdate FROM books WHERE id_user = :i ORDER BY bookstatus ASC");
            $res->bindValue(":i", $userID);
            $res->execute();
            $resultado = $res->fetchAll(\PDO::FETCH_ASSOC);
            return $resultado;   
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Método que realiza a querie no DB e retorna o resultado da pesquisa titulo || gênero
     * ordenando por status do livro
     * @param integer $userID
     * @param string $search
     * @return array
     */
    public function Search(int $userID, string $search) : array {
        $search = "%".$search."%";
        try {
            $PDO = $this->db->getConnection();
            $res = $PDO->prepare("SELECT id, bookstatus, genre, title, author, pages, notes, publicationyear, registerdate, readingdate FROM books WHERE (id_user = :i AND title LIKE :s) OR (id_user = :i AND genre LIKE :s) ORDER BY bookstatus ASC");
            $res->bindValue(":i", $userID);
            $res->bindValue(":s", $search);
            $res->execute();
            $resultado = $res->fetchAll(\PDO::FETCH_ASSOC);
            return $resultado;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }
}