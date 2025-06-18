<?php
declare(strict_types=1);
namespace database;

//Classe que instancia um objeto database que contém a conexão com o DB
class Database
{
    private static $PDO = null;
    private string $dsn;
    private string $user;
    private string $pass;

    public function __construct(string $host, string $dbname, string $user, string $pass){
        $this->dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
        $this->user = $user;
        $this->pass = $pass;
    }

    public function getConnection(){
        try {
            self::$PDO = new \PDO($this->dsn, $this->user, $this->pass);
        } catch (\PDOException $e) {
            throw new \Exception('Não foi possível conectar ao banco de dados.');
        }

        return self::$PDO;
    }
}