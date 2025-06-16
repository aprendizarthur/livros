<?php
declare(strict_types=1);
namespace traits;

/**
 * Trait responsável por validar inputs recebidos do post para registro de usuário
 */
trait AuthUser
{
    /**
     * Método que verifica a validez do tamanho de username do usuário
     * @param string $username
     * @return boolean
     */
    public function AuthUsername(string $username) : bool{
        return !preg_match('/^[a-zA-Z]+$/', $username) || mb_strlen($username) < 3 || mb_strlen($username) > 50 ? false : true;
    }

    /**
     * Método que verifica a validez do email do usuário
     * @param string $email
     * @return boolean
     */
    public function AuthEmail(string $email) : bool{
        $bool =  filter_var($email, FILTER_VALIDATE_EMAIL) ? true : false;
        return $bool;
    }

    /**
     * Método que verifica a validez da senha do usuário
     * @param string $pass
     * @return boolean
     */
    public function AuthPass(string $pass) : bool{
        $bool = mb_strlen($pass) < 8 ? false : true;
        return $bool;
    }
    
    /**
     * Método que verifica se a senha e a confirmação são idênticas
     * @param string $pass
     * @param string $passC
     * @return boolean
     */
    public function AuthPassC(string $pass, string $passC) : bool{
        $bool = $pass === $passC ? true : false;
        return $bool;
    }
}