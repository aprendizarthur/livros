<?php
declare(strict_types=1);
namespace models;


/**
 * Classe que modela as regras de negócio, valida e instancia um objeto User
 */ 
class UserModel
{
    /**
     * Propriedade privada que armazena o username do usuário
     * @var string
     */
    private string $username;
    /**
     * Propriedade privada que armazena o email do usuário
     * @var string
     */
    private string $email;
    /**
     * Propriedade privada que armazena a senha do usuário (apenas p/ validação)
     * @var string
     */
    private string $pass;
    /**
     * Propriedade privada que armazena a confirmação da senha (apenas p/ validação)
     * @var string
     */
    private string $passC;
        
    
    //Usando trait para validação inicial de dados recebidos form de registro
    use \traits\AuthUser;
    
    /**
     * Método que retorna os dados recebidos no POST do formulário de login
     *
     * @return array
     */
    public function getDataLoginPOST() : array{
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])){
            $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
            $pass = trim(filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING));

            $dadosPOST = ['username' => $username, 'pass' => $pass];
            $this->username = $username;
            $this->pass = $pass;
            
            return $dadosPOST;    
        }
        return [];
    }

    /**
     * Método que recebe os dados de registro do POST, sanitiza-os e retorna um array associativo
     * com eles
     * @return array
     */
    public function getDataRegisterPOST() : array{
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])){
            $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
            $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING));
            $pass = trim(filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING));
            $passC = trim(filter_input(INPUT_POST, 'passC', FILTER_SANITIZE_STRING));
                        
            $dadosPOST = ['username' => $username, 'email' => $email, 'pass' => $pass, 'passC' => $passC,];

            $this->username = $dadosPOST['username'];
            $this->email = $dadosPOST['email'];
            $this->pass = $dadosPOST['pass'];
            $this->passC = $dadosPOST['passC'];

            return $dadosPOST;
        }
        return [];
    }

    /**
     * Método que retorna os dados do POST para o formulário de atualizar dados usuário
     * @return array
     */
    public function getDataUpdatePOST() : array {
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-update'])){
            $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
            $email = $_POST['email'];
            $pass = trim(filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING));
            $dadosPOST = [
                'username' => $username,
                'email' => $email,
                'pass' => $pass,
            ];
            $this->username = $username;
            $this->email = $email;
            $this->pass = $pass;
            return $dadosPOST;   
        }
        return [];
    }
}