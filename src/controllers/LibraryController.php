<?php
declare(strict_types=1);
namespace controllers;
use database\BookDAO;
use models\BookModel;

class LibraryController
{
    private $bookModel;
    private $bookDAO;

    /**
     * Método que registra um livro no DB associado a um usuário, pegando dados do POST pelo
     * BookModel e realizando queries pelo BookDAO
     * @param BookModel $bookModel
     * @param BookDAO $bookDAO
     * @param array $dadosPOST
     * @return void
     */
    public function RegisterBook(BookModel $bookModel, BookDAO $bookDAO, array $dadosPOST) : void {
        $this->bookModel = $bookModel;
        $this->bookDAO = $bookDAO;
        
        if(!empty($dadosPOST)){
            //validando inputs na classe bookModel
            if(!$this->bookModel->AuthTitle($dadosPOST['title'])){
                throw new \Exception("Título inválido");
            }

            if(!$this->bookModel->AuthAuthor($dadosPOST['author'])){
                throw new \Exception("Autor inválido");
            }

            if(!$this->bookModel->AuthPages($dadosPOST['pages'])){
                throw new \Exception("Nº de páginas inválido");
            }

            if(!$this->bookModel->AuthYear($dadosPOST['year'])){
                throw new \Exception("Um livro não pode ser registrado como publicado em um ano futuro ou negativo");
            }

            //cadastrando usuário no DB com BookDAO recebendo os dados do POST validados
            $this->bookDAO->RegisterBookDB((int)$_SESSION['user-id'], $dadosPOST['title'], $dadosPOST['author'], $dadosPOST['status'], $dadosPOST['genre'], (int)$dadosPOST['pages'] ?? 0, (int)$dadosPOST['year'] ?? 0);
            //encaminhar user para library
            header("Location: library.php");
            exit();
        }
    }

    /**
     * Método que exclui um livro passando o ID vindo do get para o BookDAO
     * @param BookDAO $bookDAO
     * @return void
     */
    public function DeleteBook(BookDAO $bookDAO) : void{
        $this->bookDAO = $bookDAO;
        $bookID = (int)filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        //esperando o submit-delete no POST
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-delete'])){
            //excluindo o livro
            $this->bookDAO->DeleteBookDB($bookID);

            //encaminhando usuário para a biblioteca
            header("Location: library.php");
            exit();
        }
    }

    /**
     * Método que atualiza os dados do livro no DB
     * @param BookDAO $bookDAO
     * @return void
     */
    public function UpdateBook(BookModel $bookModel, BookDAO $bookDAO, array $dadosPOST) : void {
        $this->bookModel = $bookModel;
        $this->bookDAO = $bookDAO;

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-update'])){
            //pegando id do livro que está sendo editado pelo GET
            $bookID = (int)filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

            //atualizando livro no DB com bookDAO
            $this->bookDAO->UpdateBookDB((int)$bookID, $dadosPOST['title'], $dadosPOST['author'], $dadosPOST['genre'], (int)$dadosPOST['pages'], (int)$dadosPOST['year'], $dadosPOST['status'], $dadosPOST['notes']);
            
            //recarregando página
            header("Location: book.php?id={$bookID}");
            exit();
        }
    }

    /**
     * Método que exibe os dados atuais do livro que vai ser editado no form
     * @param BookDAO $bookDAO
     * @return void
     */
    public function ShowBookUpdateForm(BookDAO $bookDAO) : void {
        $this->bookDAO = $bookDAO;
        $bookID = (int)filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        
        //pegando dados do livro no DB com bookDAO
        $bookDATA = $this->bookDAO->getBookData($bookID);

        //formatando status do livro vindo do DB para exibir pro usuário (ex: por-ler -> Por Ler)
        $formatedStatus = match($bookDATA['bookstatus']){
                                    'em-leitura' => 'Lendo',
                                    'por-ler' => 'Por Ler',
                                    'lidos' => 'Lido',
                                    };

        //exibindo form com os dados do livro
        echo '
            <form id="form-update-book" class="form dm-sans-regular text-left" method="POST">
                    <div class="form-group">
                        <label class="dm-sans-bold" for="title">Título</label>
                        <input value="'.$bookDATA['title'].'" class="form-control" type="text" name="title" id="title">
                    </div>

                    <div class="form-group mt-3">
                        <label class="dm-sans-bold" for="notes">Ficha de leitura</label>
                        <textarea class="form-control" name="notes" id="notes" rows="5">'.$bookDATA['notes'].'</textarea>
                        <small class="dm-sans-light" style="color: #C7C7C7"><i>Editado em '.$bookDATA['notesupdate'].'</i></small>
                    </div>

                    <details class="mb-3">
                        <summary><h3 class="dm-sans-bold d-inline mr-2">Dados Técnicos</h3><small class="d-inline small-details" style="color: #C7C7C7; text-decoration: none;">(Clique para expandir)</small></summary>
                            <hr>
                            <div class="form-group">
                                <label class="dm-sans-bold" for="author">Autor</label>
                                <input class="form-control" value="'.$bookDATA['author'].'" type="text" name="author" id="author">
                            </div>

                            <div class="form-group w-45" style="float: left;">
                                <label class="dm-sans-bold" for="year ">Ano Publicação</label>
                                <input class="d-inline form-control" value="'.$bookDATA['publicationyear'].'" type="number" name="year" id="year">
                            </div>
        
                            <div class="form-group w-45" style="float: right;">
                                <label class="dm-sans-bold" for="pages">Páginas</label>
                                <input class="d-inline form-control" value="'.$bookDATA['pages'].'" type="number" name="pages" id="pages">
                            </div>

                            <div class="form-group" style="clear: both;">
                                    <label class="dm-sans-bold" for="genre">Gênero</label>
                                    <select value="Fantasia" class="form-control pr-1" name="genre" id="genre">
                                    <option selected value="'.$bookDATA['genre'].'">'.$bookDATA['genre'].'</option>
                                    <option value="Autoajuda">Autoajuda</option>
                                    <option value="Aventura">Aventura</option>
                                    <option value="Biografia">Biografia</option>
                                    <option value="Drama">Drama</option>
                                    <option value="Fantasia">Fantasia</option>
                                    <option value="Ficção">Ficção</option>
                                    <option value="Ficção científica">Ficção Científica</option>
                                    <option value="Filosofia">Filosofia</option>
                                    <option value="Historia">História</option>
                                    <option value="Infantil">Infantil</option>
                                    <option value="Livro Didaticos">Livros Didáticos</option>
                                    <option value="Mistério">Mistério</option>
                                    <option value="Não-ficção">Não Ficção</option>
                                    <option value="Poesia">Poesia</option>
                                    <option value="Romance">Romance</option>
                                    <option value="Suspense">Suspense</option>
                                    <option value="Tecnologia">Tecnologia</option>
                                </select>
                            </div>
                    </details>

                    <details class="'.$bookDATA['bookstatus'].'">
                        <summary><h3 class="dm-sans-bold d-inline mr-2">Status do Livro</h3><small class="d-inline small-details" style="color: #C7C7C7; text-decoration: none;">(Clique para expandir)</small></summary>
                            <hr>
                            <div class="form-group">
                                    <select class="form-control pr-1" name="status" id="status">
                                    <option selected value="'.$bookDATA['bookstatus'].'">'.$formatedStatus.'</option>
                                    <option class="por-ler" value="por-ler">Por Ler</option>
                                    <option class="em-leitura" value="em-leitura">Lendo</option>
                                    <option class="lidos" value="lidos">Lido</option>
                                </select>
                            </div>
                    </details>
                           
                    <button class="btn btn-primary dm-sans-bold w-100 mt-3" name="submit-update" type="submit">Salvar</button>
                </form>    
        ';
    }

    /**
     * Método que realiza pesquisa no DB e printa na view o resultado
     * @param BookDAO $bookDAO
     * @return void
     */
    public function SearchBook(BookDAO $bookDAO) : void {
        $this->bookDAO = $bookDAO;
        $userID = (int)$_SESSION['user-id'];
        //sanizitando a pesquisa do get e exibindo resultado para o usuário
        if(isset($_GET['search'])){
            $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);

            //pegando dados de resultado da pesquisa no DB
            $data = $this->bookDAO->Search($userID, $search);
            $totalResultados = (int)count($data);

            //exibindo resultados
            if(empty($data)){
                //se não tiver livros no DB exibe atalho para adicionar livro
                echo '
                    <div class="col-12 col-md-6 col-lg-4 box-add-book text-center" style="color: #C7C7C7;">
                        <a href="library.php" style="color: #C7C7C7;">
                            <i class="fa-solid fa-heart-crack fa-xl mb-4"></i>
                            <p class="dm-sans-bold">Nenhum resultado encontrado</p>   
                            <small class="dm-sans-bold">Clique aqui e volte para biblioteca</small>                    
                        </a>
                    </div>
                ';
            } else {

                //printando atalho que volta para a biblioteca
                echo '
                <div class="col-12 col-md-6 col-lg-4 box-add-book text-center" style="color: #C7C7C7;">
                    <a href="library.php" style="color: #C7C7C7;">
                    <i class="fa-solid fa-magnifying-glass fa-xl mb-4"></i>
                        <p class="dm-sans-bold">Sua pesquisa teve '.$totalResultados.' resultado(s)</p> 
                        <small class="dm-sans-bold">Clique aqui e volte para biblioteca</small>              
                    </a>
                </div>
                ';

                foreach($data as $book){
                    //determinando se existe notes e data de leitura
                    $existsNotes = empty($book['notes']) ? "Não" : "Sim";
                    $iconeStatus = match($book['bookstatus']){
                                    'em-leitura' => 'fa-clock',
                                    'por-ler' => 'fa-circle-exclamation',
                                    'lidos' => 'fa-circle-check',
                                    };

                    echo '
                        <div class="col-12 col-md-6 col-lg-4 box-book '.$book['bookstatus'].'">
                            <article>
                                <a href="book.php?id='.$book['id'].'">
                                    <header>
                                        <i class="fa-solid '.$iconeStatus.' fa-xl mr-1 '.$book['bookstatus'].'-livros"></i>
                                        <span class="dm-sans-light '.$book['bookstatus'].' px-2">'.$book['genre'].'</span>
                                        <h2 class="dm-sans-bold mt-3 mb-0">'.$book['title'].'</h2>
                                        <small class="dm-sans-light"><i>'.$book['author'].', '.$book['publicationyear'].'</i></small>
                                        <hr class="my-2">
                                    </header>
                                    <footer>
                                        <span><i class="fa-solid fa-file mr-1 fa-sm '.$book['bookstatus'].'-livros-light"></i><small class="dm-sans-light mr-1">'.$book['pages'].'</small></span>
                                        <span><i class="fa-solid fa-clipboard mr-1 '.$book['bookstatus'].'-livros-light"></i><small class="dm-sans-light mr-1">'.$existsNotes.'</small></span>
                                        <span><i class="fa-solid fa-database fa-sm mr-1 '.$book['bookstatus'].'-livros-light"></i><small class="dm-sans-light mr-1">'.$book['registerdate'].'</small></span>
                                    </footer>
                                </a>
                            </article>
                        </div>
                    ';
                }
            }
        }
    }

    /**
     * Método que ao abrir a página book.php verifica se o livro que tem o id passado
     * no get existe, e se existir verifica se ele pertence ao usuário que está acessando
     * @param BookDAO $bookDAO
     * @return void
     */
    public function VerifyBookAcess(BookDAO $bookDAO) : void {
        $this->bookDAO = $bookDAO;
        $userID = (int)$_SESSION['user-id'];
        $bookID = (int)filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        //verificando no bookDAO se o livro existe
        if(!$this->bookDAO->VerifyIDBookExists($bookID)){
            header("Location: library.php");
            exit();
        }

        //verificando no bookDAO se o livro pertence ao usuário
        if(!$this->bookDAO->VerifyOwnerBookFromGET($bookID, $userID)){
            header("Location: library.php");
            exit();
        }
    }

    /**
     * Método que exibe todos os livros da biblioteca do usuário
     * @param BookDAO $bookDAO
     * @return void
     */
    public function ShowLibrary(BookDAO $bookDAO) : void {
        //enquanto não houver pesquisa executa o método
        if(!isset($_GET['search'])){
            $this->bookDAO = $bookDAO;

            //pegando dados da biblioteca do usuário
            $data = $this->bookDAO->UserLibrary((int)$_SESSION['user-id']);

            //exibindo resultados
            if(empty($data)){
                //se não tiver livros no DB exibe atalho para adicionar livro
                echo '
                    <div class="col-12 col-md-6 col-lg-4 box-add-book">
                        <a href="newbook.php">
                            <i class="fa-solid fa-circle-plus fa-2xl"></i>
                        </a>            
                    </div>
                ';
            } else {
                foreach($data as $book){
                    //determinando se existe notes, data de leitura e qual o ícone de status do livro
                    $existsNotes = empty($book['notes']) ? "Não" : "Sim";
                    $iconeStatus = match($book['bookstatus']){
                                    'em-leitura' => 'fa-clock',
                                    'por-ler' => 'fa-circle-exclamation',
                                    'lidos' => 'fa-circle-check',
                                    };
                    
                    echo '
                        <div class="col-12 col-md-6 col-lg-4 box-book '.$book['bookstatus'].'">
                            <article>
                                <a href="book.php?id='.$book['id'].'">
                                    <header>
                                        <i class="fa-solid '.$iconeStatus.' fa-xl mr-1 '.$book['bookstatus'].'-livros"></i>
                                        <span class="dm-sans-light '.$book['bookstatus'].' px-2">'.$book['genre'].'</span>
                                        <h2 class="dm-sans-bold mt-3 mb-0">'.$book['title'].'</h2>
                                        <small class="dm-sans-light"><i>'.$book['author'].', '.$book['publicationyear'].'</i></small>
                                        <hr class="my-2">
                                    </header>
                                    <footer>
                                        <span><i class="fa-solid fa-file mr-1 fa-sm '.$book['bookstatus'].'-livros-light"></i><small class="dm-sans-light mr-1">'.$book['pages'].'</small></span>
                                        <span><i class="fa-solid fa-clipboard mr-1 '.$book['bookstatus'].'-livros-light"></i><small class="dm-sans-light mr-1">'.$existsNotes.'</small></span>
                                        <span><i class="fa-solid fa-database fa-sm mr-1 '.$book['bookstatus'].'-livros-light"></i><small class="dm-sans-light mr-1">'.$book['registerdate'].'</small></span>
                                    </footer>
                                </a>
                            </article>
                        </div>
                    ';
                }
            }
        }
    }
}