<?php
declare(strict_types=1);
namespace core;

//classe que isntancia um objeto SessionManager que gerencia  a $_SESSION (inicializando e redirecionando)
class SessionManager
{
    /**
     * Método construtor que inicia a session
     */
    public function __construct(){
        session_start();
    }

    /**
     * Método que redireciona usuário logado para o painel
     * @return void
     */
    public function redirectLoggedIN() : void{
        if(isset($_SESSION['user-email'])){
            header("Location: ../books/library.php"); 
        }
    }

    /**
     * Método que redireciona usuário deslogado para o form de login
     * @return void
     */
    public function redirectNOTLoggedIN() : void{
        if(!isset($_SESSION['user-email'])){
            header("Location: ../auth/login.php"); 
        }
    }
}