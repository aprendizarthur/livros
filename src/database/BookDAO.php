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

    /**
     * Método que retorna todos os dados da biblioteca que serão utilizados nos gráficos
     * @return array
     */
    public function getBooksDataforCharts(int $userID) : array {
        try {
            $PDO = $this->db->getConnection();
            $res = $PDO->prepare
            ("SELECT
                COUNT(CASE WHEN bookstatus = 'lidos' THEN 1 ELSE NULL END) AS total_livros_lidos,
                COUNT(CASE WHEN bookstatus = 'em-leitura' THEN 1 ELSE NULL END) AS total_livros_leitura,
                COUNT(CASE WHEN bookstatus = 'por-ler' THEN 1 ELSE NULL END) AS total_livros_ler,
                SUM(CASE WHEN bookstatus = 'lidos' THEN pages ELSE 0 END) AS total_paginas_lidos,
                SUM(CASE WHEN bookstatus = 'em-leitura' THEN pages ELSE 0 END) AS total_paginas_leitura,
                SUM(CASE WHEN bookstatus = 'por-ler' THEN pages ELSE 0 END) AS total_paginas_ler,
                COUNT(CASE WHEN pages < 200 THEN 1 ELSE NULL END) AS livros_200,
                COUNT(CASE WHEN pages >= 200 AND pages < 400 THEN 1 ELSE NULL END) AS livros_200_400,
                COUNT(CASE WHEN pages >= 400 AND pages < 600 THEN 1 ELSE NULL END) AS livros_400_600,
                COUNT(CASE WHEN pages >= 600 AND pages < 800 THEN 1 ELSE NULL END) AS livros_600_800,
                COUNT(CASE WHEN pages >= 800 AND pages < 100 THEN 1 ELSE NULL END) AS livros_800_1000,
                COUNT(CASE WHEN pages > 1000 THEN 1 ELSE NULL END) AS livros_1000,
                COUNT(CASE WHEN genre = 'Autoajuda' THEN 1 ELSE NULL END) AS total_autoajuda,
                COUNT(CASE WHEN genre = 'Aventura' THEN 1 ELSE NULL END) AS total_aventura,
                COUNT(CASE WHEN genre = 'Biografia' THEN 1 ELSE NULL END) AS total_biografia,
                COUNT(CASE WHEN genre = 'Drama' THEN 1 ELSE NULL END) AS total_drama,
                COUNT(CASE WHEN genre = 'Fantasia' THEN 1 ELSE NULL END) AS total_fantasia,
                COUNT(CASE WHEN genre = 'Ficção' THEN 1 ELSE NULL END) AS total_ficcao,
                COUNT(CASE WHEN genre = 'Ficção Científica' THEN 1 ELSE NULL END) AS total_ficcao_cientifica,
                COUNT(CASE WHEN genre = 'Filosofia' THEN 1 ELSE NULL END) AS total_filosofia,
                COUNT(CASE WHEN genre = 'História' THEN 1 ELSE NULL END) AS total_historia,
                COUNT(CASE WHEN genre = 'Infantil' THEN 1 ELSE NULL END) AS total_infantil,
                COUNT(CASE WHEN genre = 'Livro Didáticos' THEN 1 ELSE NULL END) AS total_livros_didaticos,
                COUNT(CASE WHEN genre = 'Mistério' THEN 1 ELSE NULL END) AS total_misterio,
                COUNT(CASE WHEN genre = 'Não-Ficção' THEN 1 ELSE NULL END) AS total_nao_ficcao,
                COUNT(CASE WHEN genre = 'Poesia' THEN 1 ELSE NULL END) AS total_poesia,
                COUNT(CASE WHEN genre = 'Romance' THEN 1 ELSE NULL END) AS total_romance,
                COUNT(CASE WHEN genre = 'Suspense' THEN 1 ELSE NULL END) AS total_suspense,
                COUNT(CASE WHEN genre = 'Tecnologia' THEN 1 ELSE NULL END) AS total_tecnologia
             FROM 
                books
             WHERE 
                id_user = :i");
            $res->bindValue(":i", $userID);
            $res->execute();
            $resultado = $res->fetch(\PDO::FETCH_ASSOC);
            return $resultado;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }
}