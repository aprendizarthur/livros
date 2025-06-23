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
    public function RegisterBookDB(int $userID, string $title, string $author, string $status, string $genre,  int $pages, int $year) : void {
        try {
            $PDO = $this->db->getConnection();
            $res = $PDO->prepare("INSERT INTO books (id_user, title, author, bookstatus, genre, pages, publicationyear) VALUES (:i, :t, :a, :s, :g, :p, :y)");
            $res->bindValue(":i", $userID);
            $res->bindValue(":t", $title);
            $res->bindValue(":a", $author);
            $res->bindValue(":s", $status);
            $res->bindValue(":g", $genre);
            $res->bindValue(":p", $pages);
            $res->bindValue(":y", $year);
            $res->execute();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Método que exclui um livro do DB pelo ID 
     * @param integer $bookID
     * @return void
     */
    public function DeleteBookDB(int $bookID) : void {
        try {
            $PDO = $this->db->getConnection();
            $res = $PDO->prepare("DELETE FROM books WHERE id = :i");
            $res->bindValue(":i", $bookID);
            $res->execute();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Método que atualiza os dados do livro no DB
     * @param integer $bookID
     * @param string $title
     * @param string $author
     * @param string $genre
     * @param integer $pages
     * @param integer $year
     * @param string $status
     * @param string $notes
     * @return void
     */
    public function UpdateBookDB(int $bookID, string $title, string $author, string $genre, int $pages, int $year, string $status, string $notes) : void {
        try {
            $PDO = $this->db->getConnection();
            $res = $PDO->prepare("UPDATE books SET title = :t, author = :a, genre = :g, pages = :p, publicationyear = :y, bookstatus = :s, notes = :n WHERE id = :i");
            $res->bindValue(":i", $bookID);
            $res->bindValue(":t", $title);
            $res->bindValue(":a", $author);
            $res->bindValue(":g", $genre);
            $res->bindValue(":p", $pages);
            $res->bindValue(":y", $year);
            $res->bindValue(":s", $status);
            $res->bindValue(":n", $notes);
            $res->execute();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Método que retorna um array com todos os dados do livro pesquisado por ID
     * @param integer $bookID
     * @return array
     */
    public function AllBookData(int $bookID) : array {
        try {
            $PDO = $this->db->getConnection();
            $res = $PDO->prepare("SELECT * FROM books WHERE id = :i");
            $res->bindValue(":i", $bookID);
            $res->execute();
            $resultado = $res->fetch(\PDO::FETCH_ASSOC);
            return $resultado;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
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
            $res = $PDO->prepare("SELECT id, bookstatus, genre, title, author, pages, notes, publicationyear, registerdate, readingdate FROM books WHERE (id_user = :i AND title LIKE :s) OR (id_user = :i AND genre LIKE :s) OR (id_user = :i AND bookstatus LIKE :s) ORDER BY bookstatus ASC");
            $res->bindValue(":i", $userID);
            $res->bindValue(":s", $search);
            $res->execute();
            $resultado = $res->fetchAll(\PDO::FETCH_ASSOC);
            return $resultado;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Método que verifica se o id de livro passado como parâmetro existe no DB
     * @param integer $bookID
     * @return boolean
     */
    public function VerifyIDBookExists(int $bookID) : bool {
        try {
            $PDO = $this->db->getConnection();
            $res = $PDO->prepare("SELECT COUNT(*) AS total FROM books WHERE id = :i LIMIT 1");
            $res->bindValue(":i", $bookID);
            $res->execute();
            $resultado = $res->fetchAll(\PDO::FETCH_ASSOC);

            $bool = $resultado[0]['total'] == 1 ? true : false;
            return $bool;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Método que verifica se o id de livro que foi passado é dono do id de usuário
     * que foi passado de parâmetro
     * @param integer $bookID
     * @param integer $userID
     * @return boolean
     */
    public function VerifyOwnerBookFromGET(int $bookID, int $userID) : bool {
        try {
            $PDO = $this->db->getConnection();
            $res = $PDO->prepare("SELECT id_user FROM books WHERE id = :i");
            $res->bindValue(":i", $bookID);
            $res->execute();
            $resultado = $res->fetch(\PDO::FETCH_ASSOC);

            if(empty($resultado['id_user'])){
                return false;}

            $bool = $resultado['id_user'] === $userID ? true : false;
            
            return $bool;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }
}