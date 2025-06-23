<?php
declare(strict_types=1);
namespace database;

/**
 * Classe que consulta e retorna dados do UserModel ao controller
 */
class UserDAO
{
    /**
     * Método privado que recebe o DB (com a conexão)
     * @var Database
     */
    private $db;

    /**
     * Construtor que instancia um objeto UserDAO recebendo de parâmetro um objeto DB
     * @param Database $db
     */
    public function __construct(Database $db){
        $this->db = $db;
    }

    /**
     * Método que registra um UserModel no DB
     *
     * @param string $username
     * @param string $email
     * @param string $pass
     * @return void
     */
    public function RegisterUserDB(string $username, string $email, string $pass) : void{
        $passHash = password_hash($pass, PASSWORD_DEFAULT);
        try {
            $PDO = $this->db->getConnection();
            $res = $PDO->PREPARE("INSERT INTO users (username, email, pass) VALUES (:u, :e, :p)");
            $res->bindValue(":u", $username);
            $res->bindValue(":e", $email);
            $res->bindValue(":p", $passHash);
            $res->execute();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function UpdateUserDB(string $username, string $email, int $userID) : void {
        try {
            $PDO = $this->db->getConnection();
            $res = $PDO->prepare("UPDATE users SET username = :u, email = :e WHERE id = :i");
            $res->bindValue(":u", $username);
            $res->bindValue(":e", $email);
            $res->bindValue(":i", $userID);
            $res->execute();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Método que verifica se a senha do login é a mesma que existe no DB (hash)
     * @param string $username
     * @param string $pass
     * @return boolean
     */
    public function PassValidation(string $username, string $pass) : bool{
        try {
            $PDO = $this->db->getConnection();
            $res = $PDO->prepare("SELECT pass FROM users WHERE username = :u");
            $res->bindValue(":u", $username);
            $res->execute();
            $resultado = $res->fetch(\PDO::FETCH_ASSOC);

            $bool = password_verify($pass, $resultado['pass']) ? true : false;
            return $bool;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Método que verifica se o e-mail enviado pelo UserModel já foi cadastrado
     *
     * @param string $email
     * @return boolean
     */
    public function ExistsEmailDB(string $email) : bool{
        try {
            $PDO = $this->db->getConnection();
            $res = $PDO->prepare("SELECT COUNT(*) AS total FROM users WHERE email = :e");
            $res->bindValue(":e", $email);
            $res->execute();
            $resultado = $res->fetch(\PDO::FETCH_ASSOC);
            $bool = (int)$resultado['total'] === 0 ? false : true;
            return $bool;
            
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Método que verifica se o username enviado pelo UserModel já foi cadastrado
     *
     * @param string $username
     * @return boolean
     */
    public function ExistsUsernameDB(string $username) : bool{
        try {
            $PDO = $this->db->getConnection();
            $res = $PDO->prepare("SELECT COUNT(*) AS total FROM users WHERE username = :u");
            $res->bindValue(":u", $username);
            $res->execute();
            $resultado = $res->fetch(\PDO::FETCH_ASSOC);
            $bool = (int)$resultado['total'] === 0 ? false : true;
            return $bool;            
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Método para retornar todos dados de um UserModel passando o dado e o tipo de
     * coluna que vamos usar de parâmetro para realizar a busca
     * @param string|int $data
     * @param string $searchColumn
     * @return array
     */
    public function getUserData(string|int $data, string $searchColumn) : array{
        try {
            if($searchColumn === 'username'){
                $PDO = $this->db->getConnection();
                $res = $PDO->prepare("SELECT * FROM users WHERE username = :d");
                $res->bindValue(":d", $data);
                $res->execute();
                $resultado = $res->fetch(\PDO::FETCH_ASSOC);
                        
                return $resultado;
            }

            if($searchColumn === 'id'){
                $PDO = $this->db->getConnection();
                $res = $PDO->prepare("SELECT * FROM users WHERE id= :d");
                $res->bindValue(":d", $data);
                $res->execute();
                $resultado = $res->fetch(\PDO::FETCH_ASSOC);
                        
                return $resultado;
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }
}