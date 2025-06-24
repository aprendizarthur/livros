<?php
declare(strict_types=1);
namespace controllers;
use database\{Database, UserDAO};
use models\UserModel;

/**
 * Classe que instancia um objeto AuthController responsável por gerenciar ações no sistema
 * com os dados dos models e exibindo dados nas views
 */
class AuthController
{
    /**
     * Propriedade privada que recebe o objeto UserModel
     * @var UserModel
     */
    private $userModel;
    /**
     * Propriedade privada que recebe um objeto UserDAO
     * @var UserDAO
     */
    private $userDAO;

    /**
     * Método que realiza o logout do usuário
     * @return void
     */
    public function LogoutUser() : void{
        if($_SERVER['REQUEST_METHOD'] && isset($_POST['submit-logout'])){
            session_unset();
            session_destroy();
            header("Location: ../auth/login.php");
            exit();
        }
    }

    /**
     * Método que realiza o login do usuário validando inputs do UserModel e 
     * do DB com UserDAO
     * @param UserDAO $userDAO
     * @param UserModel $userModel
     * @param array $dadosPOST
     * @return void
     */
    public function LoginUser(UserDAO $userDAO, UserModel $userModel, array $dadosPOST) : void{
        $this->userDAO = $userDAO;
        $this->userModel = $userModel;

        if(!empty($dadosPOST)){
            //validando inputs do UserModel no DB com UserDAO
            if(!$this->userDAO->ExistsUsernameDB($dadosPOST['username'])){
                throw new \Exception("Usuário não encontrado");
            }

            //validando se a senha é correta
            if(!$this->userDAO->PassValidation($dadosPOST['username'], $dadosPOST['pass'])){
                throw new \Exception("Senha incorreta");
            }

            //enviando dados para session e encaminhando para a library
            $dadosUser = $this->userDAO->getUserData($dadosPOST['username'], "username");
            $_SESSION['user-username'] = $dadosPOST['username'];
            $_SESSION['user-email'] = $dadosUser['email'];
            $_SESSION['user-id'] = (int)$dadosUser['id'];
            header("Location: ../books/library.php");
            exit();
        }
    }

    /**
     * Método que valida e realiza o registro do UserModel no DB
     * @param UserDAO $userDAO
     * @param UserModel $userModel
     * @param array $dadosPOST
     * @return void
     */
    public function RegisterUser(UserDAO $userDAO, UserModel $userModel, array $dadosPOST) : void{
        $this->userDAO = $userDAO;
        $this->userModel = $userModel;
    
        if(!empty($dadosPOST)){
            //validando inputs do UserModel na classe
            if(!$userModel->AuthUsername($dadosPOST['username'])){
                throw new \Exception("Nome inválido, ele deve conter apenas letras sem espaços e ter entre 3-50 caracteres");
            }
    
            if(!$userModel->AuthEmail($dadosPOST['email'])){
                throw new \Exception("E-mail inválido");
            }
    
            if(!$userModel->AuthPass($dadosPOST['pass'])){
                throw new \Exception("Senha muito curta");
            }
    
            if(!$userModel->AuthPassC($dadosPOST['pass'], $dadosPOST['passC'])){
                throw new \Exception("Senhas não coincidem");
            }
    
            //validando inputs do UserModel no DB com UserDAO
            if($userDAO->ExistsEmailDB($dadosPOST['email'])){
                throw new \Exception("E-mail já registrado por outro usuário");
            }
    
            if($userDAO->ExistsUsernameDB($dadosPOST['username'])){
                throw new \Exception("Username já registrado por outro usuário");
            }
    
            //cadastrando usuário no DB com UserDAO recebendo os dados do POST validados
            $this->userDAO->RegisterUserDB($dadosPOST['username'], $dadosPOST['email'], $dadosPOST['pass']);
    
            //enviando dados para a session e encaminhando para a library
            $dadosUser = $this->userDAO->getUserData($dadosPOST['username'], 'username');
            $_SESSION['user-email'] = $dadosPOST['email'];
            $_SESSION['user-username'] = $dadosPOST['username'];
            $_SESSION['user-id'] = (int)$dadosUser['id'];
            header("Location: ../books/library.php");
            exit();
        }
    }

    public function UpdateUser(UserModel $userModel, UserDAO $userDAO, array $dadosPOST) : void {
        if(!empty($dadosPOST)){

            //validando senha do usuário com userDAO
            if(!$userDAO->PassValidation($_SESSION['user-username'], $dadosPOST['pass'])){
                throw new \Exception("Senha incorreta");
            }

            //validando formato dos inputs editáveis e se já existem no DB
            if(!$userModel->AuthUsername($dadosPOST['username'])){
                throw new \Exception("Nome inválido, ele deve conter apenas letras sem espaços e ter entre 3-50 caracteres");
            }
            
            if($dadosPOST['username'] != $_SESSION['user-username']){
                if($userDAO->ExistsUsernameDB($dadosPOST['username'])){
                    throw new \Exception("Username já registrado por outro usuário");
                }
            }

            if(!$userModel->AuthEmail($dadosPOST['email'])){
                throw new \Exception("E-mail inválido");
            }

            if($dadosPOST['email'] != $_SESSION['user-email']){
                if($userDAO->ExistsEmailDB($dadosPOST['email'])){
                    throw new \Exception("E-mail já registrado por outro usuário");
                }
            }

            //atualizando dados e recarregando página
            $userDAO->UpdateUserDB($dadosPOST['username'], $dadosPOST['email'], (int)$_SESSION['user-id']);
            header("Location: update.php");
            exit();
        }
    }

    /**
     * Método que exibe o formulário para editar dados do usuário
     * @param UserDAO $userDAO
     * @return void
     */
    public function ShowUserUpdateForm(UserDAO $userDAO) : void {
        //pegando id do usuário
        $userID = (int)$_SESSION['user-id'];
        $userDATA = $userDAO->getUserData($userID, 'id');

        echo '
                <form id="form-update-book" class="form dm-sans-regular text-left" method="POST">
                    <div class="form-group">
                        <label class="dm-sans-bold" for="username">Username</label>
                        <input value="'.$userDATA['username'].'" class="form-control" type="text" name="username" id="username">
                    </div>
                    <div class="form-group">
                        <label class="dm-sans-bold" for="email">E-mail</label>
                        <input value="'.$userDATA['email'].'" class="form-control" type="email" name="email" id="email">
                    </div>
                    <div class="form-group">
                        <label class="dm-sans-bold" for="password">Digite a sua senha para confirmar alterações</label>
                        <input required class="form-control" type="password" name="pass" id="pass">
                    </div>

                    <button class="btn btn-primary dm-sans-bold w-100 mt-3" name="submit-update" type="submit">Atualizar</button>
                </form>    
        ';  
    }
}